import Vue from 'vue'
//VueRouter:引入路由对象
import VueRouter from 'vue-router'

Vue.use(VueRouter)

// Axios:引入axios
import Axios from 'axios'
// Axios:挂载原型
Vue.prototype.$ajax = Axios

// 判断是否开发模式设置 URL
const debug = process.env.NODE_ENV !== 'production'
// 本地生产开发配置
const my_host = debug ? 'http://localhost/bus/laravel/public/index.php/api/' : 'https://www.guke1.com/api/';
// 默认 URL 配置
Axios.defaults.baseURL = my_host;


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
import router from './router'
import store from './store'

Vue.config.productionTip = false


new Vue({
  router,
  store,
  render: h => h(App)
}).$mount('#app')
