import ChatbotApi from "@scripts/api/ea/ChatbotApi";

export default {
    getNextBusinessDay: (state)=> ChatbotApi.getNextBusinessDay(state)
}
