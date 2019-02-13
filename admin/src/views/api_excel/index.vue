<template>
  <div class="app-container">
    <el-row>
      <el-button type="primary" size="medium">
        <router-link to="/api_excel/add">上传测试</router-link>
      </el-button>
    </el-row>
    <el-table
      v-loading="listLoading"
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
      <el-table-column label="描述内容" align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.description }}</span>
        </template>
      </el-table-column>
      <el-table-column label="用户ID" align="center" width="80">
        <template slot-scope="scope">
          {{ scope.row.uid }}
        </template>
      </el-table-column>
      <el-table-column label="原文件">
        <template slot-scope="scope">
          {{ scope.row.upload_url }}
        </template>
      </el-table-column>

      <el-table-column label="状态" width="90" align="center">
        <template slot-scope="scope">
          <div v-if="scope.row.state === 0">
            <el-tag type="danger">未处理</el-tag>
          </div>
          <div v-else-if="scope.row.state === 1">
            <el-tag type="warning">正在处理</el-tag>
          </div>
          <div v-else>
            <el-tag type="success">已完成</el-tag>
          </div>
        </template>
      </el-table-column>

      <el-table-column label="操作" width="200" align="center">
        <template slot-scope="scope">
          <div v-if="scope.row.state === 0">
            <el-button
              size="mini"
              type="danger"
              @click="startTask(scope.$index, scope.row)">点击开始任务</el-button>
          </div>
          <div v-else-if="scope.row.state === 1">
            <el-button
              size="mini"
              type="warning">...</el-button>
          </div>
          <div v-else>
            <el-button
              size="mini"
              type="success"
              @click="download(scope.$index, scope.row)">点击下载</el-button>
          </div>
          <!--<el-button
            size="mini"
            @click="handleEdit(scope.$index, scope.row)">编辑</el-button>
          <el-button
            size="mini"
            type="danger"
            @click="handleDelete(scope.$index, scope.row)">删除</el-button>-->
        </template>
      </el-table-column>

      <el-table-column align="center" prop="created_at" label="创建时间" width="100">
        <template slot-scope="scope">
          <span>{{ scope.row.created_at }}</span>
        </template>
      </el-table-column>
    </el-table>
    <div class="pagination">
      <el-pagination
        :total="total"
        :current-page="currentpage"
        :page-sizes="[10, 20, 30, 50, 100]"
        :page-size="perpage"
        layout="total, sizes, prev, pager, next, jumper"
        @size-change="handleSizeChange"
        @current-change="handleCurrentChange"/>
    </div>
  </div>
</template>

<script>
import { getList, deleteAct, search, startTask } from '@/api/api_excel'

export default {
  filters: {
    statusFilter(status) {
      const statusMap = {
        1: 'success',
        0: 'gray',
        '-1': 'danger'
      }
      return statusMap[status]
    }
  },
  data() {
    return {
      list: null,
      listLoading: true,
      perpage: 10,
      total: 100,
      currentpage: 1,
      listQuery: { page: 1 },
      url: null
    }
  },
  created() {
    this.listQuery = this.$route.query
    this.currentpage = parseInt(this.listQuery.page)
    const perPage = parseInt(this.$route.query.perPage)
    this.perpage = isNaN(perPage) ? this.perpage : perPage
    this.fetchData()
  },
  methods: {
    startTask(index, row) {
      this.$confirm('此操作将开启任务, 是否继续?', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning'
      }).then(() => {
        startTask(row).then(res => {
          console.log(res)
          let msg = ''
          if (res.code === 200) {
            row.state = 1
            msg = 'success'
          } else {
            msg = 'error'
          }
          this.$message({
            type: msg,
            message: res.reason
          })
        })
      }).catch(() => {
        this.$message({
          type: 'info',
          message: '已取消操作'
        })
      })
    },
    download(index, row) {
      window.location.href = this.url + row.finish_url
    },
    fetchData() {
      this.listLoading = true
      const params = Object.assign({ 'page': this.listQuery.page }, { 'perPage': this.perpage })
      getList(params).then(response => {
        this.list = response.data.data
        this.listLoading = false
        this.total = response.data.total
        this.url = response.data.appUrl
      })
    },
    handleEdit(index, row) {
      this.$router.push({ path: '/api_excel/edit/' + row.id })
      // this.$router.push({ name: 'taskEdit', params: { id: row.id }})
      // console.log(index, row)
    },
    handleDelete(index, row) {
      this.$confirm('此操作将永久删除该数据, 是否继续?', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning'
      }).then(() => {
        // 删除操作
        deleteAct(row.id).then(response => {
          // console.log(response)
          this.loading = false
          if (response.code === 200) {
            this.$message({
              message: '操作成功',
              type: 'success'
            })
            this.fetchData()
            // this.$router.push({ path: this.redirect || '/' })
          } else {
            this.$message.error(response.reason)
          }
        })
        this.$message({
          type: 'success',
          message: '删除成功!'
        })
      }).catch(() => {
        this.$message({
          type: 'info',
          message: '已取消删除'
        })
      })
    },
    handleSizeChange(val) {
      this.perpage = val
      this.$router.push({ path: '', query: { page: this.listQuery.page, perPage: val }})
      this.fetchData()
    },
    handleCurrentChange(val) {
      this.listQuery = { page: val }
      this.$router.push({ path: '', query: { page: val, perPage: this.perpage }})
      this.fetchData({ page: val })
    },
    goSearch(form) {
      this.$refs[form].validate((valid) => {
        if (valid) {
          this.listLoading = true
          const param = { 'wd': this.form.input }
          search(param).then(response => {
            // console.log(response)
            this.listLoading = false
            if (response.code === 200) {
              this.form.isShow = true
              // console.log(response.data)
              this.list = response.data.data
              this.total = response.data.total
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
  .el-row {
    margin-bottom: 20px;
  }
  .pagination {
      margin: 20px auto;
  }
</style>
