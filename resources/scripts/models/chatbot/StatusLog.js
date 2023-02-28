import DayJs from "dayjs";
import dayjs from "dayjs";

export default class ApplicationNote{
    constructor({
                    created_at= null,
                    text= null,
                    created_by= null,
                    user_role= null,
                }) {
        this.created_at = dayjs.utc(created_at).local().format("DD/MM/YYYY hh:mm A");
        this.text = JSON.parse(text);
        this.created_by = created_by;
        this.user_role = user_role;
    }
}
