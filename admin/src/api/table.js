import request from '@/utils/request'

export function getList(params) {
  return request({
    url: '/api/crontask',
    method: 'get',
    params
  })
}
