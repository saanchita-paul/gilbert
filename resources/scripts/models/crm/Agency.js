import DayJs from "dayjs";
import DATE_FORMAT from "@scripts/data/constants/DATE_FORMAT";

export default class Agency {
    constructor({
                    id = null ,
                    name = '',
                    applications_count = 0,
                    updated_at = '',
                    offices_count = 0,
                    type = null,
                    last_application = null,
                    conversion_rate = 0,
                    active_user_count = 0,
                    rent_roll_count = 0,
                    logo = null
                } = {}) {
        this.id = id;
        this.title = name;
        this.total_leads =  applications_count;
        this.last_updated = updated_at? new DayJs(updated_at).format(DATE_FORMAT.REAL_ESTATE_FORMAT) : null;
        this.offices = offices_count;
        this.type = type;
        this.logo = logo;
        this.last_application = last_application
        // this.last_application = last_application? new DayJs(last_application).format(DATE_FORMAT.DATE_MONTH_FORMAT) : null;
        this.conversion_rate = conversion_rate+'%';
        this.active_user_count = active_user_count;
        this.rent_roll_count = rent_roll_count;
    }
}
