import SentimentSummary from "@scripts/models/SentimentSummary";
import DateRange from "@scripts/models/DateRange";

export default {
    /**
     *  getting customer analytic from CB
     *
     * @param { DateRange } dateRange
     *
     * @returns {SentimentSummary}
     */
    getSentimentSummary: (dateRange) => {
        return new SentimentSummary({
            positive_count: 600,
            negative_count: 100,
            neutral_count: 300,
        })
    }
}
