import request from '@/utils/request'

export function getList(params) {
  return request({
    url: '/api/crontask',
    method: 'get',
    params
  })
}

export function postNewBus(params) {
  return request({
    url: '/api/crontask',
    method: 'post',
    params
  })
}

export function deleteTask(id) {
  return request({
    url: '/api/crontask/' + id,
    method: 'delete'
  })
}
