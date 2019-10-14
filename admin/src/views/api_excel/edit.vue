<template>
  <div class="app-container">
    <el-form ref="form" :model="form" :rules="rules" label-width="120px">
      <el-form-item label="接口" prop="api_excel_id">
        <el-select v-model="item" placeholder="请选择接口" value-key="name">
          <el-option v-for="(cate, index) in apiParam" :key="index" :label="cate.name" :value="cate.id">
            <span style="float: left; color: #8492a6; font-size: 13px">{{ cate.name }}</span>
          </el-option>
        </el-select>
      </el-form-item>

      <el-form-item label="appkey" prop="appkey">
        <el-col :span="11">
          <el-input v-model="form.appkey" />
        </el-col>
        <el-col :span="13" />
      </el-form-item>
      <el-form-item label="上传文件" prop="upload_url">
        <input v-model="form.upload_url" type="hidden">
        <el-upload
          :action="uploadUrl"
          :on-preview="handlePreview"
          :on-remove="handleRemove"
          :on-success="handleSuccess"
          :before-remove="beforeRemove"
          :on-exceed="handleExceed"
          :file-list="fileList"
          multiple
          class="upload-demo"
        >
          <el-button size="small" type="primary">点击上传</el-button>
          <div slot="tip" class="el-upload__tip">只能上传 xls/xlsx 文件，且不超过 20M</div>
        </el-upload>
      </el-form-item>
      <el-form-item label="描述内容" prop="description">
        <el-col :span="11">
          <el-input v-model="form.description" size="medium" placeholder="请输入内容" />
        </el-col>
        <el-col :span="13" />
      </el-form-item>
      <el-form-item label="自动删除时间" prop="auto_delete">
        <el-col :span="2">
          <el-input v-model="form.auto_delete" />
        </el-col>
        <el-col :span="22">
          &nbsp;&nbsp; 任务执行完成后自动删除的时间（单位：天），默认： 2 天
        </el-col>
        <el-col :span="13" />
      </el-form-item>
      <el-form-item>
        <el-button type="primary" @click="onSubmit('form')">提交</el-button>
        <el-button @click="resetForm('form')">重置</el-button>
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
import { getListParam } from '@/api/api_param'
import { postEdit, edit } from '@/api/api_excel'
import { getToken } from '@/utils/auth'

export default {
  data() {
    return {
      // 请求需要携带 token
      uploadUrl: process.env.VUE_APP_BASE_API + '/api/upload?token=' + getToken(),
      fileList: [],
      item: '',
      apiParam: [],
      form: {
        upload_url: '',
        api_excel_id: '',
        appkey: '',
        uid: '',
        description: '',
        auto_delete: 2,
        sort: '',
        loading: false
      },
      rules: {
        api_excel_id: [
          { required: true, message: '请选择接口', trigger: 'blur' }
        ],
        upload_url: [
          { required: true, message: '请上传文件', trigger: 'blur' }
        ],
        appkey: [
          { required: true, message: '请输入 appkey', trigger: 'blur' }
        ],
        description: [
          { required: true, message: '请输入描述', trigger: 'blur' }
        ]
      },
      redirect: '/api_excel/index'
    }
  },
  watch: {
    item(value) {
      this.form.api_excel_id = value
      // console.log(this.form.api_excel_id)
      this.getItem()
    }
  },
  created() {
    this.init()
    this.id = this.$route.params.id
    this.getData(this.id)
  },
  methods: {
    getItem() {
      this.$emit('getItem', this.form.apiParam)
    },
    init() {
      getListParam({ perPage: 100 }).then(response => {
        this.apiParam = response.data.data
      })
    },
    handleRemove(file, fileList) {
      // console.log(file, fileList)
    },
    handlePreview(file) {
      // console.log(file)
    },
    handleExceed(files, fileList) {
      this.$message.warning(`当前限制选择 3 个文件，本次选择了 ${files.length} 个文件，共选择了 ${files.length + fileList.length} 个文件`)
    },
    beforeRemove(file, fileList) {
      return this.$confirm(`确定移除 ${file.name}？`)
    },
    handleSuccess(response, file, fileList) {
      // console.log(response)
      this.form.upload_url = response.data.url
    },
    getData(id) {
      // this.id = this.$route.params.id
      edit(id).then(response => {
        // console.log(response)
        this.loading = false
        if (response.code === 200) {
          this.form = response.data
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

