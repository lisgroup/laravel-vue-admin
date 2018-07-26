import Vue from 'vue'
//VueRouter:引入路由对象
import VueRouter from 'vue-router'

Vue.use(VueRouter)

// Axios:引入axios
import Axios from 'axios'
// Axios:挂载原型
Vue.prototype.$ajax = Axios
// 默认 URL 配置
Axios.defaults.baseURL = 'https://www.guke1.com/php/index.php/index/index';

import ElementUI from 'element-ui'
import 'element-ui/lib/theme-chalk/index.css'

Vue.use(ElementUI)

// 引入 layUI css
import 'layui-src/dist/css/layui.css'

// 引入全局组件需要的组件对象 开始
import NavBar from './components/common/navBar.vue'
// 引入全局组件需要的组件对象 开始
Vue.component('NavBar', NavBar) // 使用最好以 nav-bar

// 引入自己的vue文件 开始
import App from './App.vue'
import Home from './components/home/home.vue'
import Index from './components/home/index.vue'
import Line from './components/home/line.vue'

Vue.config.productionTip = false

// VueRouter:创建对象并配置路由规则！！！导航
let router = new VueRouter({
  // VueRouter：配置路由规则
  routes: [
    { path: '/', redirect: { name: 'index' } },
    { path: '/home', name: 'home', component: Home },
    { path: '/index', name: 'index', component: Index },
    { path: '/line', name: 'line', component: Line }
  ]
})

new Vue({
  router,
  render: h => h(App)
}).$mount('#app')
