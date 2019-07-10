<template>
  <div class="app-container">
    <el-form ref="form" :model="form" :rules="rules" label-width="220px">
      <el-form-item label="用户名称" prop="name">
        <el-col :span="10">
          <el-input v-model="form.name" />
        </el-col>
        <el-col :span="14" />
      </el-form-item>

      <el-form-item label="用户邮箱" prop="email">
        <el-col :span="10">
          <el-input v-model="form.email" />
        </el-col>
        <el-col :span="14" />
      </el-form-item>

      <el-form-item label="密码" prop="password">
        <el-col :span="10">
          <el-input v-model="form.password" type="password" />
        </el-col>
        <el-col :span="14" />
      </el-form-item>

      <el-form-item label="确认密码" prop="checkPass">
        <el-col :span="10">
          <el-input v-model="form.checkPass" type="password" />
        </el-col>
        <el-col :span="14" />
      </el-form-item>

      <el-form-item label="赋值角色" prop="roles">
        <template>
          <el-checkbox-group v-model="form.checkedRoles" @change="handleCheckedRolesChange">
            <el-checkbox v-for="role in form.roles" :key="role.id" :label="role.id">{{ role.name }}</el-checkbox>
          </el-checkbox-group>
        </template>
      </el-form-item>

      <el-form-item>
        <el-button type="primary" @click="onSubmit('form')">提交</el-button>
        <el-button @click="resetForm('form')">重置</el-button>
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
import { getRole, edit, postEdit } from '@/api/user'

export default {
  data() {
    const validatePass = (rule, value, callback) => {
      if (this.form.password !== '') {
        this.$refs.form.validateField('checkPass')
      }
      callback()
    }
    const validatePass2 = (rule, value, callback) => {
      if (value === undefined) {
        value = ''
      }
      // console.log(this.form.password)
      if (value !== this.form.password) {
        callback(new Error('两次输入密码不一致!'))
      } else {
        callback()
      }
    }
    return {
      checkAll: false,
      form: {
        name: '',
        email: '',
        password: '',
        checkedRoles: [],
        roles: [],
        isIndeterminate: true,
        loading: false
      },
      rules: {
        name: [
          { required: true, message: '请输入名称', trigger: 'blur' }
        ],
        password: [
          { validator: validatePass, trigger: 'blur' }
        ],
        checkPass: [
          { validator: validatePass2, trigger: 'blur' }
        ]
      },
      redirect: '/user'
    }
  },
  created() {
    // this.fetchData()
    this.id = this.$route.params.id
    this.getData(this.id)
  },
  methods: {
    getData(id) {
      edit(id).then(response => {
        this.loading = false
        if (response.code === 200) {
          this.form.name = response.data.user.name
          this.form.email = response.data.user.email
          this.form.roles = response.data.roles
          this.form.checkedRoles = response.data.checkedRoles

          // 是否全选中
          if (this.form.roles.length === this.form.checkedRoles.length) {
            this.checkAll = true
          }
        } else {
          this.$message.error(response.reason)
        }
      })
    },
    fetchData() {
      this.listLoading = true
      getRole().then(response => {
        this.form.roles = response.data.roles
        this.listLoading = false
      })
    },
    handleCheckedRolesChange(value) {
      const checkedCount = value.length
      this.isIndeterminate = checkedCount > 0 && checkedCount < this.form.roles.length
    },
    onSubmit(form) {
      // console.log(this.form)
      this.$refs[form].validate((valid) => {
        if (valid) {
          this.loading = true
          postEdit(this.id, this.form).then(response => {
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
          // this.$message('error submit!')
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
