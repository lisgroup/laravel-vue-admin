<template>
  <div class="app-container">
    <el-form ref="form" :model="form" label-width="120px">
      <el-form-item label="车次名称">
        <el-input v-model="form.LineInfo"/>
      </el-form-item>
      <el-form-item label="cid">
        <el-input v-model="form.cid"/>
      </el-form-item>

      <el-form-item label="LineGuid">
        <el-input v-model="form.LineGuid"/>
      </el-form-item>

      <el-form-item label="起始时间">
        <el-col :span="11">
          <el-date-picker v-model="form.start_at" type="date" placeholder="Pick a date" style="width: 100%;"/>
        </el-col>
        <el-col :span="2" class="line">-</el-col>
        <el-col :span="11">
          <el-time-picker v-model="form.end_at" type="fixed-time" placeholder="Pick a time" style="width: 100%;"/>
        </el-col>
      </el-form-item>
      <el-form-item label="是否启动">
        <el-switch v-model="form.is_task"/>
      </el-form-item>

      <el-form-item label="Resources">
        <el-radio-group v-model="form.resource">
          <el-radio label="Sponsor"/>
          <el-radio label="Venue"/>
        </el-radio-group>
      </el-form-item>
      <el-form-item label="Activity form">
        <el-input v-model="form.desc" type="textarea"/>
      </el-form-item>
      <el-form-item>
        <el-button type="primary" @click="onSubmit">Create</el-button>
        <el-button @click="onCancel">Cancel</el-button>
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
export default {
  data() {
    return {
      form: {
        LineInfo: '',
        cid: '',
        LineGuid: '',
        start_at: '',
        end_at: '',
        is_task: false,
        resource: '',
        desc: ''
      }
    }
  },
  methods: {
    onSubmit() {
      this.$message('submit!')
    },
    onCancel() {
      this.$message({
        message: 'cancel!',
        type: 'warning'
      })
    }
  }
}
</script>

<style scoped>
.line{
  text-align: center;
}
</style>

