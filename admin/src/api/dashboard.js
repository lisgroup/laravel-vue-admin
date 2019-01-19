import request from '@/utils/request'

export function getList(params) {
  // return request.get('/api/lines', params)
  return request({
    url: '/api/admin/system',
    method: 'get',
    params
  })
}
