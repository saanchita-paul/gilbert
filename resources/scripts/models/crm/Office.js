import DayJs from "dayjs";
import DATE_FORMAT from "@scripts/data/constants/DATE_FORMAT";

export default class Office {
    constructor({id , name , total_leads = 0, updated_at  , agency = null, applications_count, agents_count = 0, rent_roll = 0, active_agents=0}) {
        this.id = id;
        this.title = name;
        this.total_leads = applications_count;
        this.last_updated = updated_at? new DayJs(updated_at).format(DATE_FORMAT.DEFAULT_DATETIME_SLASH) : null;
        this.user_count = agents_count;
        this.active_user_count = active_agents;
        this.agency = agency
        this.rent_roll = rent_roll

    }

}
