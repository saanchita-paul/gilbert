export default class CustomerSummary {
    constructor({total_customer_count, new_customer_count, active_customer_count, engaged_customer_count} = {}) {
        this.total_customer_count = total_customer_count || 0;
        this.new_customer_count = new_customer_count || 0;
        this.active_customer_count = active_customer_count || 0;
        this.engaged_customer_count = engaged_customer_count || 0;
    }
}
