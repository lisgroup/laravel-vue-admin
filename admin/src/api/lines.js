import request from '@/utils/request'

export function getList(params) {
  return request({
    url: '/api/lines',
    method: 'get',
    params
  })
}

export function postAdd(params) {
  return request({
    url: '/api/lines',
    method: 'post',
    params
  })
}

export function edit(id) {
  return request({
    url: '/api/lines/' + id,
    method: 'get'
  })
}

export function postEdit(id, params) {
  return request({
    url: '/api/lines/' + id,
    method: 'patch',
    params
  })
}

export function deleteAct(id) {
  return request({
    url: '/api/lines/' + id,
    method: 'delete'
  })
}
