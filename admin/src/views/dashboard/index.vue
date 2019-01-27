<template>
  <div class="dashboard-container">

    <fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
      <legend>服务器信息</legend>
    </fieldset>
    <el-card class="box-card">
      <el-collapse v-model="activeNames" @change="handleChange">
        <el-collapse-item title="服务器配置" name="1">
          <el-table :data="serve" style="width: 100%">
            <el-table-column prop="name" label="名称" width=""/>
            <el-table-column prop="value" label="数值" width=""/>
          </el-table>
        </el-collapse-item>
      </el-collapse>
    </el-card>

    <el-card class="box-card">
      <el-collapse v-model="activeNames" @change="handleChange">
        <el-collapse-item title="服务器配置" name="2">
          <el-table :data="php" style="width: 100%">
            <el-table-column prop="name" label="名称" width=""/>
            <el-table-column prop="value" label="数值" width=""/>
          </el-table>
        </el-collapse-item>
      </el-collapse>
    </el-card>

    <div class="clearfix"/>
    <fieldset class="clearfix layui-elem-field layui-field-title" style="margin-top: 20px;">
      <legend>管理员信息</legend>
    </fieldset>
    <el-card class="box-card">
      <div class="dashboard-text">name:{{ name }}</div>
      <div class="dashboard-text">roles:<span v-for="role in roles" :key="role">{{ role }}</span></div>
    </el-card>
    <el-card class="box-card">
      <div id="chartLine" style="width:100%; height:400px;"/>
    </el-card>
    <div class="clearfix"/>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'
import { getList } from '../../api/dashboard'
import echarts from 'echarts'

export default {
  name: 'Dashboard',
  data() {
    return {
      loading: false,
      is_serve: true,
      is_php: true,
      serve: [],
      php: [],
      activeNames: ['1', '2'],
      chartLine: null
    }
  },
  computed: {
    ...mapGetters([
      'name',
      'roles'
    ])
  },
  created() {
    this.init()
  },
  mounted() {
    this.drawLineChart()
  },
  updated() {
    this.drawLineChart()
  },
  methods: {
    init() {
      this.loading = true
      getList().then(response => {
        this.loading = false
        this.serve = response.data.serve
        this.php = response.data.php
      })
    },
    closeServe() {
      this.is_serve = false
    },
    closePhp() {
      this.is_php = false
    },
    handleChange(val) {
      console.log(val)
    },
    drawLineChart() {
      this.chartLine = echarts.init(document.getElementById('chartLine'))
      this.chartLine.setOption({
        title: {
          text: '登录日志数据'
        },
        tooltip: {
          trigger: 'axis'
        },
        legend: {
          data: ['登录成功']
        },
        grid: {
          left: '3%',
          right: '4%',
          bottom: '3%',
          containLabel: true
        },
        xAxis: {
          type: 'category',
          boundaryGap: false,
          data: ['周一', '周二', '周三', '周四', '周五', '周六', '周日']
        },
        yAxis: {
          type: 'value'
        },
        series: [
          {
            name: '登录成功',
            type: 'line',
            stack: '总量',
            data: [220, 182, 191, 234, 290, 330, 310],
            smooth: true
          }
        ]
      })
    }
  }
}
</script>

<style rel="stylesheet/scss" lang="scss" scoped>
  .dashboard {
    &-container {
      margin: 30px;
    }

    &-text {
      font-size: 30px;
      line-height: 46px;
    }
  }

  .text {
    font-size: 14px;
  }

  .item {
    margin-bottom: 18px;
  }

  .clearfix:before,
  .clearfix:after {
    display: table;
    content: "";
  }

  .clearfix:after {
    clear: both
  }

  .box-card {
    width: 40%;
    float: left;
    margin: 0 2%;
  }
</style>
