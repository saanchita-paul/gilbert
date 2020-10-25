export default class ConversationSummary {
    constructor({total_message, message_sent, message_received } = {}) {
        this.total_message = total_message || 0;
        this.message_sent = message_sent || 0;
        this.message_received = message_received || 0;
    }
}
