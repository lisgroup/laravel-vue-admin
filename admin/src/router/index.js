import Vue from 'vue'
import Router from 'vue-router'

Vue.use(Router)

/* Layout */
import Layout from '@/layout'

/**
 * Note: sub-menu only appear when route children.length >= 1
 * Detail see: https://panjiachen.github.io/vue-element-admin-site/guide/essentials/router-and-nav.html
 *
 * hidden: true                   if set true, item will not show in the sidebar(default is false)
 * alwaysShow: true               if set true, will always show the root menu
 *                                if not set alwaysShow, when item has more than one children route,
 *                                it will becomes nested mode, otherwise not show the root menu
 * redirect: noRedirect           if set noRedirect will no redirect in the breadcrumb
 * name:'router-name'             the name is used by <keep-alive> (must set!!!)
 * meta : {
    roles: ['admin','editor']    control the page roles (you can set multiple roles)
    title: 'title'               the name show in sidebar and breadcrumb (recommend set)
    icon: 'svg-name'             the icon show in the sidebar
    breadcrumb: false            if set false, the item will hidden in breadcrumb(default is true)
    activeMenu: '/example/list'  if set path, the sidebar will highlight the path you set
  }
 */

/**
 * constantRoutes
 * a base page that does not have permission requirements
 * all roles can be accessed
 */
const Super = 'Super Administrator'
const Admin = 'Admin'

// 管理一般路由
export const routeAdmin = [
  {
    path: '/api_excel',
    component: Layout,
    redirect: '/api_excel/index',
    name: 'Excel-List',
    meta: { title: '批量测试管理', icon: 'ico-table', roles: [Super, Admin] },
    children: [
      { path: '/api_excel/edit/:id', name: 'EditExcel', component: () => import('@/views/api_excel/edit'), hidden: true },
      {
        path: '/api_excel/add',
        name: 'AddExcel',
        component: () => import('@/views/api_excel/add'),
        meta: { title: '上传测试', icon: 'excel', roles: [Super, Admin] }
      },
      {
        path: '/api_excel/index',
        name: 'Excel',
        component: () => import('@/views/api_excel/index'),
        meta: { title: '测试管理', icon: 'ico-aliyun', roles: [Super, Admin] }
      },
      { path: '/api_param/add', name: 'AddApiParam', component: () => import('@/views/api_param/add'), hidden: true },
      { path: '/api_param/edit/:id', name: 'EditApiParam', component: () => import('@/views/api_param/edit'), hidden: true },
      {
        path: '/api_param/index',
        name: 'ApiParam',
        component: () => import('@/views/api_param/index'),
        meta: { title: '接口列表', icon: 'api', roles: [Super, Admin] }
      }
    ]
  }
]
// 超级管理员路由
export const routeSuper = [
  // { path: '/', redirect: '/index', hidden: true },

  {
    path: '/category',
    component: Layout,
    redirect: '/category/index',
    name: 'Category-Nav',
    meta: { title: '栏目菜单', icon: 'category', roles: [Super] },
    children: [
      { path: '/category/add', name: 'AddCategory', component: () => import('@/views/category/add'), meta: { title: '添加栏目' }, hidden: true },
      { path: '/category/edit/:id', name: 'EditCategory', component: () => import('@/views/category/edit'), hidden: true },
      {
        path: '/category/index',
        name: 'Category',
        component: () => import('@/views/category/index'),
        meta: { title: '栏目管理', icon: 'ico-category', roles: [Super] }
      },
      { path: '/nav/add', name: 'AddNav', component: () => import('@/views/nav/add'), hidden: true },
      { path: '/nav/edit/:id', name: 'EditNav', component: () => import('@/views/nav/edit'), hidden: true },
      {
        path: '/nav',
        name: 'Nav',
        component: () => import('@/views/nav'),
        meta: { title: '导航管理', icon: 'nav', roles: [Super] }
      },
      { path: '/tag/add', name: 'AddTag', component: () => import('@/views/tag/add'), meta: { title: '添加标签' }, hidden: true },
      { path: '/tag/edit/:id', name: 'EditTag', component: () => import('@/views/tag/edit'), hidden: true },
      {
        path: '/tag',
        name: 'Tag',
        component: () => import('@/views/tag/index'),
        meta: { title: '标签列表', icon: 'tag', roles: [Super] }
      }
    ]
  },

  {
    path: '/article',
    component: Layout,
    // redirect: '/article',
    name: 'Article-List',
    meta: { title: '文章管理', icon: 'article', roles: [Super] },
    children: [
      { path: '/article/edit/:id', name: 'EditArticle', component: () => import('@/views/article/edit'), hidden: true },
      {
        path: '/article/index',
        name: 'Article',
        component: () => import('@/views/article/index'),
        meta: { title: '文章管理', icon: 'ico-article', roles: [Super] }
      },
      {
        path: '/article/add',
        name: 'AddArticle',
        component: () => import('@/views/article/add'),
        meta: { title: '添加文章', icon: 'add', roles: [Super] }
      }
    ]
  },

  {
    path: '/list',
    component: Layout,
    redirect: '/task',
    name: '公交',
    meta: { title: '公交管理', icon: 'bus', roles: [Super] },
    children: [
      { path: '/task/search', name: 'search', component: () => import('@/views/task/search'), hidden: true },
      { path: '/task/newBus', name: 'NewBus', component: () => import('@/views/task/newBus'), hidden: true },
      { path: '/task/edit/:id', name: 'taskEdit', component: () => import('@/views/task/edit'), hidden: true },
      {
        path: '/task',
        name: '定时任务',
        component: () => import('@/views/task/index'),
        meta: { title: '定时任务', icon: 'task', roles: [Super] }
      },
      { path: 'lines/add', name: 'linesAdd', component: () => import('@/views/lines/add'), hidden: true },
      { path: 'lines/edit/:id', name: 'linesEdit', component: () => import('@/views/lines/edit'), hidden: true },
      {
        path: 'lines',
        name: '公交列表',
        component: () => import('@/views/lines/index'),
        meta: { title: '公交列表', icon: 'table', roles: [Super] }
      },
      {
        path: 'config',
        name: '配置管理',
        component: () => import('@/views/config/index'),
        meta: { title: '配置列表', icon: 'table', roles: [Super] }
      }
    ]
  },

  {
    path: 'user',
    component: Layout,
    redirect: '/user',
    name: '权限',
    meta: { title: '权限管理', icon: 'auth', roles: [Super] },
    children: [
      { path: 'index', name: 'userIndex', component: () => import('@/views/user/index'), hidden: true },
      { path: 'password', name: 'userPassword', component: () => import('@/views/user/password'), hidden: true },
      { path: '/user/add', name: 'AddUser', component: () => import('@/views/user/add'), hidden: true },
      { path: '/user/edit/:id', name: 'EditUser', component: () => import('@/views/user/edit'), hidden: true },
      {
        path: '/user',
        name: '用户管理',
        component: () => import('@/views/user/index'),
        meta: { title: '用户列表', icon: 'user', roles: [Super] }
      },
      { path: '/permission/add', name: 'AddPermission', component: () => import('@/views/permission/add'), hidden: true },
      { path: '/permission/edit/:id', name: 'EditPermission', component: () => import('@/views/permission/edit'), hidden: true },
      {
        path: '/permission',
        name: '权限列表',
        component: () => import('@/views/permission/index'),
        meta: { title: '权限列表', icon: 'permission', roles: [Super] }
      },

      { path: '/role/add', name: 'Addroles', component: () => import('@/views/role/add'), hidden: true },
      { path: '/role/edit/:id', name: 'Editroles', component: () => import('@/views/role/edit'), hidden: true },
      {
        path: '/role',
        name: '角色管理',
        component: () => import('@/views/role/index'),
        meta: { title: '角色管理', icon: 'role', roles: [Super] }
      }
    ]
  }

]

