import Vue from 'vue'

// Axios:引入axios
import Axios from 'axios'
// Axios:挂载原型
Vue.prototype.$ajax = Axios
// 默认 URL 在 .env|(.development) 中配置
Axios.defaults.baseURL = process.env.VUE_APP_BASE_API + 'api/'

import 'normalize.css/normalize.css' // A modern alternative to CSS resets
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
