export default class InfoChart {
    constructor({total_message, message_sent, message_received } = {}) {
        this.total_message = total_message || {
            data: [],
            labels: []
        };
        this.message_sent = message_sent || {
            data: [],
            labels: []
        };
        this.message_received = message_received || {
            data: [],
            labels: []
        };
    }
}
