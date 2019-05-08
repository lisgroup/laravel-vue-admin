import request from '@/utils/request'

export function getList(params) {
  return request({
    url: '/api/config',
    method: 'get',
    params
  })
}

export function postAdd(params) {
  return request.post('/api/config', params)
}

export function edit(id) {
  return request.get('/api/config/' + id)
}

export function postEdit(id, params) {
  return request.patch('/api/config/' + id, params)
}

export function deleteAct(id) {
  return request.delete('/api/config/' + id)
}

/**
 * 搜索 config
 * @param params
 */
export function search(params) {
  return request({
    url: '/api/config_search',
    method: 'get',
    params
  })
}
