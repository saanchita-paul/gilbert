import CrmUserMapper from "@scripts/api/mappers/crm/CrmUserMapper";
import AppMetricsMapper from "@scripts/api/mappers/crm/AppMetricsMapper";
import AppLeadMapper from "@scripts/api/mappers/crm/AppLeadMapper";
import ApplicationMapper from "@scripts/api/mappers/crm/ApplicationMapper";
import axios from "axios";

const data = [
    {
        id: 1,
        title: 'Applications',
        lead_count: 500,
        status: 'Total submitted',
    },
    {
        id: 2,
        title: 'Non-payable',
        lead_count: 100,
        status: 'Non Connected',
    },
    {
        id: 3,
        title: 'Power',
        lead_count: 300,
        status: 'Successfully connected',
    },
    {
        id: 4,
        title: 'Gas',
        lead_count: 200,
        status: 'Successfully connected',
    },
    {
        id: 5,
        title: 'Internet',
        lead_count: 200,
        status: 'Successfully connected',
    },
    {
        id: 6,
        title: 'Water',
        lead_count: 200,
        status: 'Successfully connected',
    },
];
const userData = [
    {
        id: 1,
        title: 'My Applications',
        lead_count: 500,
    },
    {
        id: 2,
        title: 'Unassigned',
        lead_count: 100,
    },
    {
        id: 3,
        title: 'Assigned',
        lead_count: 300,
    },
    {
        id: 4,
        title: 'Escalated',
        lead_count: 200,

    },
    {
        id: 5,
        title: 'Submitted',
        lead_count: 200,
    }
];

const applications = [
    {
        id: 1,
        applicant_name: 'Staedtler Marker',
        moving_date: '06/08/2021',
        phone: '0410758782',
        service_types: ['power', 'gas', 'internet'],
        status: 'Not Confirmed Connection',
    },
    {
        id: 2,
        applicant_name: 'Applicant Name goes here',
        moving_date: '06/08/2021',
        phone: '0410758782',
        service_types: ['power', 'gas', 'internet'],
        status: 'Not Confirmed Connection',
    },
    {
        id: 3,
        applicant_name: 'Applicant Name goes here',
        moving_date: '06/08/2021',
        phone: '0410758782',
        service_types: ['power', 'gas', 'internet'],
        status: 'Not Confirmed Connection',
    },
    {
        id: 4,
        applicant_name: 'Applicant Name goes here',
        moving_date: '06/08/2021',
        phone: '0410758782',
        service_types: ['power', 'gas', 'internet'],
        status: 'Not Confirmed Connection',
    },
];
const application = {
    id: 1,
    applicant_name: 'Staedtler Marker',
    date_of_birth: '06/08/2021',
    phone: '0410758782',
    email: 'staedtler.marker@gmail.com',
    moving_date: '02/22/2022',
    email_billing: 'Email/Paper',
    tenancy_type: 'Renter or Home Owner',
    service_address: '398 Bourke Road, Camberwell 3124 VIC',
    service_types: ['power', 'gas', 'internet'],
    additional_instruction: 'Additional Instructions goes here. Maybe it’s extra long so I have ' +
        'to write something down to show how it will look like when a property manager has soooo ' +
        'much things to say on his lead.'
}

const plans = [
    {
        id: 1,
        title: 'Total Plan (Home)',
        key: 'total_plan',
        active: true,
    },
    {
        id: 2,
        title: 'No Frills (Home)',
        key: 'basic_plan',
        active: false,
    },
    {
        id: 3,
        title: 'Basic Home',
        key: 'no_frills',
        active: false,
    },

];
const notes = [
    {
        id: 1,
        title: 'Note by John',
        created_at:  '00/00/2021_00:00:00',
        text: 'Called Cx. No Answer. Please Callback on 12/20 at 1pm.',
        active: true,
    },
    {
        id: 2,
        title: 'Note by John',
        created_at:  '00/00/2021_00:00:00',
        text: 'Called Cx. No Answer. Please Callback on 12/20 at 1pm.',
        active: false,
    },
    {
        id: 3,
        title: 'Note by John',
        created_at:  '00/00/2021_00:00:00',
        text: 'Called Cx. No Answer. Please Callback on 12/20 at 1pm.',
        active: false,
    },
    {
        id: 4,
        title: 'Note by John',
        created_at:  '00/00/2021_00:00:00',
        text: 'Called Cx. No Answer. Please Callback on 12/20 at 1pm.',
        active: false,
    },

];

