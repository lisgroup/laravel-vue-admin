<template>
  <div>
    <nav-bar/>

    <el-input v-model="input" placeholder="线路名称，例：快线1, 55" @keyup.enter.native="goSearch">
      <template slot="prepend">线路</template>
      <el-button slot="append" icon="el-icon-search" @click="goSearch">搜索</el-button>
    </el-input>

    <el-table
      v-if="isShow"
      :data="tableData"
      border
      style="width: 100%">
      <el-table-column
        label="线路"
        width="100">
        <template slot-scope="scope">
          <el-button type="text" @click="handleCheck(scope.$index, scope.row.link)">{{ scope.row.bus }}
          </el-button>
        </template>
      </el-table-column>
      <el-table-column label="方向" width="">
        <template slot-scope="scope">
          <el-button type="text" @click="handleCheck(scope.$index, scope.row.link)" v-html="scope.row.FromTo"/>
        </template>
      </el-table-column>
    </el-table>

    <fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;color:green;">
      <legend>{{ to }}&nbsp;<button class="layui-btn layui-btn-normal" @click="handleReload()">刷新</button>
      </legend>
    </fieldset>
    <el-table v-loading="loading" :data="tableLine" border style="width: 100%">
      <el-table-column prop="stationName" label="站台" width=""/>
      <!--<el-table-column-->
      <!--prop="stationCode"-->
      <!--label="编号"-->
      <!--width="100">-->
      <!--</el-table-column>-->
      <el-table-column prop="carCode" label="车牌" width=""/>
      <el-table-column prop="ArrivalTime" label="进站时间" width=""/>
    </el-table>
  </div>
</template>

<script>
import request from '@/utils/request'

export default {
  name: 'Lines',
  data() {
    return {
      loading: false,
      isShow: false,
      input: '',
      to: '',
      tableData: [],
      tableLine: []
    }
  },
  created() {
    this.handleReload()
  },
  methods: {
    handleReload(href) {
      this.loading = true
      const url = '/api/busLine'
      if (!href) {
        // console.log(href)
        href = this.$route.query.href
      }
      // console.log(href)
      const param = 'href=' + href
      request.post(url, param).then(res => {
        // this.loading = false
        // console.log(res.data)
        this.to = res.data.to
        this.tableLine = res.data.line
      }).catch(err => {
        return err
        // console.log(err)
      })
      setTimeout(() => {
        this.loading = false
      }, 500)
    },

    goSearch() {
      const line = this.input
      if (!line) {
        this.$message({
          message: '线路名称不能为空',
          type: 'warning'
        })
        return false
      }
      this.isShow = true
      const url = '/api/getList?linename=' + line
      request.get(url).then(res => {
        this.tableData = res.data
      }).catch(err => {
        return err
        // console.log(err)
      })
    },
    handleCheck(index, link) {
      // console.log(this.tableData.length)
      if (this.tableData.length > 5) {
        this.isShow = false
      }
      // this.$router.push({ name: 'line', query: { href: link } })
      this.handleReload(link)
      // console.log(link)
    }
  }
}
</script>

<style scoped>
  .el-input {
    width: 97%;
    margin-bottom: 3%;
  }

  .input-with-select .el-input-group__prepend {
    background-color: #fff;
  }
</style>
