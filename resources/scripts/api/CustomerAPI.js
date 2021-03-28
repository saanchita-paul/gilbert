import axios from 'axios';
import CustomerMapper from "@scripts/api/mappers/CustomerMapper";
import Customer from "@scripts/models/customer-profile/Customer";
import CustomerProperty from "@scripts/models/customer-profile/CustomerProperty";
import CustomerConnection from "@scripts/models/customer-profile/CustomerConnection";
import CustomerMovingInfo from "@scripts/models/customer-profile/CustomerMovingInfo";
import CustomerOrder from "@scripts/models/customer-profile/CustomerOrder";
import root from "lodash-es/_root";

const ROOT = `${process.env.MIX_BOT_ROOT_URL}/hood-dashboard/api`;

export default {
    /**
     *  getting all customer data from CB
     *
     * @returns {Object}
     */
    getAllCustomerData: async () => {
        try {
            const data = await axios.get(`${process.env.MIX_BOT_ROOT_URL}/hood-dashboard/api/customers/all`);
            console.log("RECEIVED ALL CUSTOMER DATA ", data);
            return data.data.data;
        } catch (error) {
            return error.data;
        }
    },
    /**
     * getting specified customer data from CB
     * @param customerId
     * @returns {Customer} || {Object}
     */
    getCustomerProfileData: async (customerId) => {
        try {
            const data = await axios.get(`${process.env.MIX_BOT_ROOT_URL}/hood-dashboard/api/customers/${customerId}`);
            console.log(`RECEIVED CUSTOMER ${customerId} DATA `, data);
            return CustomerMapper.toClientDetail(data.data.data);
        } catch (error) {
            return error.data;
        }
    },

    /**
     *
     * @param customerId
     * @returns {Promise<CustomerProperty>}
     */
    getPropertyInfo: async customerId => {
        const data =(await axios.get(`${ROOT}/customers/${customerId}/property-info`)).data;
        return CustomerMapper.mapPropertyInfo(data.data);
    },
    /**
     *
     * @param customerId
     * @returns {Promise<CustomerConnection>}
     */
    getConnectionInfo: async customerId => {
        const data =(await axios.get(`${ROOT}/customers/${customerId}/connection-info`)).data;
        return CustomerMapper.mapConnectionInfo(data.data);
    },
    /**
     *
     * @param customerId
     * @returns {Promise<CustomerMovingInfo>}
     */
    getMovingInfo: async customerId => {
        const data =(await axios.get(`${ROOT}/customers/${customerId}/moving-info`)).data;
        return CustomerMapper.mapMovingInfo(data.data);
    },
    /**
     *
     * @param customerId
     * @returns {Promise<CustomerOrder[]>}
     */
    getOrderDetails: async customerId => {
        const data =(await axios.get(`${ROOT}/customers/${customerId}/order-details`)).data;
        return CustomerMapper.mapOrderDetails(data.data);
    },

    /**
     *
     * @param customerId
     * @returns {Promise<CustomerOtherService[]>}
     */
    getLocalSearch: async customerId => {
        const data =(await axios.get(`${ROOT}/customers/${customerId}/local-business-search`)).data;
        return CustomerMapper.mapOtherServices(data.data);
    },

    getCustomerDetails: async customerId => {
        // Todo API End point will be replaced later
        // const data =(await axios.get(`${ROOT}/customers/${customerId}/local-business-search`)).data;
        // working with some dummy data
        const data = {
            data: {
                id: 2135,
                propertyProfileId: '100004406028484',
                propertyAccountType: 'Residensial',
                propertyLifeSupport: 'Y/N',
                propertyTenancyType: 'Rent',
                propertyIdType: 'Residensial',
                propertyEAResponseTime: '20/03/2020',
                propertySolarPowered: '20/03/2020',
                estimetedMovingPeriod: '20/03/2020',
                isManualAddress: 'Email/Address',
                userAgreeTime:'20/03/2020',

                connectionId: '1901',
                connectionProvider: 'Residensial',
                connectionAddress: '26 Highpoint, sunbury VIC 3429',
                connectionSelectedPlan: 'Total Plan',
                connectionEnergyType: '--',
                connectionGasProvider: 'Envesta Country',
                connectionEDestributor: 'Envesta Country',
                connectionElectricyFee: '$56',
                gasMeterCharge: '$56',
                connectionMernNo: 52456465454165,
                connectionNmiNo: 52456465454165,
                connectionStatus: 'Rejected',
                connectionReason: 'The quick brown fox jumps over the lazy dog. The quick brown fox jumps over the lazy dog. The quick brown fox jumps over the ....',
                manualInterventionStatus: 'in_progress',
                manualInterventionIsActive: false,
                manualInterventionAt: '2020-12-17 08:30:17'
            }
        }
        return CustomerMapper.mapCustomerDetailsServices(data.data);
    },

    /**
     *
     * @param pageIndex
     * @returns {Promise<CustomerListInfo[]>}
     */
    getCustomerList: async pageIndex=> {
        // Todo API End point will be replaced later
        const data =(await axios.get(`${BOT_API}/customers?page=${pageIndex}`)).data;
        // const data = {
        //     "data":[
        //         {
        //             "id": 3297,
        //             "hood_ui": "HD20000000031",
        //             "facebook_id": "1844377632272440",
        //             "title": "mr",
        //             "first_name": "Karan",
        //             "last_name": "Singh",
        //             "full_name": "Karan Singh",
        //             "email": "myself.karand33p@gmail.com",
        //             "phone": "0426904772",
        //             "avatar": "https://devbot.hood.ai/storage/profile/1844377632272440.jpg",
        //             "sentiment": "NEGATIVE",
        //             "last_interaction": "1 day ago",
        //             "manual_intervention_status": "no_issue",
        //             "connection_status": "",
        //             "state": "VIC",
        //             "connection_date": "2021-04-08 12:00:00",
        //             "billing_preference": "email"
        //         },
        //         {
        //             "id": 3296,
        //             "hood_ui": null,
        //             "facebook_id": "1671408219574925",
        //             "title": null,
        //             "first_name": "Dimuthu",
        //             "last_name": "Satharasinghe",
        //             "full_name": null,
        //             "email": null,
        //             "phone": null,
        //             "avatar": "https://devbot.hood.ai/storage/profile/1671408219574925.jpg",
        //             "sentiment": "NEGATIVE",
        //             "last_interaction": "2 days ago",
        //             "manual_intervention_status": "no_issue",
        //             "connection_status": "",
        //             "state": null,
        //             "connection_date": null,
        //             "billing_preference": null
        //         },
        //         {
        //             "id": 3295,
        //             "hood_ui": null,
        //             "facebook_id": "1632897196839988",
        //             "title": null,
        //             "first_name": "Tuhin",
        //             "last_name": "Bepari",
        //             "full_name": null,
        //             "email": null,
        //             "phone": null,
        //             "avatar": "https://devbot.hood.ai/storage/profile/1632897196839988.jpg",
        //             "sentiment": "POSITIVE",
        //             "last_interaction": "4 days ago",
        //             "manual_intervention_status": "no_issue",
        //             "connection_status": "",
        //             "state": null,
        //             "connection_date": null,
        //             "billing_preference": null
        //         },
        //         {
        //             "id": 3286,
        //             "hood_ui": null,
        //             "facebook_id": "2354808421203468",
        //             "title": "mr",
        //             "first_name": "Armin",
        //             "last_name": "Towfigh Nia",
        //             "full_name": "Christopher Hitchen",
        //             "email": null,
        //             "phone": "0492313568",
        //             "avatar": "https://devbot.hood.ai/storage/profile/2354808421203468.jpg",
        //             "sentiment": "POSITIVE",
        //             "last_interaction": "1 week ago",
        //             "manual_intervention_status": "no_issue",
        //             "connection_status": "",
        //             "state": "VIC",
        //             "connection_date": "2021-06-01 12:00:00",
        //             "billing_preference": null
        //         },
        //         {
        //             "id": 3285,
        //             "hood_ui": null,
        //             "facebook_id": "3158729210922928",
        //             "title": "mr",
        //             "first_name": "Tommy",
        //             "last_name": "Fraser",
        //             "full_name": null,
        //             "email": null,
        //             "phone": null,
        //             "avatar": "https://devbot.hood.ai/storage/profile/3158729210922928.jpg",
        //             "sentiment": "NEUTRAL",
        //             "last_interaction": "1 week ago",
        //             "manual_intervention_status": "no_issue",
        //             "connection_status": "",
        //             "state": "VIC",
        //             "connection_date": "2021-03-19 12:00:00",
        //             "billing_preference": null
        //         },
        //         {
        //             "id": 3280,
        //             "hood_ui": null,
        //             "facebook_id": "1929729513751361",
        //             "title": null,
        //             "first_name": "LeNin",
        //             "last_name": "Sheikh",
        //             "full_name": null,
        //             "email": null,
        //             "phone": null,
        //             "avatar": "https://devbot.hood.ai/storage/profile/1929729513751361.jpg",
        //             "sentiment": "NEUTRAL",
        //             "last_interaction": "1 week ago",
        //             "manual_intervention_status": "no_issue",
        //             "connection_status": "",
        //             "state": null,
        //             "connection_date": null,
        //             "billing_preference": null
        //         },
        //         {
        //             "id": 3268,
        //             "hood_ui": null,
        //             "facebook_id": "2007440619297864",
        //             "title": null,
        //             "first_name": "Md.",
        //             "last_name": "Rahman",
        //             "full_name": null,
        //             "email": null,
        //             "phone": null,
        //             "avatar": "https://devbot.hood.ai/storage/profile/2007440619297864.jpg",
        //             "sentiment": "POSITIVE",
        //             "last_interaction": "1 week ago",
        //             "manual_intervention_status": "no_issue",
        //             "connection_status": "",
        //             "state": "VIC",
        //             "connection_date": "2021-04-01 00:00:00",
        //             "billing_preference": null
        //         },
        //         {
        //             "id": 3144,
        //             "hood_ui": null,
        //             "facebook_id": "1739095172832681",
        //             "title": "mr",
        //             "first_name": "Karan",
        //             "last_name": "Singh",
        //             "full_name": "Karandeep Singh",
        //             "email": "karandeep@hood.ai",
        //             "phone": "0490154053",
        //             "avatar": "https://devbot.hood.ai/storage/profile/1739095172832681.jpg",
        //             "sentiment": "POSITIVE",
        //             "last_interaction": "1 month ago",
        //             "manual_intervention_status": "no_issue",
        //             "connection_status": "",
        //             "state": "VIC",
        //             "connection_date": "2021-03-04 12:00:00",
        //             "billing_preference": null
        //         },
        //         {
        //             "id": 3029,
        //             "hood_ui": null,
        //             "facebook_id": "4527867930621611",
        //             "title": null,
        //             "first_name": "Karandeep",
        //             "last_name": "Singh",
        //             "full_name": null,
        //             "email": null,
        //             "phone": null,
        //             "avatar": "https://devbot.hood.ai/storage/profile/4527867930621611.jpg",
        //             "sentiment": "POSITIVE",
        //             "last_interaction": "2 months ago",
        //             "manual_intervention_status": "no_issue",
        //             "connection_status": "",
        //             "state": "VIC",
        //             "connection_date": "2021-02-04 12:00:00",
        //             "billing_preference": null
        //         },
        //         {
        //             "id": 2213,
        //             "hood_ui": null,
        //             "facebook_id": "1833131173399465",
        //             "title": null,
        //             "first_name": "Ronald",
        //             "last_name": "Saha",
        //             "full_name": null,
        //             "email": null,
        //             "phone": null,
        //             "avatar": "https://devbot.hood.ai/storage/profile/1833131173399465.jpg",
        //             "sentiment": "POSITIVE",
        //             "last_interaction": "1 year ago",
        //             "manual_intervention_status": "no_issue",
        //             "connection_status": "",
        //             "state": "NY",
        //             "connection_date": "2020-03-12 00:00:00",
        //             "billing_preference": null
        //         },
        //         {
        //             "id": 2179,
        //             "hood_ui": null,
        //             "facebook_id": "1685986481517141",
        //             "title": null,
        //             "first_name": "Molly",
        //             "last_name": "Gordon",
        //             "full_name": null,
        //             "email": null,
        //             "phone": null,
        //             "avatar": "https://devbot.hood.ai/storage/profile/1685986481517141.jpg",
        //             "sentiment": "POSITIVE",
        //             "last_interaction": "1 year ago",
        //             "manual_intervention_status": "no_issue",
        //             "connection_status": "",
        //             "state": "CA",
        //             "connection_date": "2020-03-08 00:00:00",
        //             "billing_preference": null
        //         },
        //         {
        //             "id": 1356,
        //             "hood_ui": null,
        //             "facebook_id": "2252544044786378",
        //             "title": null,
        //             "first_name": "Owen",
        //             "last_name": "Martin",
        //             "full_name": null,
        //             "email": null,
        //             "phone": null,
        //             "avatar": "https://devbot.hood.ai/storage/profile/2252544044786378.jpg",
        //             "sentiment": "NEUTRAL",
        //             "last_interaction": "2 years ago",
        //             "manual_intervention_status": "no_issue",
        //             "connection_status": "",
        //             "state": null,
        //             "connection_date": null,
        //             "billing_preference": null
        //         },
        //         {
        //             "id": 1062,
        //             "hood_ui": null,
        //             "facebook_id": "1885814831438265",
        //             "title": null,
        //             "first_name": "Casper",
        //             "last_name": "Lai",
        //             "full_name": null,
        //             "email": null,
        //             "phone": null,
        //             "avatar": "https://devbot.hood.ai/storage/profile/1885814831438265.jpg",
        //             "sentiment": "NEUTRAL",
        //             "last_interaction": "2 years ago",
        //             "manual_intervention_status": "no_issue",
        //             "connection_status": "",
        //             "state": null,
        //             "connection_date": null,
        //             "billing_preference": null
        //         },
        //         {
        //             "id": 1017,
        //             "hood_ui": null,
        //             "facebook_id": "1851139028286225",
        //             "title": null,
        //             "first_name": "Didar",
        //             "last_name": "Islam",
        //             "full_name": null,
        //             "email": null,
        //             "phone": null,
        //             "avatar": "https://devbot.hood.ai/storage/profile/1851139028286225.jpg",
        //             "sentiment": "NEUTRAL",
        //             "last_interaction": "2 years ago",
        //             "manual_intervention_status": "no_issue",
        //             "connection_status": "",
        //             "state": null,
        //             "connection_date": "2018-08-30 00:00:00",
        //             "billing_preference": null
        //         },
        //         {
        //             "id": 965,
        //             "hood_ui": null,
        //             "facebook_id": "1815531175205603",
        //             "title": null,
        //             "first_name": "Michelle",
        //             "last_name": "Jones",
        //             "full_name": null,
        //             "email": null,
        //             "phone": null,
        //             "avatar": "https://devbot.hood.ai/storage/profile/1815531175205603.jpg",
        //             "sentiment": "NEUTRAL",
        //             "last_interaction": "2 years ago",
        //             "manual_intervention_status": "no_issue",
        //             "connection_status": "",
        //             "state": null,
        //             "connection_date": "2018-08-27 00:00:00",
        //             "billing_preference": null
        //         } ],
        //     "pagination":{
        //         "total":771,
        //         "currentPage":1,
        //         "perPage":5,
        //         "nextPage":"http:\/\/127.0.0.1:8000\/sup?page=2",
        //         "previousPage":"http:\/\/127.0.0.1:8000\/sup?page=2",
        //         "hasMorePages":true
        //     },
        // };

        return {
            data: CustomerMapper.mapCustomerList(data.data),
            totalCount :data.pagination ? data.pagination.total: 0,
            page: data.pagination ? data.pagination.currentPage: 1,
            pageCount: data.pagination ? Math.ceil(data.pagination.total / data.pagination.perPage): 1,
            itemsPerPage: data.pagination ? data.pagination.perPage : 0,
            total: data.pagination ? data.pagination.total : 0
        };
    },

    /**
     *
     * @param customerId
     * @param manualInterventionStatus
     */
     toggleManualIntervention: async (customerId, manualInterventionStatus) => {
        const response = (await axios.get(`${ROOT}/utility/${'2179'}/manual-intervention`, {
            params: {
                manualInterventionStatus: manualInterventionStatus
            }
            // manualInterventionStatus: manualInterventionStatus
        }));
        return response;
    },

}
