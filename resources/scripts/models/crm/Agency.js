export default class Agency {
    constructor({
        id = null , name = '', total_leads=70, updated_at = '', offices_count = 0
                }) {
        this.id = id;
        this.title = name;
        this.total_leads =  total_leads;
        this.last_updated = updated_at;
        this.offices = offices_count;
    }
}
