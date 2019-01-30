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
    <div class="clearfix"/>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'
import { getList } from '../../api/dashboard'

export default {
  name: 'Dashboard',
  data() {
    return {
      loading: false,
      is_serve: true,
      is_php: true,
      serve: [],
      php: [],
      activeNames: ['1', '2']
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
