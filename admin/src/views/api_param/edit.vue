<template>
  <div class="app-container">
    <el-form ref="form" :model="form" :rules="rules" label-width="120px">
      <el-form-item label="接口名称" prop="name">
        <el-input v-model="form.name" />
      </el-form-item>
      <el-form-item label="接口地址" prop="url">
        <el-input v-model="form.url" />
      </el-form-item>
      <el-form-item label="接口参数" prop="param">
        <el-input v-model="form.param" />
        <span>(多个参数请用英文 , 分割；如： realname,mobile,idcard)</span>
      </el-form-item>
      <el-form-item label="结果集 result" prop="result">
        <el-input v-model="form.result" />
        <span>(多个参数请用英文 , 分割；如： res,msg)</span>
      </el-form-item>
      <el-form-item label="是否处理" prop="is_need">
        <el-switch v-model="form.is_need" />
      </el-form-item>
      <el-form-item label="网址" prop="website">
        <el-input v-model="form.website" />
      </el-form-item>
      <el-form-item label="请求方式" prop="method">
        <!--<el-input v-model="form.method"/>-->
        <el-select v-model="form.method" placeholder="请选择接口" value-key="name">
          <el-option key="1" label="get" value="get">
            <span style="float: left; color: #8492a6; font-size: 13px">get</span>
          </el-option>
          <el-option key="2" label="post" value="post">
            <span style="float: left; color: #8492a6; font-size: 13px">post</span>
          </el-option>
        </el-select>
      </el-form-item>
      <el-form-item label="是否启用">
        <el-switch v-model="form.state" />
      </el-form-item>
      <el-form-item>
        <el-button type="primary" @click="onSubmit('form')">提交</el-button>
        <el-button @click="resetForm('form')">重置</el-button>
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
import { edit, postEdit } from '@/api/api_param'

export default {
  data() {
    return {
      form: {
        name: '',
        url: '',
        param: '',
        result: '',
        is_need: false,
        state: true,
        website: '',
        method: 'get',
        loading: false
      },
      rules: {
        name: [
          { required: true, message: '请输入名称', trigger: 'blur' }
        ],
        url: [
          { required: true, message: '请输入接口地址', trigger: 'blur' }
        ],
        param: [
          { required: true, message: '请输入接口参数', trigger: 'blur' }
        ],
        result: [
          { required: true, message: '请输入结果集 result', trigger: 'blur' }
        ],
        is_need: [
          { required: true, message: '请选择是否处理', trigger: 'blur' }
        ]
      },
      redirect: '/api_param/index'
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
          this.form.is_need = (response.data.is_need === 1)
          this.form.state = (response.data.state === 1)
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

