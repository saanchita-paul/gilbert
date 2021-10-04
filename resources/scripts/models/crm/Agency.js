import DayJS from "dayjs";
import DayJs from "dayjs";
import DATE_FORMAT from "@scripts/data/constants/DATE_FORMAT";

export default class Agency {
    constructor({
                    id = null ,
                    name = '',
                    applications_count = 0,
                    updated_at = '',
                    offices_count = 0,
                    type = null
                }) {
        this.id = id;
        this.title = name;
        this.total_leads =  applications_count;
        this.last_updated = updated_at? new DayJs(updated_at).format(DATE_FORMAT.REAL_ESTATE_FORMAT) : null;
        this.offices = offices_count;
        this.type = type;
    }
}
