import { ofetch } from 'ofetch'

const fetchApi = ofetch.create({
  baseURL: '/api/admin',
  onRequest({ options }) {
    const token = localStorage.getItem('admin_token')
    if (token) {
      const headers = new Headers(options.headers as HeadersInit)
      headers.set('Authorization', `Bearer ${token}`)
      options.headers = headers
    }
  },
  onResponseError({ response }) {
    if (response.status === 401 && window.location.pathname !== '/admin/login') {
      localStorage.removeItem('admin_token')
      window.location.href = '/admin/login'
    }
  },
})

export { fetchApi }
