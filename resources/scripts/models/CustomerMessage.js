import Customer from "@scripts/models/Customer";
import DayJs from "dayjs";
import DATE_FORMAT from "@scripts/data/constants/DATE_FORMAT";
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
        this.created_at = created_at? new DayJs(created_at).format(DATE_FORMAT.DATETIME_MESSAGE) : null;
        this.diff_for_humans = diff_for_humans || null;
        this.type = type || null;
        this.text_content = text_content || null;
        this.customer = new Customer(customer) || null;
        this.isAvatarNeed = isAvatarNeed || false;

    }
}
