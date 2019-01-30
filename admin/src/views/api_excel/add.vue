<template>
  <div class="app-container">
    <el-form ref="form" :model="form" :rules="rules" label-width="120px">
      <el-form-item label="上传文件" prop="upload_url">
        <input v-model="form.upload_url" type="text">
        <el-upload
          :action="uploadUrl"
          :on-preview="handlePreview"
          :on-remove="handleRemove"
          :on-success="handleSuccess"
          :before-remove="beforeRemove"
          :on-exceed="handleExceed"
          :file-list="fileList"
          multiple
          class="upload-demo">
          <el-button size="small" type="primary">点击上传</el-button>
          <div slot="tip" class="el-upload__tip">只能上传 xls/xlsx 文件，且不超过 20M</div>
        </el-upload>
      </el-form-item>
      <el-form-item label="用户ID" prop="uid">
        <el-input v-model="form.uid"/>
      </el-form-item>
      <el-form-item label="描述内容" prop="description">
        <el-input v-model="form.description"/>
      </el-form-item>
      <el-form-item>
        <el-button type="primary" @click="onSubmit('form')">提交</el-button>
        <el-button @click="resetForm('form')">重置</el-button>
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
import { postAdd } from '@/api/api_excel'
import { getToken } from '@/utils/auth'

export default {
  data() {
    return {
      // 请求需要携带 token
      uploadUrl: process.env.BASE_API + '/api/upload?token=' + getToken(),
      fileList: [],
      form: {
        upload_url: '',
        uid: '',
        description: '',
        sort: '',
        loading: false
      },
      rules: {
        upload_url: [
          { required: true, message: '请上传文件', trigger: 'blur' }
        ],
        description: [
          { required: true, message: '请输入描述', trigger: 'blur' }
        ]
      },
      redirect: '/api_excel/index'
    }
  },
  methods: {
    handleRemove(file, fileList) {
      console.log(file, fileList)
    },
    handlePreview(file) {
      console.log(file)
    },
    handleExceed(files, fileList) {
      this.$message.warning(`当前限制选择 3 个文件，本次选择了 ${files.length} 个文件，共选择了 ${files.length + fileList.length} 个文件`)
    },
    beforeRemove(file, fileList) {
      return this.$confirm(`确定移除 ${file.name}？`)
    },
    handleSuccess(response, file, fileList) {
      console.log(response)
      this.form.upload_url = response.data.url
    },
    onSubmit(form) {
      console.log(this.form)
      this.$refs[form].validate((valid) => {
        if (valid) {
          this.loading = true
          postAdd(this.form).then(response => {
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

