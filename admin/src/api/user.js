import request from '@/utils/request'

export function getUser(params) {
  return request({
    url: '/api/user',
    method: 'get',
    params
  })
}

export function postUser(params) {
  return request.post('/api/user', params)
}

export function editUser(id) {
  return request.get('/api/user/' + id)
}

export function postEditUser(id, params) {
  return request.patch('/api/user/' + id, params)
}

export function deleteUser(id) {
  return request.delete('/api/user/' + id)
}

export function postEditPassword(params) {
  return request.post('/api/user_password', params)
}
