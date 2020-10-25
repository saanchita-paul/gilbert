import ConversationAPI from "@scripts/api/ConversationAPI";
import DateRange from "@scripts/models/DateRange";


export default {
    /**
     * @param {DateRange} dateRange
     * @returns {CustomerAnalytics}
     */
    getConversationSummary: dateRange => ConversationAPI.getConversationSummary(dateRange)
}
