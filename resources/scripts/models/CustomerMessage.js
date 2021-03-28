import Customer from "@scripts/models/Customer";
export default class CustomerMessage {
    constructor(
        {
            id,
            created_at,
            diff_for_humans,
            type,
            text_content,
            customer,
        } = {}, 
        isAvatarNeed
    ) {
        this.id = id || null;
        this.created_at = created_at || null;
        this.diff_for_humans = diff_for_humans || null;
        this.type = type || null;
        this.text_content = text_content || null;
        this.customer = new Customer(customer) || null;
        this.isAvatarNeed = isAvatarNeed || false;

    }
}
