export default class Agency {
    constructor({
        id, agency_name, total_leads, last_updated, offices
                }) {
        this.id = id;
        this.agency_name = agency_name;
        this.total_leads =  total_leads;
        this.last_updated = last_updated;
        this.offices = offices;
    }
}
