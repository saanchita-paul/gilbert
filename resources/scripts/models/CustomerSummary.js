export default class CustomerSummary {
    constructor({total_customer, new_customer, active_customer, total_user, engaged_customer} = {}) {
        this.total_customer = total_customer || 0;
        this.total_user = total_user || 0;
        this.new_customer = new_customer || 0;
        this.active_customer = active_customer || 0;
        this.engaged_customer = engaged_customer || 0;
    }
}
