import axios from 'axios';
import router from "../routes.js";
// Создадим новый instance axios
const api = axios.create({
    baseURL: 'http://127.0.0.1:8000/api/v1', // Укажите базовый URL вашего API
    headers: {
        'Content-Type': 'application/json'
    }
});

// Добавим interceptor для добавления токена в заголовок каждого запроса
api.interceptors.request.use(config => {
    const token = localStorage.getItem('authToken');
    console.log(token);
    if (token) {
        config.headers['Authorization'] = `Bearer ${token}`;
    }
    return config;
}, error => {
    return Promise.reject(error);
});


api.interceptors.response.use(
    response => response,
    error => {
        if (error.response.status === 401) {
            localStorage.removeItem('authToken');
            console.log('401');

            router.push('/login');
        }
        return Promise.reject(error);
    }
)


export default api;