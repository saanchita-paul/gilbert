export default class CustomerAnalytics {
    constructor({new_user_count, messages_sent, messages_received, male_percent, female_percent} = {}) {
        this.new_user_count = new_user_count || 0;
        this.messages_sent = messages_sent || 0;
        this.messages_received = messages_received || 0;
        this.male_percent = male_percent || 0;
        this.female_percent = female_percent || 0;
    }
}
