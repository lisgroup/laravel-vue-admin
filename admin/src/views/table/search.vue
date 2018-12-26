<template>
  <div class="app-container">
    <el-form ref="form" :model="form" :rules="rules" label-width="120px">
      <el-form-item label="输入车次名称" prop="input">
        <el-input v-model="form.input" placeholder="线路名称，例：快线1, 55" @keyup.enter.native="goSearch('form')">
          <template slot="prepend">线路</template>
          <el-button slot="append" icon="el-icon-search" @click="goSearch('form')">搜索</el-button>
        </el-input>
      </el-form-item>
    </el-form>

    <el-table
      v-loading="listLoading"
      v-show="form.isShow"
      :data="list"
      element-loading-text="Loading"
      border
      fit
      highlight-current-row>
      <el-table-column align="center" label="ID" width="70">
        <template slot-scope="scope">
          {{ scope.row.id }}
        </template>
      </el-table-column>
      <el-table-column label="车次信息">
        <template slot-scope="scope">
          {{ scope.row.name }}
        </template>
      </el-table-column>
      <el-table-column label="price" width="110" align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.price }}</span>
        </template>
      </el-table-column>
      <el-table-column label="类型" width="110" align="center">
        <template slot-scope="scope">
          {{ scope.row.car_type }}
        </template>
      </el-table-column>
      <el-table-column class-name="status-col" label="审核通过" width="110" align="center">
        <template slot-scope="scope">
          <el-tag v-if="scope.row.is_show === 0">未审核</el-tag>
          <el-tag v-else-if="scope.row.is_show === 1" type="success">通过</el-tag>
          <el-tag v-else type="warning">不通过</el-tag>
        </template>
      </el-table-column>
      <el-table-column align="center" prop="created_at" label="创建时间" width="200">
        <template slot-scope="scope">
          <i class="el-icon-time"/>
          <span>{{ scope.row.created_at }}</span>
        </template>
      </el-table-column>

      <el-table-column label="操作">
        <template slot-scope="scope">
          <el-button
            size="mini"
            @click="handleEdit(scope.$index, scope.row)">编辑</el-button>
          <el-button
            size="mini"
            type="danger"
            @click="handleDelete(scope.$index, scope.row)">删除</el-button>
        </template>
      </el-table-column>
    </el-table>

    <el-table v-show="form.isShow">
      <el-button type="primary" @click="onSubmit('form')">提交</el-button>
      <el-button @click="resetForm('form')">重置</el-button>
    </el-table>
  </div>
</template>

<script>
import { getLine, postNewBus } from '@/api/table'

export default {
  data() {
    return {
      list: null,
      listLoading: false,
      form: {
        isShow: false,
        input: ''
      },
      rules: {
        input: [
          { required: true, message: '请输入线路名称', trigger: 'blur' }
        ]
      },
      redirect: '/task'
    }
  },
  methods: {
    onSubmit(form) {
      // console.log(this.form)
      this.$refs[form].validate((valid) => {
        if (valid) {
          this.listLoading = true
          postNewBus(this.form).then(response => {
            // console.log(response)
            this.listLoading = false
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
    },
    goSearch(form) {
      this.$refs[form].validate((valid) => {
        if (valid) {
          this.listLoading = true
          const param = { 'wd': this.form.input }
          getLine(param).then(response => {
            // console.log(response)
            this.listLoading = false
            if (response.code === 200) {
              this.form.isShow = true
              console.log(response.data)
              this.list = response.data
              this.listLoading = false
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
          // this.$message('error search!')
          // console.log('error submit!!')
          return false
        }
      })
    }
  }
}
</script>

<style scoped>
  .line {
    text-align: center;
  }
</style>

