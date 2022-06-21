export default class ApplicationCafFile {
    constructor({id, title, first_name, middle_name, last_name, full_name, dob, created_date, occupancy_type, phone, email, abn, nmi, mirn, business_name, billing_preference, to_address, service, additional_instruction} = {}) {
        this.id = id;
        this.title = title;
        this.first_name = first_name;
        this.middle_name = middle_name;
        this.last_name = last_name;
        this.full_name = full_name;
        this.dob = dob;
        this.created_date = created_date;
        this.occupancy_type = occupancy_type;
        this.phone = phone;
        this.email = email;
        this.abn = abn;
        this.nmi = nmi;
        this.mirn = mirn;
        this.business_name = business_name;
        this.billing_preference = billing_preference;
        this.to_address = to_address;
        this.service = service;
        this.additional_instruction = additional_instruction;
    }
}
