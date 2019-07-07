import request from '@/utils/request'

export function getList(params) {
  // return request.get('/api/api_excel', params)
  return request({
    url: '/api/api_excel',
    method: 'get',
    params
  })
}

export function postAdd(params) {
  return request.post('/api/api_excel', params)
}

export function edit(id) {
  return request.get('/api/api_excel/' + id)
}

export function postEdit(id, params) {
  return request.patch('/api/api_excel/' + id, params)
}

export function deleteAct(id) {
  return request.delete('/api/api_excel/' + id)
}

/**
 * 搜索 api_excel
 * @param params
 */
export function search(params) {
  return request({
    url: '/api/api_excel_search',
    method: 'get',
    params
  })
}

export function startTask(params) {
  return request({
    url: '/api/start_task',
    method: 'post',
    params
  })
}

export function download_log(params) {
  return request({
    url: '/api/download_log',
    method: 'post',
    params
  })
}
