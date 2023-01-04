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

}
