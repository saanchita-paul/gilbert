import ChatbotApplicationApi from "@scripts/api/chatbot/ChatbotApplicationApi";
import Store from '@scripts/store/index';
import axios from "axios";
import utilityAPI from "@scripts/api/UtilityAPI";
export default {
    saveNote: note => ChatbotApplicationApi.saveNote(note),
    updatePersonalDetails: async (utilityId, data, provider_name) => {
        const application = await ChatbotApplicationApi.updatePersonalData(utilityId, data, provider_name);
        Store.commit('updateApp', application);
    },

    updatePropertyDetails: async (utilityId, data) => {
        const application =  await ChatbotApplicationApi.updatePropertyDetails(utilityId, data);
        Store.commit('updateApp', application);

    },
    updateIdDetails: async (utilityId, data) =>
    {
        const application =  await ChatbotApplicationApi.updateIdDetails(utilityId, data);
        Store.commit('updateApp', application);
    },

    updateConcessionDetails: async (utilityId, data) =>
    {
        const application =  await ChatbotApplicationApi.updateConcessionDetails(utilityId, data);
        Store.commit('updateApp', application);
    },


    saveServiceStatus: async (chatbot_app) => {
      return  await ChatbotApplicationApi.saveServiceStatus(chatbot_app.id, chatbot_app.eleService, chatbot_app.gasService, chatbot_app.cafStatus);
    },

    updatePropertyAddress: async (utilityId, address) => {
        await ChatbotApplicationApi.updatePropertyAddress(utilityId, address)

    }


}
