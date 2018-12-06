<template>
  <div class="app-container">
    <el-form ref="form" :model="form" :rules="rules" label-width="120px">
      <el-form-item label="车次名称" prop="name">
        <el-input v-model="form.name"/>
      </el-form-item>
      <el-form-item label="price" prop="price">
        <el-input v-model="form.price"/>
      </el-form-item>
      <el-form-item label="类型" prop="car_type">
        <el-select v-model="form.car_type" placeholder="请选择类型">
          <el-option label="大巴" value="大巴"/>
          <el-option label="中巴" value="中巴"/>
          <el-option label="地铁" value="地铁"/>
        </el-select>
      </el-form-item>
      <el-form-item label="发车间隔" prop="depart_time">
        <el-input v-model="form.depart_time"/>
      </el-form-item>
      <el-form-item label="营运时间" prop="open_time">
        <el-input v-model="form.open_time"/>
      </el-form-item>
      <el-form-item label="全程时间" prop="total_time">
        <el-input v-model="form.total_time"/>
      </el-form-item>
      <el-form-item label="途经道路" prop="via_road">
        <el-input v-model="form.via_road" type="textarea"/>
      </el-form-item>
      <el-form-item label="公交公司" prop="company">
        <el-input v-model="form.company"/>
      </el-form-item>
      <el-form-item label="途经站点(去程)" prop="station">
        <el-input :rows="4" v-model="form.station" type="textarea" />
      </el-form-item>
      <el-form-item label="途经站点(返程)" prop="station_back">
        <el-input :rows="4" v-model="form.station_back" type="textarea" />
      </el-form-item>
      <el-form-item label="编辑原因" prop="reason">
        <el-input v-model="form.reason" />
      </el-form-item>
      <el-form-item label="最后更新时间" prop="last_update">
        <el-input v-model="form.last_update" />
      </el-form-item>
      <el-form-item label="是否已审核">
        <!--<el-switch v-model="form.is_show"/>-->
        <el-radio-group v-model="form.is_show">
          <el-radio :label="0">未审核</el-radio>
          <el-radio :label="1">通过</el-radio>
          <el-radio :label="2">不通过</el-radio>
        </el-radio-group>
      </el-form-item>
      <el-form-item>
        <el-button type="primary" @click="onSubmit('form')">提交</el-button>
        <el-button @click="resetForm('form')">重置</el-button>
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
import { postNewBus } from '@/api/table'

export default {
  data() {
    return {
      form: {
        name: '',
        price: '',
        car_type: '大巴',
        depart_time: '',
        open_time: '',
        total_time: '',
        via_road: '',
        company: '',
        station: '',
        station_back: '',
        reason: '',
        last_update: '',
        is_show: 0,
        loading: false
      },
      rules: {
        name: [
          { required: true, message: '请输入线路名称', trigger: 'blur' }
        ],
        price: [
          { required: true, message: '请输入 price', trigger: 'blur' }
        ],
        car_type: [
          { required: true, message: '请输入 car_type', trigger: 'blur' }
        ],
        station: [
          { required: true, message: '请输入途经站点', trigger: 'blur' }
        ],
        start_at: [
          { required: true, message: '请输入起始时间', trigger: 'change' }
        ],
        end_at: [
          { required: true, message: '请输入结束时间', trigger: 'change' }
        ]
      },
      redirect: '/list/lines'
    }
  },
  methods: {
    onSubmit(form) {
      // console.log(this.form)
      this.$refs[form].validate((valid) => {
        if (valid) {
          this.loading = true
          postNewBus(this.form).then(response => {
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