const newNote = {
    id: 7,
    title: 'Note by John',
    created_at:  '00/00/2021_00:00:00',
    text: 'Called Cx. No Answer. Please Callback on 12/20 at 1pm.',
    active: false,
};

const serviceProvider = [
    {
        id: 1,
        logo: '/assets/images/SupplierLogo.png',
        title: 'EA'
    }


];

export default {

   async getMetrics(arg) {
        try {
            let agency_id = '';
            if(arg.agency_id) {
                agency_id = arg.agency_id;
            }
             const leads = await axios.get('/api/applications-metrics-count?agency_id='+agency_id);
            return AppMetricsMapper.mapAppMetricList(data, leads.data.data);

        } catch (error) {
            return error.data;N
        }
    },

   async getUserLeadMetrics() {
        try {
            const data = await axios.get('/api/applications-metrics');
            return AppMetricsMapper.mapUserMetLeads(data.data.data);

        } catch (error) {
            return error.data;
        }
    },

    async getUserLeads(sort_search_meta, active_lead_type) {
        try {
            const data = await axios.get('/api/applications',{params:{...sort_search_meta, active_lead_type}});
            return ApplicationMapper.mapApplicationList(data.data);

        } catch (error) {
            return error.data;
        }
    },

  async getUserLead (id) {
        try {
            const data = await axios.get('/api/applications/' + id);
            const response = ApplicationMapper.mapApplicationSummary(data.data.data);
            return response;

        } catch (error) {
            console.log('response2', error);
            return error.data;
        }
    },

    getPlan(serviceProvider) {
        try {
            // const data = await axios.get('/');
            return ApplicationMapper.mapPlans(plans);

        } catch (error) {
            return error.data;
        }
    },

   async getNote(id) {
        try {
            const data = await axios.get('/api/applications/'+id+'/notes');
            return ApplicationMapper.mapNotes(data.data.data);
        } catch (error) {

            return error.data;
        }
    },

    getServiceProvider(service) {
        try {
            // const data = await axios.get('/');
            return ApplicationMapper.mapServiceProvider(serviceProvider);

        } catch (error) {
            return error.data;
        }
    },

   async saveNote(newNote, leadId) {
        try {
            const data = await axios.post('/api/applications/'+leadId+'/notes',{...newNote});
            return ApplicationMapper.mapNote(data);

        } catch (error) {
            return error.data;
        }
    },
    eacalate(leadId) {
        try {
          //call
            return true;

        } catch (error) {
            return error.data;
        }
    },

   async saveLead(lead, leadId) {
        try {
            const data = await axios.post('/api/applications/'+leadId+'/submit',{lead});
            return data;

        } catch (error) {
            return error.data;
        }
    },

    async updateAddress(address, leadId) {
        try {
            return await axios.put('/api/applications/'+leadId+'/update-address',{address});
        } catch (error) {
            return error.data;
        }
    },

    async saveEscalateReason(reason, leadId) {
        try {
            const data = await axios.post('/api/applications/'+leadId+'/escalate',{reason:reason});

        } catch (error) {
            return error.data;
        }
    },
    async assignUser(leadId, id) {
        try {
            const data = await axios.post('/api/applications/'+leadId+'/assign',{hood_user_id: id});
            return ApplicationMapper.mapNote(data);

        } catch (error) {
            return error.data;
        }
    }

}
