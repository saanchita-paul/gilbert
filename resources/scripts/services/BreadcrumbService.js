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
        else if(type === 'AgencyOffices') {
            let agencyId = params.id;
            let data = await AgencyService.loadAgencyById(agencyId);
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
        else if(type === 'AgencyUsers') {
            let agencyId = params.id;
            let officeId = params.officeId;
            let agencyData = await AgencyService.loadAgencyById(agencyId);
            let officeData = await OfficeService.loadOnlyOfficeById(officeId);
            breadcrumb = [
                {
                    text: 'Agencies',
                    to: 'real.state.agency.home'
                },
                {
                    text: agencyData.title,
                    to: 'real.state.agency.office'
                },
                {
                    text: officeData.name,
                    to: 'real.state.agency.users'
                }
            ]
        } else if(type === 'OfficeProfile') {
            let agencyId = params.id;
            let officeId = params.officeId;
            let agencyData = await AgencyService.loadAgencyById(agencyId);
            let officeData = await OfficeService.loadOnlyOfficeById(officeId);
            breadcrumb = [
                {
                    text: 'Agencies',
                    to: 'real.state.agency.home'
                },
                {
                    text: agencyData.title,
                    to: 'real.state.agency.office'
                },
                {
                    text: officeData.name,
                    to: 'real.state.office.profile'
                }
            ]
        } else if(type === 'LeadApplications') {
            breadcrumb = [
                {
                    text: 'Applications',
                    to: 'applications'
                }
            ]
        } else if(type === 'ApplicationsDetails') {
            let applicationId = params.id;
            breadcrumb = [
                {
                    text: 'Applications',
                    to: 'applications'
                },
                {
                    text: 'Application Details',
                    to: 'applications.details'
                }
            ]
        } else if(type === 'ApplicationsDashboard') {
            breadcrumb = [
                {
                    text: 'Applications Dashboard',
                    to: 'applications.dashboard'
                }
            ]
        }
        Store.commit('addBreadcrumb', breadcrumb)
    }
}
