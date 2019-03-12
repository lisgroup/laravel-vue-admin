import request from '@/utils/request'

export function getList(params) {
  // return request.get('/api/roles', params)
  return request({
    url: '/api/roles',
    method: 'get',
    params
  })
}

export function getPermission() {
  return request.get('/api/roles/create')
}

export function postAdd(params) {
  return request.post('/api/roles', params)
}

export function edit(id) {
  return request.get('/api/roles/' + id)
}

export function postEdit(id, params) {
  return request.patch('/api/roles/' + id, params)
}

export function deleteAct(id) {
  return request.delete('/api/roles/' + id)
}

/**
 * 搜索 roles
 * @param params
 */
export function search(params) {
  return request({
    url: '/api/roles_search',
    method: 'get',
    params
  })
}

export function startTask(params) {
  return request({
    url: '/api/start_task',
    method: 'post',
    params
  })
}
