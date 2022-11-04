import axios from "axios";
import MRIMapper from "@scripts/api/mappers/crm/MRIMapper";

const getMriOfficeList = async () => {
    try {
        const data = await axios.get('/api/mri-offices');
        return MRIMapper.mapMriList(data.data);
    } catch (error) {
        console.log('Error from fetch Mri data');
        return error.data;
    }
}

export default {
    getMriOfficeList
}
