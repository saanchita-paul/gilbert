export default class CrmUser{
    constructor({id, proerty_manager_name, submitted_lead, role, mobile, email }) {
        this.id = id;
        this.proerty_manager_name = proerty_manager_name;
        this.submitted_lead = submitted_lead;
        this.role = role;
        this.mobile = mobile;
        this.email = email;
    }
}
