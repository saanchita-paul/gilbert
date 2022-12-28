import dayJs from "dayjs";

export default class ApplicationNote{
    constructor({
                    created_at= null,
                    text= null,
                    created_by= null,
                    user_role= null,
                }) {
        this.created_at = dayJs(created_at).format("DD/MM/YYYY h:mm A");
        this.text = text;
        this.created_by = created_by;
        this.user_role = user_role;
    }
}
