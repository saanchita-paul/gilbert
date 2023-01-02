import ChatbotApplicationApi from "@scripts/api/chatbot/ChatbotApplicationApi";

export default {
    saveNote: note => ChatbotApplicationApi.saveNote(note),
    updatePersonalDetails: (utilityId, data) => ChatbotApplicationApi.updatePersonalData(utilityId, data),
    updatePropertyDetails: (utilityId, data) => ChatbotApplicationApi.updatePropertyDetails(utilityId, data),
    updateIdDetails: (utilityId, data) => ChatbotApplicationApi.updateIdDetails(utilityId, data),
}
