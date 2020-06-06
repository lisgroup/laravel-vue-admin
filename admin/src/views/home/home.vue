<template>
  <div>
    <nav-bar />
    <fieldSet />
    <div id="content">
      <form id="form" class="layui-form layui-form-pane" action="">
        <div class="layui-form-item">
          <label class="layui-form-label" style="width: 20%;">线路:</label>
          <div style="width: 50%;float: left;">
            <input
              v-model="lineName"
              type="text"
              name="linename"
              lay-verify="required"
              placeholder="线路名称，例：快线1, 55"
              autocomplete="off"
              class="layui-input"
              value=""
            >
            <input id="token" type="hidden" name="__token__" value="a26057366e1dd1eef9c47738fb3f6e11">
          </div>
          <div style="float: left">
            <button id="submit" class="layui-btn" lay-submit="" lay-filter="demo1" @click="search">搜索
            </button>
          </div>
        </div>
        <!--<div class="layui-form-item">
            <div class="layui-input-block">
                <button type="reset" class="layui-btn layui-btn-primary">重置</button>
            </div>
        </div>-->
      </form>
      <div id="cookieLine" style="margin-left: 5%;">
        <!--<button class="layui-btn layui-btn-normal submit-button" data-value="1">1</button>-->
      </div>
    </div>
    <div id="msg" />
    <Footer />
  </div>
</template>

<script>
import fieldSet from '../../components/common/fieldSet'
import { Footer } from '../../layout/components'

export default {
  name: 'Home',
  components: {
    fieldSet,
    Footer
  },
  data() {
    return {
      lineName: ''
    }
  },
  created() {

  },
  methods: {
    search(e) {
      // 阻止表单默认提交
      e.preventDefault()
      // let line = this.lineName;
      // 发送后台请求
      // this.$ajax.get()
    }
  }
}
</script>

<style scoped>

</style>
