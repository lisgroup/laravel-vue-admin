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
