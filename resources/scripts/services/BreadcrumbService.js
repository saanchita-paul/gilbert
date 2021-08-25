import Store from '@scripts/store/index';
import router from '@scripts/routes/router';
import OfficeService from "@scripts/services/crm/OfficeService";
import AgencyService from "@scripts/services/crm/AgencyService";

export default {
    setBreadcrumb: async (type,params) => {
        let breadcrumb = null;
        if(type === 'AgencyList') {
            breadcrumb = [
                    {
                        text: 'Agencies',
                        to: 'real.state.agency.home'
                    }
                ]
        }
        else if(type === 'AgencyOffices'){
            let officeId = params.id;
            let data = await AgencyService.loadAgencyById(officeId);
            console.log('df', data);
            breadcrumb = [
                {
                    text: 'Agencies',
                    to: 'real.state.agency.home'
                },
                {
                    text: data.title,
                    to: 'real.state.agency.office'
                }
            ]
        }
        Store.commit('addBreadcrumb', breadcrumb)
    }
}
