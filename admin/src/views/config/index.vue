<template>
  <div class="app-container">
    <el-form ref="form" :model="form" :rules="rules" label-width="120px">
      <el-form-item label="名称" prop="name">
        <el-input v-model="form.name" />
      </el-form-item>
      <el-form-item label="开启新老接口" prop="default_open">
        <el-col :span="4">
          <el-input v-model="form.default_open" />
        </el-col>
        <el-col :span="20">&nbsp;开启公交配置情况--【1: 老接口，2: 新接口，3: 先老接口再新接口，4: 先新接口后老接口】</el-col>
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
import { postAdd, getList } from '@/api/config'

export default {
  data() {
    return {
      form: {
        name: '',
        default_open: 1,
        state: true,
        loading: false
      },
      rules: {
        name: [
          { required: true, message: '请输入名称', trigger: 'blur' }
        ],
        default_open: [
          { required: true, message: '请输入开启公交配置情况', trigger: 'blur' }
        ]
      },
      redirect: '/config/index'
    }
  },
  created() {
    this.init()
  },
  methods: {
    init() {
      const that = this
      getList({ perPage: 20 }).then(response => {
        const data = response.data
        that.form.name = data.name
        that.form.default_open = data.default_open
      })
    },
    onSubmit(form) {
      // console.log(this.form)
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
              // this.$router.push({ path: this.redirect || '/' })
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

