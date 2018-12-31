import request from '@/utils/request'

export function getList(params) {
  return request({
    url: '/api/crontask',
    method: 'get',
    params
  })
}

export function postNewBus(params) {
  return request.post('/api/crontask', params)
}

export function editBus(id) {
  return request.get('/api/crontask/' + id)
}

export function postEditBus(id, params) {
  return request.patch('/api/crontask/' + id, params)
}

export function deleteTask(id) {
  return request.delete('/api/crontask/' + id)
}

/**
 * 搜索 bus_line
 * @param params
 */
export function getLine(params) {
  return request({
    url: '/api/bus_line_search',
    method: 'get',
    params
  })
}

/**
 * 提交数据
 * @param params
 * @returns {*}
 */
export function postCrontask(params) {
  return request.post('/api/postCrontask', params)
}

export function getBusLineList(params) {
  return request({
    url: '/api/bus_line_list',
    methods: 'get',
    params
  })
}
