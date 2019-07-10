<template>
  <div class="app-container">
    <el-form ref="form" :model="form" :rules="rules" label-width="120px">
      <el-form-item label="旧密码" prop="old_pwd" width="200">
        <el-input v-model="form.old_pwd" type="password" />
      </el-form-item>
      <el-form-item label="新密码" prop="password" width="200">
        <el-input v-model="form.password" type="password" />
      </el-form-item>

      <el-form-item label="再次输入" prop="repassword" width="200">
        <el-input v-model="form.repassword" type="password" />
      </el-form-item>

      <el-form-item>
        <el-button type="primary" @click="onSubmit('form')">提交</el-button>
        <el-button @click="resetForm('form')">重置</el-button>
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
import { postEditPassword } from '@/api/user'

export default {
  data() {
    return {
      form: {
        old_pwd: '',
        password: '',
        repassword: ''
      },
      rules: {
        old_pwd: [
          { required: true, message: '请输入旧密码', trigger: 'blur' }
        ],
        password: [
          { required: true, message: '请输入新密码', trigger: 'blur' }
        ],
        repassword: [
          { required: true, message: '请再次输入新密码', trigger: 'blur' }
        ]
      },
      redirect: '/admin'
    }
  },
  created() {},
  methods: {
    onSubmit(form) {
      // console.log(this.form)
      this.$refs[form].validate((valid) => {
        if (valid) {
          this.loading = true
          postEditPassword(this.form).then(response => {
            // console.log(response)
            this.loading = false
            if (response.code === 200) {
              this.$message({
                message: '操作成功',
                type: 'success'
              })
              this.$router.push({ path: this.redirect || '/' })
            } else {
              this.$message.error(response.reason)
            }
          })
        } else {
          this.$message('error submit!')
          // console.log('error submit!!')
          return false
        }
      })
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
  .line {
    text-align: center;
  }
</style>

