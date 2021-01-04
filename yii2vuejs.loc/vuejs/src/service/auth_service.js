import axios from 'axios';

// eslint-disable-next-line no-unused-vars
const authService = {
    user: null,
    async login(formData){
        try{
            // eslint-disable-next-line no-unused-vars
            const {status, data} = await axios.post('http://localhost:8080/api/users/login', formData);
            if(status == 200)
            {
                this.setUser(data);
                return {success: true};
            }
        }catch(e){
            return {
                success: false,
                errors: e.response.data.errors
            }
        }
    },
    setUser(user){
        this.user = user;
        localStorage.setItem('ACCESS_TOKEN', user.access_token);
    },
    isLoggedIn(){
        return !!localStorage.getItem('ACCESS_TOKEN');
    },
    getToken(){
        return localStorage.getItem('ACCESS_TOKEN');
    }
}

export default authService;