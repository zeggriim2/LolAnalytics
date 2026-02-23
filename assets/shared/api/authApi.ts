import { fetchApi } from './fetchApi'

export const authApi = {
  login: (email: string, password: string) =>
    fetchApi<{ token: string }>('/auth/login', {
      method: 'POST',
      body: JSON.stringify({ email, password }),
    }),
}
