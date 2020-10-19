import axios from 'axios'

export default {
    login: form => {
        axios.get('/sanctum/csrf-cookie').then(async response => {
            const res = await axios.post('/login', form);
            console.log(res)
        });
    }
}
