import InternetProviders from "@scripts/modules/internet/constants/InternetProviders";
import axios from "axios";
import ApplicationMapper from "@scripts/api/mappers/crm/ApplicationMapper";

const getProviderAndPlan = () => {
    try {
        return InternetProviders.map(provider => provider) || [];
    } catch (error) {
        console.log("Error", error);
    }
}

const updateInternetServiceInfo = async (data, leadId) => {
    try {
        const response = await axios.put('/api/applications/' + leadId + '/update-internet-service-info', data);
        return ApplicationMapper.mapApplication(response.data.data);
    } catch (error) {
        return error.data;
    }
}

const updateNbnProviderInfo = async (data, leadId) => {
    try {
        const response = (await axios.put('/api/applications/' + leadId + '/update-nbn-provider', data)).data;
        return response.data;
    } catch (error) {
        return error.data;
    }
}

const NBNSubmit = async (data, leadId) => {
    try {
        const response = (await axios.post('/api/applications/' + leadId + '/nbn-submit', data)).data;
        return response.data;
    } catch (error) {
        return error.data;
    }
}

export default {
    getProviderAndPlan,
    updateInternetServiceInfo,
    updateNbnProviderInfo,
    NBNSubmit
};
