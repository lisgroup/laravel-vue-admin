import request from '@/utils/request'

export function getList(params) {
  return request({
    url: '/api/nav',
    method: 'get',
    params
  })
}

export function postAdd(params) {
  return request.post('/api/nav', params)
}

export function edit(id) {
  return request.get('/api/nav/' + id)
}

export function postEdit(id, params) {
  return request.patch('/api/nav/' + id, params)
}

export function deleteAct(id) {
  return request.delete('/api/nav/' + id)
}

/**
 * 搜索 nav
 * @param params
 */
export function search(params) {
  return request({
    url: '/api/nav_search',
    method: 'get',
    params
  })
}
