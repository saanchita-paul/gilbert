import axios from 'axios';
import DuplicateLeadMapper from "@scripts/api/mappers/crm/DuplicateLeadMapper";

const data = [
    {
        id: 1,
        first_name: 'Shakil',
        middle_name: '',
        last_name: 'Hossain',
        mobile: '0410758782',
        source: 'Lead',
        connection_address: '398 Bourke Road, Camberwell 3124 VIC',
        email: 'testemail@gmail.com',
    },
    {
        id: 2,
        first_name: 'First',
        middle_name: 'Middle',
        last_name: 'Last',
        mobile: '0410758700',
        source: 'Lead',
        connection_address: '398 Bourke Road, Camberwell 3124 VIC',
        email: 'testname@gmail.com',
    }
];

export default {

    getDuplicateLeadData: async () => {
        // const data = await axios.get(`/api/duplicate-lead`);

        return {
            data: DuplicateLeadMapper.mapDuplicateLeadList(data),
        };
    },

}
