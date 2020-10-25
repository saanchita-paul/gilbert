import CustomerSummary from "@scripts/models/CustomerSummary";
import ConversationSummary from "@scripts/models/ConversationSummary";

export default {
    toClientList(data) {
        const customerSummary = new CustomerSummary();
        const conversationSummary = new ConversationSummary();
        data.forEach((item) => {
           customerSummary.total_customer = customerSummary.total_customer + item.total_customer;
           customerSummary.new_customer = customerSummary.new_customer + item.new_customer;
           customerSummary.active_customer =  customerSummary.active_customer + item.active_customer;
           customerSummary.engaged_customer = customerSummary.engaged_customer + item.engaged_customer;

           conversationSummary.total_message = conversationSummary.total_message + item.message_sent + item.message_received;
           conversationSummary.message_sent =  conversationSummary.message_sent + item.message_sent;
           conversationSummary.message_received = conversationSummary.message_received + item.message_received;
        });
        return {
            customerSummary,
            conversationSummary
        };
    },
};