import request from '@/utils/request'

export function getList(params) {
  // return request.get('/api/permissions', params)
  return request({
    url: '/api/permissions',
    method: 'get',
    params
  })
}

export function getRole() {
  return request.get('/api/permissions/create')
}

export function postAdd(params) {
  return request.post('/api/permissions', params)
}

export function edit(id) {
  return request.get('/api/permissions/' + id)
}

export function postEdit(id, params) {
  return request.patch('/api/permissions/' + id, params)
}

export function deleteAct(id) {
  return request.delete('/api/permissions/' + id)
}

/**
 * 搜索 permissions
 * @param params
 */
export function search(params) {
  return request({
    url: '/api/permissions_search',
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
