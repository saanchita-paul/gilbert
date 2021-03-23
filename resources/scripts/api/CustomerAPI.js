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

                propertyProfileId: '1901',
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
                connectionReason: 'The quick brown fox jumps over the lazy dog. The quick brown fox jumps over the lazy dog. The quick brown fox jumps over the ....'
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
        // const data =(await axios.get(`${ROOT}/customers/${customerId}/local-business-search`)).data;
        const data = {
            data: [
                {
                    id: 1,
                    name: 'Sazzad Ahmed',
                    profilePic: 'https://fiverr-res.cloudinary.com/images/q_auto,f_auto/gigs/106438752/original/3c4d95e3604313ecca407541a45b6a58dcc67c5c/update-your-online-dating-profile-bio-to-get-you-more-matches.jpg',
                    lastInteractiveTime: '10m',
                    hoodUid: '#1671408574925',
                    messagerId: '#1671419574925',
                    email: 'sazzadahmed@gmail.com',
                    ph: '1671408219574925',
                },
                {
                    id: 2,
                    name: 'Sazzad Ahmed',
                    profilePic: 'https://fiverr-res.cloudinary.com/images/q_auto,f_auto/gigs/106438752/original/3c4d95e3604313ecca407541a45b6a58dcc67c5c/update-your-online-dating-profile-bio-to-get-you-more-matches.jpg',
                    lastInteractiveTime: '10m',
                    hoodUid: '#1671408574925',
                    messagerId: '#1671419574925',
                    email: 'sazzadahmed@gmail.com',
                    ph: '1671408219574925',
                },
                {
                    id: 3,
                    name: 'Sazzad Ahmed',
                    profilePic: 'https://fiverr-res.cloudinary.com/images/q_auto,f_auto/gigs/106438752/original/3c4d95e3604313ecca407541a45b6a58dcc67c5c/update-your-online-dating-profile-bio-to-get-you-more-matches.jpg',
                    lastInteractiveTime: '10m',
                    hoodUid: '#1671408574925',
                    messagerId: '#1671419574925',
                    email: 'sazzadahmed@gmail.com',
                    ph: '1671408219574925',
                },
                {
                    id: 4,
                    name: 'Sazzad Ahmed',
                    profilePic: 'https://fiverr-res.cloudinary.com/images/q_auto,f_auto/gigs/106438752/original/3c4d95e3604313ecca407541a45b6a58dcc67c5c/update-your-online-dating-profile-bio-to-get-you-more-matches.jpg',
                    lastInteractiveTime: '10m',
                    hoodUid: '#1671408574925',
                    messagerId: '#1671419574925',
                    email: 'sazzadahmed@gmail.com',
                    ph: '1671408219574925',
                },

            ],
        };
        return CustomerMapper.mapCustomerList(data.data);
    }

}
