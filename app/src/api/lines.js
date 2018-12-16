import request from '@/utils/request'

export function getList(params) {
  // return request.get('/api/lines', params)
  return request({
    url: '/api/lines',
    method: 'get',
    params
  })
}

export function postAdd(params) {
  return request.post('/api/lines', params)
}

export function edit(id) {
  return request.get('/api/lines/' + id)
}

export function postEdit(id, params) {
  return request.patch('/api/lines/' + id, params)
}

export function deleteAct(id) {
  return request.delete('/api/lines/' + id)
}
