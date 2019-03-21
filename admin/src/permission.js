import router from './router'
import { routeManage, routerAdmin } from './router/router'
import store from './store'
import NProgress from 'nprogress' // Progress 进度条
import 'nprogress/nprogress.css'// Progress 进度条样式
import { Message } from 'element-ui'
import { getToken } from '@/utils/auth' // 验权

console.log(router)
router.addRoutes(routerAdmin)
router.addRoutes(routeManage)
const whiteList = ['/login', '/index', '/line', '/home', '/404', '/', '', '/md'] // 不重定向白名单
router.beforeEach((to, from, next) => {
  NProgress.start()
  if (getToken()) {
    if (to.path === '/login') {
      next({ path: '/admin' })
      NProgress.done() // if current page is dashboard will not trigger	afterEach hook, so manually handle it
    } else {
      if (store.getters.roles.length === 0) {
        store.dispatch('GetInfo').then(res => { // 拉取用户信息
          const roles = res.data.roles
          console.log(roles)
          store.dispatch('GenerateRoutes', { roles }).then(() => { // 根据roles权限生成可访问的路由表
            router.addRoutes(store.getters.addRouters) // 动态添加可访问路由表
            next({ ...to, replace: true }) // hack方法 确保addRoutes已完成 ,set the replace: true so the navigation will not leave a history record
          })
          if (!to.meta.role || whiteList.indexOf(to.path) !== -1 || hasPermission(res.data.roles, to.meta.roles)) {
            next()
          } else {
            next({ path: '/404' })
            NProgress.done()
          }
        }).catch((err) => {
          store.dispatch('FedLogOut').then(() => {
            Message.error(err || 'Verification failed, please login again')
            next({ path: '/admin' })
          })
        })
      } else {
        // 动态改变权限
        if (!to.meta.role || whiteList.indexOf(to.path) !== -1 || hasPermission(store.getters.roles, to.meta.roles)) {
          next()
        } else {
          next({ path: '/404' })
          NProgress.done()
        }
      }
    }
  } else {
    if (whiteList.indexOf(to.path) !== -1) {
      next()
    } else {
      next(`/login?redirect=${to.path}`) // 否则全部重定向到登录页
      NProgress.done()
    }
  }
})

router.afterEach(() => {
  NProgress.done() // 结束Progress
})

// permissiom judge function
function hasPermission(roles, permissionRoles) {
  if (roles.indexOf('Super Administrator') >= 0) return true // admin permission passed directly
  if (!permissionRoles) return true
  return roles.some(role => permissionRoles.indexOf(role) >= 0)
}
