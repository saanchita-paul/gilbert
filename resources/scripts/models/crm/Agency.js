export default class Agency {
    constructor({
        id, title = 'ABC company', total_leads=70, last_updated = '2021/10/10', offices = 100
                }) {
        this.id = id;
        this.title = title;
        this.total_leads =  total_leads;
        this.last_updated = last_updated;
        this.offices = offices;
    }
}
