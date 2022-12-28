import ChatbotApplicationApi from "@scripts/api/chatbot/ChatbotApplicationApi";

export default {
    saveNote: note => ChatbotApplicationApi.saveNote(note)
}
