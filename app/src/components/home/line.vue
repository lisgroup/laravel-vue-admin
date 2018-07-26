<template>
    <div>
        <el-input placeholder="线路名称，例：快线1, 55" v-model="input">
            <template slot="prepend">线路</template>
            <el-button slot="append" icon="el-icon-search" @click="goSearch">搜索</el-button>
        </el-input>

        <el-table v-if="isShow"
                  border
                  :data="tableData"
                  style="width: 100%">
            <el-table-column
                    label="线路"
                    width="100">
                <template slot-scope="scope">
                    <el-button type="text" @click="handleCheck(scope.$index, scope.row.link)">{{ scope.row.bus }}
                    </el-button>
                </template>
            </el-table-column>
            <el-table-column
                    label="方向"
                    width="">
                <template slot-scope="scope">
                    <el-button type="text" @click="handleCheck(scope.$index, scope.row.link)">{{ scope.row.FromTo }}
                    </el-button>
                </template>
            </el-table-column>
        </el-table>

        <fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;color:green;">
            <legend>{{to}}&nbsp;<button class="layui-btn layui-btn-normal" @click="handleReload()">刷新</button>
            </legend>
        </fieldset>
        <el-table
                :data="tableLine"
                border
                style="width: 100%">
            <el-table-column
                    prop="stationName"
                    label="站台"
                    width="180">
            </el-table-column>
            <el-table-column
                    prop="stationCode"
                    label="编号"
                    width="180">
            </el-table-column>
            <el-table-column
                    prop="carCode"
                    label="车牌">
            </el-table-column>
            <el-table-column
                    prop="ArrivalTime"
                    label="进站时间">
            </el-table-column>
        </el-table>
    </div>
</template>

<script>
  export default {
    name: "index",
    data() {
      return {
        isShow: false,
        input: '',
        to: '',
        tableData: [],
        tableLine: [],
      }
    },
    created() {
      this.handleReload();
    },
    methods: {
      handleReload(href) {
        let url = "/busLine";
        if (!href) {
          // console.log(href);
          href = this.$route.query.href;
        }
        // console.log(href);
        let param = "href=" + href;
        this.$ajax.post(url, param).then(res => {
          // console.log(res.data);
          this.to = res.data.result.to;
          this.tableLine = res.data.result.line;
        }).catch(err => {
          // console.log(err);
        });
      },
      goSearch() {
        let line = this.input;
        if (!line) {
          this.$message({
            message: '线路名称不能为空',
            type: 'warning'
          });
          return false;
        }
        this.isShow = true;
        let url = "/getList?linename=" + line;
        this.$ajax.get(url).then(res => {
          let data = res.data;
          if (data.error_code === 0) {
            // console.log(res.data);
            this.tableData = res.data.result;
          }
        }).catch(err => {
          // console.log(err);
        });
      },
      handleCheck(index, link) {
        // console.log(this.tableData.length);
        if (this.tableData.length > 5) {
          this.isShow = false;
        }
        // this.$router.push({ name: 'line', query: { href: link } });
        this.handleReload(link);
        // console.log(link);
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
