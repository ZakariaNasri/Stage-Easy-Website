import axios from 'axios';

// Set the base URL for your API
axios.defaults.baseURL = 'http://127.0.0.1:8000/api';

// Get the token from local storage
const token = localStorage.getItem('token');

if (token) {
  // Set the Authorization header for every request if a token exists
  axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
  
}

// Add an interceptor to add the Authorization header to every request
axios.interceptors.request.use(
  function(config) {
    const token = localStorage.getItem('token');

    if (token) {
      config.headers.Authorization = `Bearer ${token}`;
    }

    return config;
  },
  function(error) {
    return Promise.reject(error);
  }
);


export default axios;
