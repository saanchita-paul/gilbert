import axios from 'axios';
import DuplicateLeadMapper from "@scripts/api/mappers/crm/DuplicateLeadMapper";

export default {

    getDuplicateLeadData: async (duplicateGroupId) => {
        const data = (await axios.get(`/api/applications/${duplicateGroupId}/duplicate`)).data;
        return {
            data: DuplicateLeadMapper.mapDuplicateLeadList(data),
        };
    },

}
