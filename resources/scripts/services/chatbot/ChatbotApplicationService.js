import ChatbotApplicationApi from "@scripts/api/chatbot/ChatbotApplicationApi";
import Store from '@scripts/store/index';
import axios from "axios";
export default {
    saveNote: note => ChatbotApplicationApi.saveNote(note),
    updatePersonalDetails: async (utilityId, data) => {
        const application = await ChatbotApplicationApi.updatePersonalData(utilityId, data);
        console.log (application);
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


    saveServiceStatus: async (service) => {
      return  await ChatbotApplicationApi.saveServiceStatus(service.id, {status: service.status});
    }

}
