import axios from "axios";
import ChatbotApplicationMapper from "@scripts/api/mappers/ChatbotApplicationMapper";
import ApplicationMapper from "@scripts/api/mappers/crm/ApplicationMapper";
import ApplicationCafFileMapper from "@scripts/api/mappers/crm/ApplicationCafFileMapper";
import Store from "@scripts/store";

const ROOT = `${process.env.MIX_BOT_ROOT_URL}/hood-dashboard/api`;

export default {
    saveNote: async note => {
        try {
            const user = Store.getters.user;
            return (await axios.post(`${ROOT}/application-note`, {...note,
                created_by: user.profile.first_name,
                 user_role: user.roles[0],
                 type: 'regular',})).data;
        } catch (error) {
            console.log('error', error);
            return null;
        }
    },

   async updatePersonalData(utilityId, data, provider_name) {
        try {
            const mappedData = ChatbotApplicationMapper.mapTosavePersonalData(data, provider_name);
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

    async saveServiceStatus(id, eleService = null, gasService = null, caf_status = null) {
        try {
            const user = Store.getters.user;
            const mappedData =  ChatbotApplicationMapper.mapToUpdateServiceStatus(eleService, gasService, caf_status);
            mappedData.note = ChatbotApplicationMapper.mapNoteData(user, id);
            const response = await axios.post(`${BOT_API}/utility-data/${id}/service-status`,  mappedData);
            return response.data;
        } catch (error) {
            console.log('error', error);
            return null;
        }
    },

    async updatePropertyAddress(utilityId, address){
        try {
            const mappedData = ChatbotApplicationMapper.mapToUpdateAddress(address);
            const response = await axios.post(`${ROOT}/${utilityId}/utility-address`, {...mappedData});
            return response.data;
        } catch (error) {
            console.log('error', error);
            return null;
        }
    }

}
