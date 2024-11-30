import axios from 'axios';

const axiosInstance = axios.create({
    baseURL: import.meta.env.VITE_API_URL ? `${import.meta.env.VITE_API_URL}` : undefined,
    headers: {
        Accept: 'application/json'
    }
});

export default axiosInstance;
