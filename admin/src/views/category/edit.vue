<template>
  <div class="app-container">
    <el-form ref="form" :model="form" :rules="rules" label-width="120px">
      <el-form-item label="栏目名称" prop="name">
        <el-input v-model="form.name" />
      </el-form-item>
      <el-form-item label="关键词" prop="keywords">
        <el-input v-model="form.keywords" />
      </el-form-item>
      <el-form-item label="描述" prop="description">
        <el-input v-model="form.description" />
      </el-form-item>
      <el-form-item label="排序" prop="sort">
        <el-col :span="6">
          <el-input v-model="form.sort" />
        </el-col>
        取值范围 【-128 到 128】 的整数，数值越小越靠前
        <el-col :span="18" />
      </el-form-item>
      <el-form-item>
        <el-button type="primary" @click="onSubmit('form')">提交</el-button>
        <el-button @click="resetForm('form')">重置</el-button>
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
import { edit, postEdit } from '@/api/category'

export default {
  data() {
    return {
      form: {
        name: '',
        keywords: '',
        description: '',
        sort: '',
        loading: false
      },
      rules: {
        name: [
          { required: true, message: '请输入名称', trigger: 'blur' }
        ],
        keywords: [
          { required: true, message: '请输入关键词', trigger: 'blur' }
        ],
        description: [
          { required: true, message: '请输入描述', trigger: 'blur' }
        ]
      },
      redirect: '/category'
    }
  },
  created() {
    this.id = this.$route.params.id
    this.getData(this.id)
  },
  methods: {
    getData(id) {
      // this.id = this.$route.params.id
      edit(id).then(response => {
        // console.log(response)
        this.loading = false
        if (response.code === 200) {
          this.form = response.data
          this.form.is_task = (response.data.is_task === 1)
        } else {
          this.$message.error(response.reason)
        }
      })
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

