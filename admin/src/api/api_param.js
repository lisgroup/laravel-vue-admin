import request from '@/utils/request'

export function getListParam(params) {
  return request({
    url: '/api/api_param',
    method: 'get',
    params
  })
}

export function postAdd(params) {
  return request.post('/api/api_param', params)
}

export function edit(id) {
  return request.get('/api/api_param/' + id)
}

export function postEdit(id, params) {
  return request.patch('/api/api_param/' + id, params)
}

export function deleteAct(id) {
  return request.delete('/api/api_param/' + id)
}

/**
 * 搜索 api_param
 * @param params
 */
export function search(params) {
  return request({
    url: '/api/api_param_search',
    method: 'get',
    params
  })
}
