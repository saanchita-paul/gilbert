import CustomerSummary from "@scripts/models/CustomerSummary";
import ConversationSummary from "@scripts/models/ConversationSummary";
import InfoChart from "@scripts/models/InfoChart";
import SentimentSummary from "@scripts/models/SentimentSummary";

export default {
    toClientList(data) {
        const customerSummary = new CustomerSummary();
        const conversationSummary = new ConversationSummary();
        const infoChart = new InfoChart();
        const sentimentSummary = new SentimentSummary()

        customerSummary.total_customer = data.total_customer;
        customerSummary.total_user = data.total_user;
        let totalSentiment = 0;
        let sentiment = {
            neg: 0,
            neu: 0,
            pos: 0
        }

        data.summaries.map(item => {
            totalSentiment += (item.sentiment_negative_count + item.sentiment_neutral_count + item.sentiment_positive_count)
            sentiment.neg += item.sentiment_negative_count;
            sentiment.pos += item.sentiment_positive_count;
            sentiment.neu += item.sentiment_neutral_count;
        });

        if (totalSentiment > 0) {
            sentimentSummary.negative_count = ((sentiment.neg / totalSentiment) * 100) + '%'
            sentimentSummary.positive_count = ((sentiment.pos / totalSentiment) * 100) + '%'
            sentimentSummary.neutral_count = (sentiment.neu / totalSentiment) * 100 + '%'
        }


        return {
            customerSummary,
            sentimentSummary
        }
        /**
         * not using
         */
        data.forEach((item) => {
            customerSummary.total_customer = customerSummary.total_customer + item.total_customer;
            customerSummary.new_customer = customerSummary.new_customer + item.new_customer;
            customerSummary.active_customer =  customerSummary.active_customer + item.active_customer;
            customerSummary.engaged_customer = customerSummary.engaged_customer + item.engaged_customer;

            conversationSummary.total_message = conversationSummary.total_message + item.message_sent + item.message_received;
            conversationSummary.message_sent =  conversationSummary.message_sent + item.message_sent;
            conversationSummary.message_received = conversationSummary.message_received + item.message_received;

            infoChart.total_message.data.push(item.message_sent + item.message_received);
            infoChart.total_message.labels.push(item.date);
            infoChart.message_sent.data.push(item.message_sent);
            infoChart.message_sent.labels.push(item.date);
            infoChart.message_received.data.push(item.message_received);
            infoChart.message_received.labels.push(item.date);
        });

        return {
            customerSummary,
            conversationSummary,
            infoChart
        };
    },
};