const routeTest = [
  // 404 page must be placed at the end !!!
  { path: '*', redirect: '/404', hidden: true }
]

/**
 * asyncRoutes
 * the routes that need to be dynamically loaded based on user roles
 */
export const asyncRoutes = [...routeAdmin, ...routeSuper, ...routeTest]

// 基础路由
const routeBase = [
  { path: '/', name: 'index', component: () => import('@/views/home/index'), hidden: true },
  { path: '/bus', name: 'bus', component: () => import('@/views/home/index'), hidden: true },
  { path: '/tool', name: 'tool', component: () => import('@/views/home/tool'), hidden: true },
  { path: '/line', name: 'line', component: () => import('@/views/home/line'), hidden: true },
  { path: '/home', component: () => import('@/views/home/home'), hidden: true },
  { path: '/md', name: 'md', component: () => import('@/views/markdown/index'), hidden: true },
  { path: '/out', name: 'out', component: () => import('@/views/home/out'), hidden: true },
  { path: '/excel', name: 'excel', component: () => import('@/views/home/excel'), hidden: true },
  { path: '/upload', name: 'upload', component: () => import('@/views/home/upload'), hidden: true },
  { path: '/websocket', name: 'websocket', component: () => import('@/views/home/websocket'), hidden: true },
  { path: '/clipboard', name: 'clipboard', component: () => import('@/views/home/clipboard'), hidden: true },
  { path: '/echarts', name: 'echarts', component: () => import('@/views/echarts/index'), hidden: true },
  // { path: '/index', component: () => import('@/views/home/index'), hidden: true },
  { path: '/login', component: () => import('@/views/login/index'), hidden: true },
  { path: '/404', component: () => import('@/views/404'), hidden: true },

  {
    path: '/admin',
    component: Layout,
    redirect: '/admin/dashboard',
    name: 'Dashboard',
    hidden: true,
    children: [{
      path: 'dashboard',
      component: () => import('@/views/dashboard/index'),
      meta: { title: 'Dashboard', icon: 'dashboard' }
    }]
  }
]

/**
 * constantRoutes
 * a base page that does not have permission requirements
 * all roles can be accessed
 */
export const constantRoutes = [...routeBase]

const createRouter = () => new Router({
  // mode: 'history', // require service support
  scrollBehavior: () => ({ y: 0 }),
  routes: constantRoutes
})

const router = createRouter()

// Detail see: https://github.com/vuejs/vue-router/issues/1234#issuecomment-357941465
export function resetRouter() {
  const newRouter = createRouter()
  router.matcher = newRouter.matcher // reset router
}

export default router
