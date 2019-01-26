import request from '@/utils/request'

export function getList(params) {
  // return request.get('/api/article', params)
  return request({
    url: '/api/article',
    method: 'get',
    params
  })
}

export function postAdd(params) {
  return request.post('/api/article', params)
}

export function edit(id) {
  return request.get('/api/article/' + id)
}

export function postEdit(id, params) {
  return request.patch('/api/article/' + id, params)
}

export function deleteAct(id) {
  return request.delete('/api/article/' + id)
}

/**
 * 搜索 article
 * @param params
 */
export function search(params) {
  return request({
    url: '/api/article_search',
    method: 'get',
    params
  })
}

export function axios(params) {
  return request(params)
}
