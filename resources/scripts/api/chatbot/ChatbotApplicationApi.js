import axios from "axios";
import ChatbotApplicationMapper from "@scripts/api/mappers/ChatbotApplicationMapper";

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
            return (await axios.put(`${ROOT}/utility-data/${utilityId}/update`, {...mappedData})).data;
        } catch (error) {
            console.log('error', error);
            return null;
        }
    },
    async updatePropertyDetails(utilityId, data) {
        try {
            const mappedData = ChatbotApplicationMapper.mapTosavePropertyData(data);
            return (await axios.put(`${ROOT}/utility-data/${utilityId}/update`, {...mappedData})).data;
        } catch (error) {
            console.log('error', error);
            return null;
        }
    },
    async updateIdDetails(data) {
        try {
            return (await axios.post(`${ROOT}/application-note`, note)).data;
        } catch (error) {
            console.log('error', error);
            return null;
        }
    },

}
