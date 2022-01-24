export default class Agent {
    constructor({ id, office_id, agency_id, first_name, last_name }) {
        this.id = id;
        this.office_id = office_id;
        this.agency_id = agency_id;
        this.first_name = first_name;
        this.last_name = last_name;
        this.full_name = first_name + " " + last_name;
    }
}
