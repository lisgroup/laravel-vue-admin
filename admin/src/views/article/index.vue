<template>
  <div class="app-container">
    <el-row>
      <el-button type="primary" size="medium">
        <router-link to="/article/add">新增</router-link>
      </el-button>
    </el-row>

    <el-form ref="form" :model="form" :rules="rules" label-width="120px">
      <el-form-item label="输入文章名称" prop="input">
        <el-input v-model="form.input" placeholder="文章名称" @keyup.enter.native="goSearch('form')">
          <el-button slot="append" icon="el-icon-search" @click="goSearch('form')">搜索</el-button>
        </el-input>
      </el-form-item>
    </el-form>
    <el-table
      v-loading="listLoading"
      :data="list"
      element-loading-text="Loading"
      border
      fit
      highlight-current-row
    >
      <el-table-column align="center" label="ID" width="70">
        <template slot-scope="scope">
          {{ scope.row.id }}
        </template>
      </el-table-column>
      <el-table-column label="标题">
        <template slot-scope="scope">
          {{ scope.row.title }}
        </template>
      </el-table-column>
      <el-table-column label="作者" width="110" align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.author }}</span>
        </template>
      </el-table-column>
      <el-table-column label="关键词">
        <template slot-scope="scope">
          {{ scope.row.keywords }}
        </template>
      </el-table-column>
      <el-table-column label="点击次数" width="110" align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.click }}</span>
        </template>
      </el-table-column>
      <el-table-column align="center" prop="created_at" label="创建时间" width="200">
        <template slot-scope="scope">
          <i class="el-icon-time" />
          <span>{{ scope.row.created_at }}</span>
        </template>
      </el-table-column>

      <el-table-column label="操作">
        <template slot-scope="scope">
          <el-button
            size="mini"
            @click="handleEdit(scope.$index, scope.row)"
          >编辑</el-button>
          <el-button
            size="mini"
            type="danger"
            @click="handleDelete(scope.$index, scope.row)"
          >删除</el-button>
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
        @current-change="handleCurrentChange"
      />
    </div>
  </div>
</template>

<script>
import { getList, deleteAct, search } from '@/api/article'

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
      total: 1000,
      currentpage: 1,
      listQuery: { page: 1 },
      form: {
        input: ''
      },
      rules: {
        input: [
          { required: true, message: '请输入线路名称', trigger: 'blur' }
        ]
      }
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
    fetchData() {
      this.listLoading = true
      const params = Object.assign({ 'page': this.listQuery.page }, { 'perPage': this.perpage })
      getList(params).then(response => {
        this.list = response.data.data
        this.listLoading = false
        this.total = response.data.total
      })
    },
    handleEdit(index, row) {
      this.$router.push({ path: '/article/edit/' + row.id })
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
