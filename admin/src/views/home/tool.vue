<template>
  <div>
    <nav-bar />

    <el-form ref="form" :model="form" :rules="rules" label-width="120px">
      <el-form-item label="" prop="description">
        <el-col :span="10">
          <span>输入的内容</span>
        </el-col>
        <el-col :span="2">
          <span>&nbsp;</span>
        </el-col>
        <el-col :span="10">
          <span>输出的内容</span>
        </el-col>
      </el-form-item>
      <el-form-item label="" prop="input">
        <el-col :span="10">
          <el-input v-model="form.input" :rows="20" type="textarea" />
        </el-col>
        <el-col :span="2">
          <span>&nbsp;在线工具输出</span><br><br>&nbsp;
          <el-button type="primary" @click="copyInput">复制输入</el-button>
          <br><br>&nbsp;
          <el-button type="primary" @click="copyOutput">复制输出</el-button>
          <br><br>&nbsp;
          <el-button type="warning" @click="resetForm('form')">&nbsp;&nbsp;&nbsp;清&nbsp;空&nbsp;&nbsp;&nbsp;</el-button>
        </el-col>
        <el-col :span="11">
          <el-input v-model="output" :rows="20" type="textarea" />
        </el-col>
      </el-form-item>
      <el-form-item>
        <el-col :span="10">&nbsp;</el-col>
        <el-col :span="14">&nbsp;</el-col>
        <el-col :span="0">&nbsp;</el-col>
      </el-form-item>
    </el-form>
    <el-row>
      <el-col :span="4">&nbsp;</el-col>
      <el-col :span="2">
        <el-button @click="onSubmit('form', 'hex_to_string')">16进制转中文</el-button>
      </el-col>
      <el-col :span="2">
        <el-button @click="onSubmit('form', 'string_to_hex')">中文转16进制</el-button>
      </el-col>
      <el-col :span="2">
        <el-button @click="onSubmit('form', 'openid_secret')">OpenId转秘钥</el-button>
      </el-col>
      <el-col :span="2">
        <el-button @click="onSubmit('form', 'base64_encode')">Base64 Encode</el-button>
      </el-col>
      <el-col :span="2">
        <el-button @click="onSubmit('form', 'base64_decode')">Base64 Decode</el-button>
      </el-col>
      <el-col :span="2">
        <el-button @click="onSubmit('form', 'url_encode')">Url Encode</el-button>
      </el-col>
      <el-col :span="2">
        <el-button @click="onSubmit('form', 'url_decode')">Url Decode</el-button>
      </el-col>
    </el-row>
    <Footer />
  </div>
</template>

<script>
import request from '@/utils/request'
import { Footer } from '../../layout/components'

export default {
  name: 'Lines',
  components: {
    Footer
  },
  data() {
    return {
      form: {
        input: '',
        output: ''
      },
      convert: '',
      rules: {
        input: [
          { required: true, message: '请输入内容', trigger: 'blur' }
        ]
      },
      loading: false,
      isShow: false,
      output: ''
    }
  },
  created() {},
  methods: {
    copyInput() {
      const that = this
      that.$copyText(this.form.input).then(function(e) {
        that.$message({
          message: '输入内容复制成功',
          type: 'success'
        })
      }, function(e) {
        this.$message({
          message: '复制失败',
          type: 'error'
        })
        console.log(e)
      })
    },
    copyOutput() {
      const that = this
      this.$copyText(this.output).then(function(e) {
        that.$message({
          message: '输出内容复制成功',
          type: 'success'
        })
        // console.log(e)
      }, function(e) {
        this.$message({
          message: '复制失败',
          type: 'error'
        })
        console.log(e)
      })
    },
    onSubmit(form, func) {
      this.$refs[form].validate((valid) => {
        if (valid) {
          this.loading = true
          const uri = '/api/tool'
          request.post(uri, { operation: func, input: this.form.input }).then(res => {
            // this.loading = false
            // console.log(res.data)
            if (res.data.to) {
              this.to = res.data.to
            }
            this.output = res.data.output
          }).catch(err => {
            return err
            // console.log(err)
          })
          setTimeout(() => {
            this.loading = false
          }, 500)
        } else {
          // this.$message('error submit!')
          // console.log('error submit!!')
          return false
        }
      })
      this.convert = ''
    },
    onCancel() {
      this.$message({
        message: 'cancel!',
        type: 'warning'
      })
    },
    resetForm(formName) {
      this.$refs[formName].resetFields()
    }
  }
}
</script>

<style scoped>
  .el-input {
    width: 97%;
    margin-bottom: 3%;
  }

  .input-with-select .el-input-group__prepend {
    background-color: #fff;
  }
</style>
