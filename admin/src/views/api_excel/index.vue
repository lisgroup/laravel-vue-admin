<template>
  <div class="app-container">
    <el-row>
      <el-button type="primary" size="medium">
        <router-link to="/api_excel/add">上传测试</router-link>
      </el-button>
      <el-button :loading="reload" type="primary" class="reload" plain @click="fetchData">{{ reload_name }}</el-button>
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
      <el-table-column label="接口名称" align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.api_param.name }}</span>
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
            <el-tag type="warning">未开启</el-tag>
          </div>
          <div v-else-if="scope.row.state === 1">
            <el-tag type="primary">正在处理</el-tag>
          </div>
          <div v-else-if="scope.row.state === 2">
            <el-tag type="success">已完成</el-tag>
          </div>
          <div v-else>
            <el-tag type="info">失败</el-tag>
          </div>
        </template>
      </el-table-column>

      <el-table-column label="进度条" width="100" align="center" display="none">
        <template slot-scope="scope">
          <div v-if="scope.row.state === 0">
            <el-progress :text-inside="true" :stroke-width="18" :percentage="0"/>
          </div>
          <div v-else-if="scope.row.state === 1">
            <el-progress :text-inside="true" :stroke-width="18" :percentage="scope.row.rate"/>
          </div>
          <div v-else-if="scope.row.state === 2">
            <el-progress :text-inside="true" :stroke-width="18" :percentage="100" status="success"/>
          </div>
          <div v-else>
            <el-progress :text-inside="true" :stroke-width="18" :percentage="50" status="exception"/>
          </div>
        </template>
      </el-table-column>

      <el-table-column label="操作" width="300" align="center">
        <template slot-scope="scope">
          <div>
            <el-button
              v-if="scope.row.state === 0"
              size="mini"
              type="warning"
              @click="openTask(scope.$index, scope.row)">点击开启任务</el-button>
            <el-button
              v-else-if="scope.row.state === 1"
              size="mini"
              type="primary">...</el-button>
            <el-button
              v-else-if="scope.row.state === 2"
              size="mini"
              type="success"
              @click="download(scope.$index, scope.row)">点击下载</el-button>
            <el-button
              v-else-if="scope.row.state === 5"
              size="mini"
              type="info"
              @click="download_log(scope.$index, scope.row)">下载已测试数据</el-button>

            <el-button
              size="mini"
              type="danger"
              @click="handleDelete(scope.$index, scope.row)">删除</el-button>
          </div>
          <!--<el-button
            size="mini"
            @click="handleEdit(scope.$index, scope.row)">编辑</el-button>-->
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
import { getToken } from '@/utils/auth'
import { getList, deleteAct, search, startTask, download_log } from '@/api/api_excel'

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
      reload: false,
      reload_name: '点击刷新',
      list: null,
      listLoading: true,
      perpage: 10,
      total: 100,
      currentpage: 1,
      listQuery: { page: 1 },
      url: null,
      websock: null
    }
  },
  created() {
    this.listQuery = this.$route.query
    this.currentpage = parseInt(this.listQuery.page)
    const perPage = parseInt(this.$route.query.perPage)
    this.perpage = isNaN(perPage) ? this.perpage : perPage
    // this.fetchData()
    this.initWebSocket()
  },
  destroyed() {
    this.websock.close() // 离开路由之后断开 websocket 连接
  },
  methods: {
    startTask(index, row) {
      this.$confirm('此操作将开启任务, 是否继续?', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning'
      }).then(() => {
        startTask(row).then(res => {
          // console.log(res)
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
    openTask(index, row) {
      this.$alert('此操作将开启任务, 是否继续?', '开启任务提醒', {
        confirmButtonText: '确定',
        center: true,
        type: 'warning',
        callback: action => {
          if (action === 'confirm') {
            startTask(row).then(res => {
              // console.log(res)
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
          }
        }
      })
    },
    initWebSocket() { // 初始化 weosocket
      if ('WebSocket' in window) {
        // const url = 'wss://www.guke1.com/ws?action=api_excel&token=' + getToken()
        const url = 'ws://localhost:5200?action=api_excel&token=' + getToken()
        this.websock = new WebSocket(url)
        this.websock.onmessage = this.onmessage
        this.websock.onopen = this.onopen
        this.websock.onerror = this.onerror
        this.websock.onclose = this.close
      } else {
        // 浏览器不支持 WebSocket，使用 ajax 轮询
        console.log('Your browser does not support WebSocket!')
      }
    },
    onopen() { // 连接建立之后执行send方法发送数据
      // const actions = { 'id': '7' }
      // const rs = this.send(JSON.stringify(actions))
      // console.log(rs)
    },
    onerror() {
      // 连接建立失败, 发送 http 请求获取数据
      // this.initWebSocket()
      this.fetchData()
    },
    onmessage(e) { // 数据接收
      // console.log(e.data)
      const data = JSON.parse(e.data)
      // this.list[2].rate = parseInt(data.data.rate)
      // console.log(this.list[2].rate)
      console.log(data)
      // websocket 返回的数据
      this.list = data.data.data
      this.listLoading = false
      this.total = data.data.total
      this.url = data.data.appUrl
      // console.log('type', Object.prototype.toString.call(this.list))
      setTimeout(() => {
        this.reload = false
        this.reload_name = '刷新'
      }, 800)
    },
    send(Data) {
      this.websock.send(Data)
    },
    close() { // 关闭
      console.log('断开连接')
    },
    download(index, row) {
      window.location.href = this.url + row.finish_url
    },
    download_log(index, row) {
      download_log({ id: row.id }).then(res => {
        console.log(res)
        if (res.code === 200) {
          window.location.href = this.url + res.data.failed_done_file
        }
      })
    },
    fetchData() {
      this.listLoading = this.reload = true
      this.reload_name = '加载中'
      const params = Object.assign({ 'page': this.listQuery.page }, { 'perPage': this.perpage })
      getList(params).then(response => {
        this.list = response.data.data
        this.listLoading = false
        this.total = response.data.total
        this.url = response.data.appUrl
        // console.log('type', Object.prototype.toString.call(this.list))
        setTimeout(() => {
          this.reload = false
          this.reload_name = '刷新'
        }, 800)
        // this.initWebSocket()
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
        type: 'error'
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
  .reload {
    margin-right: 300px;
    float: right;
  }
</style>
