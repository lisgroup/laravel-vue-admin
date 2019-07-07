import request from '@/utils/request'

export function getList(params) {
  // return request.get('/api/category', params)
  return request({
    url: '/api/category',
    method: 'get',
    params
  })
}

export function postAdd(params) {
  return request.post('/api/category', params)
}

export function edit(id) {
  return request.get('/api/category/' + id)
}

export function postEdit(id, params) {
  return request.patch('/api/category/' + id, params)
}

export function deleteAct(id) {
  return request.delete('/api/category/' + id)
}

/**
 * 搜索 category
 * @param params
 */
export function search(params) {
  return request({
    url: '/api/category_search',
    method: 'get',
    params
  })
}
