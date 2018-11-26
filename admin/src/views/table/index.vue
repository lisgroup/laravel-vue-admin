<template>
  <div class="app-container">
    <el-row>
      <el-button type="primary" size="medium" @click="newBus()">新增车次</el-button>
      <router-link to="/example/newBus">About</router-link>
    </el-row>
    <el-table
      v-loading="listLoading"
      :data="list"
      element-loading-text="Loading"
      border
      fit
      highlight-current-row>
      <el-table-column align="center" label="ID" width="95">
        <template slot-scope="scope">
          <!--{{ scope.$index + 1 }}-->
          {{ scope.row.id }}
        </template>
      </el-table-column>
      <el-table-column label="车次信息">
        <template slot-scope="scope">
          {{ scope.row.LineInfo }}
        </template>
      </el-table-column>
      <el-table-column label="cid" width="110" align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.cid }}</span>
        </template>
      </el-table-column>
      <el-table-column label="LineGuid" width="110" align="center">
        <template slot-scope="scope">
          {{ scope.row.LineGuid }}
        </template>
      </el-table-column>
      <el-table-column class-name="status-col" label="启动状态" width="110" align="center">
        <template slot-scope="scope">
          <el-tag v-if="scope.row.is_task" type="success">启动</el-tag>
          <el-tag v-else type="warning">关闭</el-tag>
        </template>
      </el-table-column>
      <el-table-column align="center" prop="created_at" label="创建时间" width="200">
        <template slot-scope="scope">
          <i class="el-icon-time"/>
          <span>{{ scope.row.created_at }}</span>
        </template>
      </el-table-column>
    </el-table>
  </div>
</template>

<script>
import { getList } from '@/api/table'

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
      listLoading: true
    }
  },
  created() {
    this.fetchData()
  },
  methods: {
    fetchData() {
      this.listLoading = true
      getList(this.listQuery).then(response => {
        this.list = response.data.data
        this.listLoading = false
      })
    },
    newBus() {
      console.log('bus')
    }
  }
}
</script>

<style scoped>
  .el-row {
    margin-bottom: 20px;
  }
</style>
