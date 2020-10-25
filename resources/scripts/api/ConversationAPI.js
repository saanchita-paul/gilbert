import ConversationSummary from "@scripts/models/ConversationSummary";
import DateRange from "@scripts/models/DateRange";

export default {
    /**
     *  getting customer analytic from CB
     *
     * @param { DateRange } dateRange
     *
     * @returns {ConversationSummary}
     */
    getConversationSummary: (dateRange) => {
        return new ConversationSummary({
            total_message_count: '12,433',
            total_sent: '8,122',
            total_received: '3,237',
        })
    }
}
