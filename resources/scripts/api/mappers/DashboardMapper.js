import CustomerSummary from "@scripts/models/CustomerSummary";
import ConversationSummary from "@scripts/models/ConversationSummary";
import InfoChart from "@scripts/models/InfoChart";

export default {
    toClientList(data) {
        const customerSummary = new CustomerSummary();
        const conversationSummary = new ConversationSummary();
        const infoChart = new InfoChart();

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