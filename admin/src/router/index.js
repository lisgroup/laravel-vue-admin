import Vue from 'vue'
import Router from 'vue-router'

// in development-env not use lazy-loading, because lazy-loading too many pages will cause webpack hot update too slow. so only in production use lazy-loading;
// detail: https://panjiachen.github.io/vue-element-admin-site/#/lazy-loading

Vue.use(Router)

/* Layout */
const Layout = () => import('../views/layout/Layout')

/**
 * hidden: true                   if `hidden:true` will not show in the sidebar(default is false)
 * alwaysShow: true               if set true, will always show the root menu, whatever its child routes length
 *                                if not set alwaysShow, only more than one route under the children
 *                                it will becomes nested mode, otherwise not show the root menu
 * redirect: noredirect           if `redirect:noredirect` will no redirect in the breadcrumb
 * name:'router-name'             the name is used by <keep-alive> (must set!!!)
 * meta : {
    title: 'title'               the name show in submenu and breadcrumb (recommend set)
    icon: 'svg-name'             the icon show in the sidebar,
  }
 **/
export const constantRouterMap = [
  // { path: '/', redirect: '/index', hidden: true },
  { path: '/', name: 'index', component: () => import('@/views/home/index'), hidden: true },
  { path: '/line', name: 'line', component: () => import('@/views/home/line'), hidden: true },
  { path: '/home', component: () => import('@/views/home/home'), hidden: true },
  { path: '/md', name: 'md', component: () => import('@/views/markdown/index'), hidden: true },
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
      component: () => import('@/views/dashboard/index')
    }]
  },

  {
    path: '/category',
    component: Layout,
    redirect: '/category/index',
    name: 'Category-Nav',
    meta: { title: '栏目菜单', icon: 'category' },
    children: [
      { path: '/category/add', name: 'NewCategory', component: () => import('@/views/category/add'), meta: { title: '添加栏目' }, hidden: true },
      { path: '/category/edit/:id', name: 'EditCategory', component: () => import('@/views/category/edit'), hidden: true },
      {
        path: '/category/index',
        name: 'Category',
        component: () => import('@/views/category/index'),
        meta: { title: '栏目管理', icon: 'ico-category' }
      },
      { path: '/nav/add', name: 'AddNav', component: () => import('@/views/nav/add'), hidden: true },
      { path: '/nav/edit/:id', name: 'EditNav', component: () => import('@/views/nav/edit'), hidden: true },
      {
        path: '/nav',
        name: 'Nav',
        component: () => import('@/views/nav'),
        meta: { title: '导航管理', icon: 'nav' }
      }
    ]
  },

  {
    path: '/list',
    component: Layout,
    redirect: '/task',
    name: '列表',
    meta: { title: '列表展示', icon: 'example' },
    children: [
      { path: '/task/search', name: 'search', component: () => import('@/views/task/search'), hidden: true },
      { path: '/task/newBus', name: 'NewBus', component: () => import('@/views/task/newBus'), hidden: true },
      { path: '/task/edit/:id', name: 'taskEdit', component: () => import('@/views/task/edit'), hidden: true },
      {
        path: '/task',
        name: '定时任务',
        component: () => import('@/views/task/index'),
        meta: { title: '定时任务', icon: 'table' }
      },
      { path: 'lines/add', name: 'linesAdd', component: () => import('@/views/lines/add'), hidden: true },
      { path: 'lines/edit/:id', name: 'linesEdit', component: () => import('@/views/lines/edit'), hidden: true },
      {
        path: 'lines',
        name: '公交列表',
        component: () => import('@/views/lines/index'),
        meta: { title: '公交列表', icon: 'table' }
      },
      {
        path: 'tree',
        name: 'Tree',
        component: () => import('@/views/tree/index'),
        meta: { title: 'Tree', icon: 'tree' },
        hidden: true
      }
    ]
  },

  {
    path: '/user',
    component: Layout,
    redirect: '/user/index',
    hidden: true,
    children: [
      { path: 'index', name: 'userIndex', component: () => import('@/views/user/index'), hidden: true },
      { path: 'password', name: 'userPassword', component: () => import('@/views/user/password'), hidden: true }
    ]
  },

  {
    path: '/form',
    component: Layout,
    children: [
      {
        path: 'index',
        name: 'Form',
        component: () => import('@/views/form/index'),
        meta: { title: 'Form', icon: 'form' }
      }
    ], hidden: true
  },

  {
    path: '/nested',
    component: Layout,
    redirect: '/nested/menu1',
    name: 'Nested',
    meta: {
      title: 'Nested',
      icon: 'nested'
    },
    children: [
      {
        path: 'menu1',
        component: () => import('@/views/nested/menu1/index'), // Parent router-view
        name: 'Menu1',
        meta: { title: 'Menu1' },
        children: [
          {
            path: 'menu1-1',
            component: () => import('@/views/nested/menu1/menu1-1'),
            name: 'Menu1-1',
            meta: { title: 'Menu1-1' }
          },
          {
            path: 'menu1-2',
            component: () => import('@/views/nested/menu1/menu1-2'),
            name: 'Menu1-2',
            meta: { title: 'Menu1-2' },
            children: [
              {
                path: 'menu1-2-1',
                component: () => import('@/views/nested/menu1/menu1-2/menu1-2-1'),
                name: 'Menu1-2-1',
                meta: { title: 'Menu1-2-1' }
              },
              {
                path: 'menu1-2-2',
                component: () => import('@/views/nested/menu1/menu1-2/menu1-2-2'),
                name: 'Menu1-2-2',
                meta: { title: 'Menu1-2-2' }
              }
            ]
          },
          {
            path: 'menu1-3',
            component: () => import('@/views/nested/menu1/menu1-3'),
            name: 'Menu1-3',
            meta: { title: 'Menu1-3' }
          }
        ]
      },
      {
        path: 'menu2',
        component: () => import('@/views/nested/menu2/index'),
        meta: { title: 'menu2' }
      }
    ]
  },
  { path: '*', redirect: '/404', hidden: true }
]

export default new Router({
  // mode: 'history', //后端支持可开
  scrollBehavior: () => ({ y: 0 }),
  routes: constantRouterMap
})
