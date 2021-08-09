export default class Agency {
    constructor({
        id = null , name = '', applications_count = 0, updated_at = '', offices_count = 0
                }) {
        this.id = id;
        this.title = name;
        this.total_leads =  applications_count;
        this.last_updated = updated_at;
        this.offices = offices_count;
    }
}
