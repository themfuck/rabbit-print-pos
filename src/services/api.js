import axios from 'axios';

const API_BASE_URL = import.meta.env.VITE_API_BASE_URL || 'http://localhost:8000/api';

const apiClient = axios.create({
  baseURL: API_BASE_URL,
  withCredentials: true, // Important for handling cookies with Sanctum
});

/*
// Example of a request interceptor for CSRF token handling.
// Often, an initial call to a '/sanctum/csrf-cookie' endpoint handles this.
// If you make an initial call to fetch the CSRF cookie (e.g., in your main App component
// or before any authenticated requests), this explicit interceptor might not be needed
// as Axios will automatically handle the XSRF-TOKEN cookie.

apiClient.interceptors.request.use(request => {
  // Axios automatically handles XSRF-TOKEN if the cookie is set by the backend
  // and the `withCredentials` option is true.
  // This is a common setup for Laravel Sanctum.
  // If you need to manually fetch and set the token (less common with Sanctum),
  // you might do something like this:
  // const token = document.cookie.split('; ').find(row => row.startsWith('XSRF-TOKEN='))?.split('=')[1];
  // if (token) {
  //   request.headers['X-XSRF-TOKEN'] = decodeURIComponent(token);
  // }
  return request;
}, error => {
  return Promise.reject(error);
});

// Example of how to make an initial call to get the CSRF cookie
// You might do this in your main App component or an initialization script.
//
// export const getCsrfCookie = async () => {
//   try {
//     await apiClient.get('/sanctum/csrf-cookie');
//     console.log('CSRF cookie fetched');
//   } catch (error) {
//     console.error('Error fetching CSRF cookie:', error);
//   }
// };
*/

export default apiClient;
