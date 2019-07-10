<template>
  <div class="app-container">
    <el-form ref="form" :model="form" :rules="rules" label-width="220px">
      <el-form-item label="权限名称" prop="name">
        <el-col :span="10">
          <el-input v-model="form.name" />
        </el-col>
        <el-col :span="14" />
        <br>示例：create-article, update-article, delete-article, show-article
      </el-form-item>

      <el-form-item label="路由" prop="route">
        <el-col :span="10">
          <el-input v-model="form.route" />
        </el-col>
        <el-col :span="14" />
      </el-form-item>

      <el-form-item label="新增权限赋值角色" prop="roles">
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

import { postAdd, getRole } from '@/api/permission'

export default {
  data() {
    return {
      form: {
        name: '',
        route: '',
        checkedRoles: [],
        roles: [],
        isIndeterminate: true,
        loading: false
      },
      rules: {
        name: [
          { required: true, message: '请输入名称', trigger: 'blur' }
        ]
      },
      redirect: '/permission'
    }
  },
  created() {
    this.fetchData()
  },
  methods: {
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
      this.$refs[form].validate((valid) => {
        if (valid) {
          this.loading = true
          postAdd(this.form).then(response => {
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
