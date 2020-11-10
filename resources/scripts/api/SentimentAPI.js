import SentimentSummary from "@scripts/models/SentimentSummary";
import DateRange from "@scripts/models/DateRange";
import axios from 'axios';

export default {
    /**
     *  getting customer analytic from CB
     *
     * @param { DateRange } dateRange
     *
     * @returns {SentimentSummary}
     */
    getSentimentSummary: async (dateRange) => {
        try {
            console.log("ABOUT TO CALL SENTIMENT SUMMARY API ", dateRange);

            const data = await axios.get(`${process.env.MIX_BOT_ROOT_URL}/hood-dashboard/api/sentiment-status`, {
                params: { ...dateRange }
            });
            console.log("RECEIVED SENTIMENT STATUS ", data);
            return data.data;
        } catch (error) {
            return error.data;
        }
        return new SentimentSummary({
            positive_count: 600,
            negative_count: 100,
            neutral_count: 300,
        })
    }
}
