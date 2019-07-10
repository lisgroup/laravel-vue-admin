<template>
  <div class="app-container">
    <el-form ref="form" :model="form" :rules="rules" label-width="220px">
      <el-form-item label="角色名称" prop="name">
        <el-col :span="10">
          <el-input v-model="form.name" />
        </el-col>
        <el-col :span="14" />
      </el-form-item>

      <el-form-item label="新增角色赋值权限" prop="roles">
        <template>
          <el-checkbox v-model="checkAll" @change="CheckAll">全选</el-checkbox>
          <div style="margin: 15px 0;" />
          <el-checkbox-group v-model="form.checkedPermissions" @change="PerChange">
            <el-checkbox v-for="permission in form.permissions" :key="permission.id" :label="permission.id">{{ permission.name }}</el-checkbox>
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
import { postEdit, edit } from '@/api/role'

export default {
  data() {
    return {
      checkAll: false,
      isIndeterminate: true,

      form: {
        name: '',
        checkedPermissions: [],
        permissions: [],
        loading: false
      },
      rules: {
        name: [
          { required: true, message: '请输入名称', trigger: 'blur' }
        ]
      },
      redirect: '/role'
    }
  },
  created() {
    this.id = this.$route.params.id
    this.getData(this.id)
    // this.fetchData()
  },
  methods: {
    getData(id) {
      edit(id).then(response => {
        this.loading = false
        if (response.code === 200) {
          this.form.name = response.data.role.name
          this.form.permissions = response.data.permissions
          this.form.checkedPermissions = response.data.checkedPermissions

          // 是否全选中
          if (this.form.permissions.length === this.form.checkedPermissions.length) {
            this.checkAll = true
          }
        } else {
          this.$message.error(response.reason)
        }
      })
    },
    CheckAll(val) {
      this.form.checkedPermissions = []
      if (val) {
        for (let i = 0, len = this.form.permissions.length; i < len; i++) {
          this.form.checkedPermissions.push(this.form.permissions[i].id)
        }
      }

      this.isIndeterminate = false
    },
    PerChange(value) {
      const checkedCount = value.length
      this.isIndeterminate = checkedCount > 0 && checkedCount < this.form.permissions.length
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
