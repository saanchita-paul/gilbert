import DayJs from "dayjs";
import DATE_FORMAT from "@scripts/data/constants/DATE_FORMAT";

export default class Customer {
    constructor({
                    id,
                    full_name,
                    avatar,
                    last_interaction,
                    hood_ui,
                    facebook_id,
                    email,
                    phone,

                    manual_intervention_status,
                    connection_status,
                    sentiment,
                    state,
                    connection_date,
                    connection_time,
                    billing_preference
                } = {}) {
        this.id = id;
        this.name = full_name;
        this.profile_pic = avatar;
        this.last_interactive_time = last_interaction;
        this.hood_uid = hood_ui;
        this.messager_id = facebook_id;
        this.email = email;
        this.ph = phone;

        this.issue_status = manual_intervention_status;
        this.connection_status = connection_status;
        this.sentiment = sentiment;
        this.location = state;
        this.connection_date = connection_date;
        this.connection_time = connection_time;
        this.billing_preference = billing_preference;

    }

}
