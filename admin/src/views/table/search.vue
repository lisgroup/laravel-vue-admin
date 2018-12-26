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
      ref="multipleTable"
      :data="list"
      element-loading-text="Loading"
      border
      fit
      highlight-current-row
      tooltip-effect="dark"
      style="width: 100%"
      class="currentInfo-table"
      @selection-change="handleSelectionChange">
      <el-table-column type="selection" width="55"/>
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
      <el-table-column label="车次信息">
        <template slot-scope="scope">
          {{ scope.row.name }}
        </template>
      </el-table-column>
      <el-table-column label="班次">
        <template slot-scope="scope">
          {{ scope.row.LineInfo }}
        </template>
      </el-table-column>
      <el-table-column label="线路方向">
        <template slot-scope="scope">
          {{ scope.row.FromTo }}
        </template>
      </el-table-column>
    </el-table>
    <div style="margin-top: 20px">
      <!--<el-button @click="toggleSelection()">取消选择</el-button>-->
      <el-button type="primary" @click="onSubmit('form')">提交</el-button>
      <el-button @click="resetForm('form')">重置</el-button>
    </div>
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
  watch: {
    list: function(val) {
      const para = document.createElement('p')
      const node = document.createTextNode('暂无数据')
      const element = document.getElementsByClassName('currentInfo-table')[0].getElementsByClassName('el-table__body-wrapper')[0]
      para.appendChild(node)
      para.style.height = '100%'
      para.className = 'noData'
      para.style.textAlign = 'center'
      // para.style.paddingTop = document.getElementById('toolboxcard').clientHeight / 2 - 250 + 'px'

      if (val && val.length <= 1) {
        if (!document.getElementsByClassName('currentInfo-table')[0].getElementsByClassName('noData')[0]) {
          element.appendChild(para)
        }
      } else {
        if (document.getElementsByClassName('currentInfo-table')[0].getElementsByClassName('noData')[0]) {
          const pare = document.getElementsByClassName('currentInfo-table')[0].getElementsByClassName('noData')[0]
          element.removeChild(pare)
        }
      }
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
    },
    toggleSelection(rows) {
      if (rows) {
        rows.forEach(row => {
          this.$refs.multipleTable.toggleRowSelection(row)
        })
      } else {
        this.$refs.multipleTable.clearSelection()
      }
    },
    handleSelectionChange(val) {
      this.multipleSelection = val
    }
  }
}
</script>

<style scoped>
  .line {
    text-align: center;
  }
</style>

