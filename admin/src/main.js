import Vue from 'vue'

import 'normalize.css/normalize.css' // A modern alternative to CSS resets

import ElementUI from 'element-ui'
import 'element-ui/lib/theme-chalk/index.css'
// import locale from 'element-ui/lib/locale/lang/en' // lang i18n

// 引入 layUI css
import 'layui-src/dist/css/layui.css'

import '@/styles/index.scss' // global css

// 全局注册
// import with ES6
import mavonEditor from 'mavon-editor'
// markdown-it对象：md.s_markdown, md => mavonEditor 实例
//                 or
//                 mavonEditor.markdownIt
import 'mavon-editor/dist/css/index.css'
// use
Vue.use(mavonEditor)

import App from './App'
import router from './router'
import store from './store'

import '@/icons' // icon
import '@/permission' // permission control

// Vue.use(ElementUI, { locale })
Vue.use(ElementUI)

Vue.config.productionTip = false

new Vue({
  el: '#app',
  router,
  store,
  render: h => h(App)
})
