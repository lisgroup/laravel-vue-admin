<template>
  <div class="app-container">
    <el-form ref="form" :model="form" :rules="rules" label-width="220px">
      <el-form-item label="权限名称" prop="name">
        <el-col :span="10">
          <el-input v-model="form.name" />
        </el-col>
        <el-col :span="14">
          如： create-article, update-article, delete-article, show-article
        </el-col>
      </el-form-item>
      <el-form-item label="路由" prop="route">
        <el-col :span="10">
          <el-input v-model="form.route" />
        </el-col>
        <el-col :span="14" />
      </el-form-item>

      <el-form-item>
        <el-button type="primary" @click="onSubmit('form')">提交</el-button>
        <el-button @click="resetForm('form')">重置</el-button>
      </el-form-item>
    </el-form>
  </div>
</template>

<script>

import { postEdit, edit } from '@/api/permission'

export default {
  data() {
    return {
      form: {
        name: '',
        route: '',
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
    this.id = this.$route.params.id
    this.getData(this.id)
  },
  methods: {
    getData(id) {
      edit(id).then(response => {
        this.loading = false
        if (response.code === 200) {
          this.form = response.data
        } else {
          this.$message.error(response.reason)
        }
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
