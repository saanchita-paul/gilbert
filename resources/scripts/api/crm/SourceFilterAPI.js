import axios from "axios";
import SourceFilterMapper from "@scripts/api/mappers/crm/SourceFilterMapper";

const getSourceList = async () => {
    try {
        const data = await axios.get('/api/sources');
        return SourceFilterMapper.mapSourceList(data.data);
    } catch (error) {
        console.log('Error from Source filter API data');
        return error.data;
    }
}

export default {
    getSourceList
}
