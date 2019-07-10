<template>
  <div class="app-container">
    <el-form ref="form" :model="form" :rules="rules" label-width="120px">
      <el-form-item label="车次名称" prop="LineInfo">
        <el-input v-model="form.LineInfo" />
      </el-form-item>
      <el-form-item label="cid" prop="cid">
        <el-input v-model="form.cid" />
      </el-form-item>

      <el-form-item label="LineGuid" prop="LineGuid">
        <el-input v-model="form.LineGuid" />
      </el-form-item>

      <el-form-item label="起始时间" prop="start_at">
        <el-col :span="11">
          <el-time-picker v-model="form.start_at" value-format="HH:mm:ss" format="HH:mm:ss" type="fixed-time" placeholder="Pick a time" style="width: 100%;" />
        </el-col>
        <el-col :span="2" class="line">-</el-col>
        <el-col :span="11">
          <el-time-picker v-model="form.end_at" value-format="HH:mm:ss" format="HH:mm:ss" type="fixed-time" placeholder="Pick a time" style="width: 100%;" />
        </el-col>
      </el-form-item>
      <el-form-item label="是否启动">
        <el-switch v-model="form.is_task" />
      </el-form-item>
      <el-form-item>
        <el-button type="primary" @click="onSubmit('form')">提交</el-button>
        <el-button @click="resetForm('form')">重置</el-button>
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
import { editBus, postEditBus } from '@/api/task'

export default {
  data() {
    return {
      form: {
        LineInfo: '',
        cid: '',
        LineGuid: '',
        start_at: '05:00:00',
        end_at: '23:00:00',
        is_task: false,
        loading: false,
        id: ''
      },
      rules: {
        LineInfo: [
          { required: true, message: '请输入线路名称', trigger: 'blur' }
        ],
        cid: [
          { required: true, message: '请输入 cid', trigger: 'blur' }
        ],
        LineGuid: [
          { required: true, message: '请输入 LineGuid', trigger: 'blur' }
        ],
        start_at: [
          { required: true, message: '请输入起始时间', trigger: 'change' }
        ],
        end_at: [
          { required: true, message: '请输入结束时间', trigger: 'change' }
        ]
      },
      redirect: '/task'
    }
  },
  created() {
    this.id = this.$route.params.id
    this.getTaskData(this.id)
  },
  methods: {
    getTaskData(id) {
      // this.id = this.$route.params.id
      editBus(id).then(response => {
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
          postEditBus(this.id, this.form).then(response => {
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

