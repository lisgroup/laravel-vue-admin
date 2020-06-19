<template>
  <div>
    <nav-bar />
    <fieldSet />

    <el-input v-model="input" placeholder="线路名称，例：快线1, 55" @keyup.enter.native="goSearch">
      <template slot="prepend">线路</template>
      <el-button slot="append" icon="el-icon-search" @click="goSearch">搜索</el-button>
    </el-input>

    <el-table
      v-if="isShow"
      :data="tableData"
      border
      style="width: 100%"
    >
      <el-table-column label="线路" width="100">
        <template slot-scope="scope">
          <el-button type="text" @click="handleCheck(scope.$index, scope.row)">{{ scope.row.bus }}</el-button>
        </template>
      </el-table-column>
      <el-table-column label="方向" width="">
        <template slot-scope="scope">
          <el-button type="text" @click="handleCheck(scope.$index, scope.row)" v-html="scope.row.FromTo" />
        </template>
      </el-table-column>
    </el-table>
    <Footer />
  </div>
</template>

<script>
import request from '@/utils/request'
import fieldSet from '../../components/common/fieldSet'
import { Footer } from '../../layout/components'

export default {
  name: 'Index',
  components: {
    fieldSet,
    Footer
  },
  data() {
    return {
      isShow: false,
      input: '',
      tableData: []
    }
  },
  created() {
    this.getLine()
  },
  methods: {
    goSearch() {
      const line = this.input
      if (!line) {
        this.$message({
          message: '线路名称不能为空',
          type: 'warning'
        })
        return false
      }

      const query = { linename: line }
      this.$router.push({ name: 'bus', query })

      this.isShow = true
      const url = '/api/getList?linename=' + line
      request.get(url).then(res => {
        // const data = res.data
        this.tableData = res.data
        // console.log(this.tableData)
      }).catch(err => {
        return err
        // console.log(err);
      })
    },
    handleCheck(index, row) {
      // keep-alive 实现前进后退不刷新
      const query = row.lineID ? { lineID: row.lineID, to: row.LineInfo } : { href: row.link }
      this.$router.push({ name: 'line', query })
    },

    getLine() {
      const that = this
      this.loading = true
      const line = this.$route.query.linename
      if (line) {
        that.input = line
        that.goSearch()
      }

      setTimeout(() => {
        this.loading = false
      }, 800)
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
