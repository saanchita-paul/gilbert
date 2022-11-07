import axios from "axios";

const getMriOfficeList = async () => {
    try {
        const data = await axios.get('/api/mri-offices');
        return data.data;
    } catch (error) {
        console.log('Error from fetch Mri data');
        return error.data;
    }
}

export default {
    getMriOfficeList
}
