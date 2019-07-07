import request from '@/utils/request'

export function getList(params) {
  // return request.get('/api/lines', params)
  return request({
    url: '/api/admin/system',
    method: 'get',
    params
  })
}

export function report(params) {
  return request({
    url: '/api/admin/report',
    method: 'get',
    params
  })
}
