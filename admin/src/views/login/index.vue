<template>
  <div class="login-container">
    <el-form ref="loginForm" :model="loginForm" :rules="loginRules" class="login-form" auto-complete="on" label-position="left">
      <h3 class="title">admin-login</h3>
      <el-form-item prop="username">
        <span class="svg-container">
          <svg-icon icon-class="user" />
        </span>
        <el-input v-model="loginForm.username" name="username" type="text" auto-complete="on" placeholder="username" />
      </el-form-item>
      <el-form-item prop="password">
        <span class="svg-container">
          <svg-icon icon-class="password" />
        </span>
        <el-input
          v-model="loginForm.password"
          :type="pwdType"
          name="password"
          auto-complete="on"
          placeholder="password"
          @keyup.enter.native="handleLogin"
        />
        <span class="show-pwd" @click="showPwd">
          <svg-icon icon-class="eye" />
        </span>
      </el-form-item>
      <el-form-item>
        <el-row>
          <el-col id="captcha" :span="10" style="height: 42px;">
            <p id="wait" class="show" />
            <p id="notice" class="hide">请先完成验证</p>
          </el-col>
        </el-row>
      </el-form-item>
      <el-form-item>
        <el-button :loading="loading" type="primary" style="width:100%;" @click.native.prevent="handleLogin">
          Sign in
        </el-button>
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
import '../../assets/js/gt'
import request from '../../utils/request'
import { isvalidUsername } from '@/utils/validate'

export default {
  name: 'Login',
  data() {
    const validateUsername = (rule, value, callback) => {
      if (!isvalidUsername(value)) {
        callback(new Error('请输入正确的用户名'))
      } else {
        callback()
      }
    }
    const validatePass = (rule, value, callback) => {
      if (value.length < 5) {
        callback(new Error('密码不能小于5位'))
      } else {
        callback()
      }
    }
    return {
      loginForm: {
        username: '',
        password: ''
      },
      loginRules: {
        username: [{ required: true, trigger: 'blur', validator: validateUsername }],
        password: [{ required: true, trigger: 'blur', validator: validatePass }]
      },
      loading: false,
      pwdType: 'password',
      redirect: undefined,
      gtCapValid: '',
      uuidData: ''
    }
  },
  watch: {
    $route: {
      handler: function(route) {
        this.redirect = route.query && route.query.redirect
      },
      immediate: true
    }
  },
  created() {
    this.init()
  },
  methods: {
    showPwd() {
      if (this.pwdType === 'password') {
        this.pwdType = ''
      } else {
        this.pwdType = 'password'
      }
    },
    handleLogin() {
      if (!this.gtCapValid) {
        this.$message({
          message: '请先点击按钮验证',
          type: 'warning'
        })
        return false
      }
      this.$refs.loginForm.validate(valid => {
        if (valid) {
          this.loading = true
          let params = this.mergeJsonObject(this.loginForm, { uuid: this.uuidData })
          params = this.mergeJsonObject(params, this.gtCapValid)

          this.$store.dispatch('user/login', params).then(() => {
            this.loading = false
            this.$router.push({ path: this.redirect || '/' })
          }).catch(() => {
            this.init()
            this.loading = false
          })
        } else {
          // console.log('error submit!!')
          return false
        }
      })
    },
    uuid() {
      const s = []
      const hexDigits = '0123456789abcdef'
      for (let i = 0; i < 36; i++) {
        s[i] = hexDigits.substr(Math.floor(Math.random() * 0x10), 1)
      }
      s[14] = '4' // bits 12-15 of the time_hi_and_version field to 0010
      s[19] = hexDigits.substr((s[19] & 0x3) | 0x8, 1) // bits 6-7 of the clock_seq_hi_and_reserved to 01
      s[8] = s[13] = s[18] = s[23] = '-'
      return s.join('')
    },
    mergeJsonObject(jsonbject1, jsonbject2) {
      var resultJsonObject = {}
      for (const attr in jsonbject1) {
        resultJsonObject[attr] = jsonbject1[attr]
      }
      for (const attr1 in jsonbject2) {
        resultJsonObject[attr1] = jsonbject2[attr1]
      }
      return resultJsonObject
    },
    init() {
      const that = this
      this.uuidData = this.uuid()
      this.gtCapValid = ''
      request.get('/api/user/startCaptcha?uuid=' + this.uuidData).then(res => {
        const data = res.data
        if (data.challenge && data.gt) {
          // 调用 initGeetest 进行初始化
          // 参数1：配置参数
          // 参数2：回调，回调的第一个参数验证码对象，之后可以使用它调用相应的接口
          window.initGeetest({
            // 以下 4 个配置参数为必须，不能缺少
            gt: data.gt,
            challenge: data.challenge,
            offline: !data.success, // 表示用户后台检测极验服务器是否宕机
            new_captcha: data.new_captcha, // 用于宕机时表示是新验证码的宕机

            product: 'float', // 产品形式，包括：float，popup
            width: '448px'
            // 更多配置参数说明请参见：http://docs.geetest.com/install/client/web-front/
          }, function(captchaObj) {
            document.getElementById('captcha').innerHTML = ''
            captchaObj.appendTo('#captcha') // 同时插入三个input表单时，geetest_challenge, geetest_validate, geetest_seccode
            captchaObj.onReady(function() {
              // document.getElementById("wait").hide();
            })

            captchaObj.onSuccess(function() {
              that.gtCapValid = captchaObj.getValidate()
            })
            captchaObj.onError(function() {
              that.$notify.error({
                title: '错误',
                message: '网络错误，请稍后重试'
              })
            })
          })
        }
      })
    }
  }
}
</script>

<style rel="stylesheet/scss" lang="scss">
$bg:#2d3a4b;
$light_gray:#eee;

/* reset element-ui css */
.login-container {
  .el-input {
    display: inline-block;
    height: 47px;
    width: 85%;
    input {
      background: transparent;
      border: 0px;
      -webkit-appearance: none;
      border-radius: 0px;
      padding: 12px 5px 12px 15px;
      color: $light_gray;
      height: 47px;
      &:-webkit-autofill {
        -webkit-box-shadow: 0 0 0px 1000px $bg inset !important;
        -webkit-text-fill-color: #fff !important;
      }
    }
  }
  .el-form-item {
    border: 1px solid rgba(255, 255, 255, 0.1);
    background: rgba(0, 0, 0, 0.1);
    border-radius: 5px;
    color: #454545;
  }
}

</style>

<style rel="stylesheet/scss" lang="scss" scoped>
$bg:#2d3a4b;
$dark_gray:#889aa4;
$light_gray:#eee;
.login-container {
  position: fixed;
  height: 100%;
  width: 100%;
  background-color: $bg;
  .login-form {
    position: absolute;
    left: 0;
    right: 0;
    width: 520px;
    max-width: 100%;
    padding: 35px 35px 15px 35px;
    margin: 120px auto;
  }
  .tips {
    font-size: 14px;
    color: #fff;
    margin-bottom: 10px;
    span {
      &:first-of-type {
        margin-right: 16px;
      }
    }
  }
  .svg-container {
    padding: 6px 5px 6px 15px;
    color: $dark_gray;
    vertical-align: middle;
    width: 30px;
    display: inline-block;
  }
  .title {
    font-size: 26px;
    font-weight: 400;
    color: $light_gray;
    margin: 0px auto 40px auto;
    text-align: center;
    font-weight: bold;
  }
  .show-pwd {
    position: absolute;
    right: 10px;
    top: 7px;
    font-size: 16px;
    color: $dark_gray;
    cursor: pointer;
    user-select: none;
  }
}
</style>
