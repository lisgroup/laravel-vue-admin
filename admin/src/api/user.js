import request from '@/utils/request'

export function login(userInfo) {
  return request.post('/api/user/login', userInfo)
}

export function getInfo(token) {
  return request({
    url: '/api/user/info',
    method: 'get',
    params: { token }
  })
}

export function logout() {
  return request({
    url: '/api/user/logout',
    method: 'post'
  })
}

// import { getRoute, setRoute } from '@/utils/auth'
export function route(token) {
  return request.get('/api/routes', token)
}

export function getList(params) {
  return request({
    url: '/api/user',
    method: 'get',
    params
  })
}

export function getRole() {
  return request.get('/api/permissions/create')
}

export function postAdd(params) {
  return request.post('/api/user', params)
}

export function edit(id) {
  return request.get('/api/user/' + id)
}

export function postEdit(id, params) {
  return request.patch('/api/user/' + id, params)
}

export function deleteAct(id) {
  return request.delete('/api/user/' + id)
}

export function postEditPassword(params) {
  return request.post('/api/user_password', params)
}

/**
 * 搜索 api_param
 * @param params
 */
export function search(params) {
  return request({
    url: '/api/api_user_search',
    method: 'get',
    params
  })
}
