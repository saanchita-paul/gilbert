import axios from "axios";
import ChatbotApplicationMapper from "@scripts/api/mappers/ChatbotApplicationMapper";
import ApplicationMapper from "@scripts/api/mappers/crm/ApplicationMapper";
import ApplicationCafFileMapper from "@scripts/api/mappers/crm/ApplicationCafFileMapper";

const ROOT = `${process.env.MIX_BOT_ROOT_URL}/hood-dashboard/api`;

export default {
    saveNote: async note => {
        try {
            return (await axios.post(`${ROOT}/application-note`, note)).data;
        } catch (error) {
            console.log('error', error);
            return null;
        }
    },

   async updatePersonalData(utilityId, data) {
        try {
            const mappedData = ChatbotApplicationMapper.mapTosavePersonalData(data);
            const response =  (await axios.put(`${ROOT}/utility-data/${utilityId}/update`,
                {...mappedData})).data;
            return ApplicationCafFileMapper.mapChatbotSingleApplication(response.data);
        } catch (error) {
            console.log('error', error);
            return null;
        }
    },
    async updatePropertyDetails(utilityId, data) {
        try {
            const mappedData = ChatbotApplicationMapper.mapTosavePropertyData(data);
            const response = (await axios.put(`${ROOT}/utility-data/${utilityId}/update`, {...mappedData})).data;
            return ApplicationCafFileMapper.mapChatbotSingleApplication(response.data);
        } catch (error) {
            console.log('error', error);
            return null;
        }
    },
    async updateIdDetails(utilityId, data) {
        try {
            const mappedData = ChatbotApplicationMapper.mapTosaveIdData(data);
            const response = (await axios.put(`${ROOT}/utility-data/${utilityId}/update`, {...mappedData})).data;
            return ApplicationCafFileMapper.mapChatbotSingleApplication(response.data);
        } catch (error) {
            console.log('error', error);
            return null;
        }
    },

    async updateConcessionDetails(utilityId, data) {
        try {
            const mappedData = ChatbotApplicationMapper.mapTosaveConcessionData(data);
            console.log('mapData', mappedData);
            const response = (await axios.put(`${ROOT}/utility-data/${utilityId}/update`, {...mappedData})).data;
            console.log('response', response);
            return ApplicationCafFileMapper.mapChatbotSingleApplication(response.data);
        } catch (error) {
            console.log('error', error);
            return null;
        }
    },

    async saveServiceStatus(serviceId, data) {
        try {
            const response = await axios.post(`${BOT_API}/service-status/${serviceId}`,  data);
            return response.data;
        } catch (error) {
            console.log('error', error);
            return null;
        }
    },

    async updatePropertyAddress(utilityId, address){
        try {
            const mappedData = ChatbotApplicationMapper.mapToUpdateAddress(address);
            const response = (await axios.post(`${ROOT}/${utilityId}/utility-address`, {...mappedData})).data;
            // return ApplicationCafFileMapper.mapChatbotSingleApplication(response.data);
            return response;
        } catch (error) {
            console.log('error', error);
            return null;
        }
    }

}
