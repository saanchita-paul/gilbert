export default class ConversationSummary {
    constructor({total_message_count, total_sent, total_received} = {}) {
        this.total_message_count = total_message_count || 0;
        this.total_sent = total_sent || 0;
        this.total_received = total_received || 0;
    }
}
