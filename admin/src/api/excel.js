import request from '@/utils/request'

export function getList(params) {
  // return request.get('/api/excel', params)
  return request({
    url: '/api/excel',
    method: 'get',
    params
  })
}

export function postAdd(params) {
  return request.post('/api/excel', params)
}

export function edit(id) {
  return request.get('/api/excel/' + id)
}

export function postEdit(id, params) {
  return request.patch('/api/excel/' + id, params)
}

export function deleteAct(id) {
  return request.delete('/api/excel/' + id)
}

/**
 * 搜索 excel
 * @param params
 */
export function search(params) {
  return request({
    url: '/api/excel_search',
    method: 'get',
    params
  })
}
