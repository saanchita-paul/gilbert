import SentimentAPI from "@scripts/api/SentimentAPI";
import DateRange from "@scripts/models/DateRange";


export default {
    /**
     * @param {DateRange} dateRange
     * @returns {CustomerAnalytics}
     */
    getSentimentSummary: dateRange => SentimentAPI.getSentimentSummary(dateRange)
}
