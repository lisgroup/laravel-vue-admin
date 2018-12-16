import Vue from 'vue'
//VueRouter:引入路由对象
import VueRouter from 'vue-router'

Vue.use(VueRouter)

const Home = () => import('../components/home/home.vue')
const Index = () => import('../components/home/index.vue')
const Line = () => import('../components/home/line.vue')


/* 后台相关 Layout */
const Layout = () => import('../components/common/navBar.vue')

const routes = [
  { path: '/', redirect: { name: 'index' } },
  { path: '/home', name: 'home', component: Home },
  { path: '/index', name: 'index', component: Index },
  { path: '/line', name: 'line', component: Line },
  // 后台路由
  // { path: '/login', component: () => import('@/views/login/index'), hidden: true },
  // { path: '/404', component: () => import('@/views/404'), hidden: true },
  {
    path: '/admin',
    component: Layout,
    redirect: '/admin/dashboard',
    name: 'Dashboard',
    hidden: true,
    children: [{
      path: 'dashboard',
      // component: () => import('@/views/dashboard/index')
      component: () => import('../components/home/index.vue')
    }]
  },
]

// export default routes

// VueRouter:创建对象并配置路由规则！！！导航
export default new VueRouter({
  // VueRouter：配置路由规则
  routes
})
