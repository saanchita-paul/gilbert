import axios from 'axios';
import DuplicateLeadMapper from "@scripts/api/mappers/crm/DuplicateLeadMapper";

export default {

    getDuplicateLeadData: async (leadId) => {
        const data = (await axios.get(`/api/applications/${leadId}/duplicate`)).data;
        return {
            data: DuplicateLeadMapper.mapDuplicateLeadList(data),
        };
    },

}
