<template>
  <div class="nav">
    <el-menu :default-active="activeIndex" class="el-menu-demo" mode="horizontal">
      <el-row :gutter="1">
        <el-col :xs="6" :sm="6" :md="4" :lg="4" :xl="4">
          <div class="grid-content">
            <el-menu-item index="1">
              <router-link :to="{name: 'bus'}">首页</router-link>
            </el-menu-item>
          </div>
        </el-col>
        <el-col :xs="6" :sm="6" :md="4" :lg="4" :xl="4">
          <div class="grid-content">
            <el-menu-item index="1">
              <router-link :to="{name: 'tool'}">在线工具</router-link>
            </el-menu-item>
          </div>
        </el-col>
        <el-col :xs="6" :sm="6" :md="4" :lg="4" :xl="4">
          <div class="grid-content">
            <el-menu-item index="3">
              <router-link :to="{name: 'out'}" class="login">中文排版</router-link>
            </el-menu-item>
          </div>
        </el-col>
        <el-col :xs="6" :sm="6" :md="4" :lg="4" :xl="4">
          <div class="grid-content">
            <el-menu-item index="4">
              <router-link :to="{name: 'excel'}" class="login">在线表格 Excel</router-link>
            </el-menu-item>
          </div>
        </el-col>
        <el-col :xs="6" :sm="6" :md="4" :lg="4" :xl="4">
          <div class="grid-content">
            <el-menu-item index="4">
              <router-link :to="{name: 'upload'}" class="login">图片转文字</router-link>
            </el-menu-item>
          </div>
        </el-col>
        <el-col :xs="6" :sm="6" :md="4" :lg="4" :xl="4">
          <div class="grid-content">
            <el-submenu index="2">
              <template slot="title">其它工具</template>
              <el-menu-item index="2-2">
                <router-link :to="{name: 'md'}" class="login">Markdown</router-link>
              </el-menu-item>
              <el-menu-item index="2-2">
                <router-link :to="{name: 'echarts'}">图表</router-link>
              </el-menu-item>
            </el-submenu>
          </div>
        </el-col>
        <!--<el-col :xs="6" :sm="6" :md="0" :lg="0" :xl="0"/>-->
      </el-row>
    </el-menu>
  </div>
</template>

<script>
export default {
  name: 'NavBar',
  data() {
    return {
      activeIndex: ''
    }
  }
}
</script>

<style scoped>
  .nav {
    padding-left: 0;
    margin-bottom: 20px;
    list-style: none
  }
  .nav:after, .nav:before {
    display:table;
    content:" "
  }
  .nav:after {
    clear:both
  }
  .el-row {
    /*margin-bottom: 20px;*/
    height: 50px;
  }

  .el-col {
    border-radius: 4px;
  }

  .grid-content {
    border-radius: 4px;
    min-height: 50px;
  }

  .grid-content a{
    display: block;
  }
  ul > li > a{
    display: block;
  }
</style>
