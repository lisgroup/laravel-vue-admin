import request from '@/utils/request'

export function getList(params) {
  return request({
    url: '/api/tag',
    method: 'get',
    params
  })
}

export function postAdd(params) {
  return request.post('/api/tag', params)
}

export function edit(id) {
  return request.get('/api/tag/' + id)
}

export function postEdit(id, params) {
  return request.patch('/api/tag/' + id, params)
}

export function deleteAct(id) {
  return request.delete('/api/tag/' + id)
}

/**
 * 搜索 tag
 * @param params
 */
export function search(params) {
  return request({
    url: '/api/tag_search',
    method: 'get',
    params
  })
}
