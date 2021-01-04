import axios from 'axios';
import authService from "@/service/auth_service";
import router from "@/router";

let config = {
    baseURL: `http://localhost/8080/api`
};

const httpClient = axios.create(config);

const authInterceptor = config => {
    config.headers.Authorization = `Bearer ${authService.getToken()}`;
    return config;
}

httpClient.interceptors.request.use(authInterceptor);

httpClient.interceptors.response.use(
    response => {
        return response;
    },
    error => {
        if(error.response.status === 401){
            router.push({name: 'Login'});
        }

        return Promise.reject(error);
    }
);

export default httpClient;