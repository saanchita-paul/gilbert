export default class CrmUser{
    constructor({id = 10, proerty_manager_name='', submitted_lead=100, role='user', mobile='444444', email, first_name='Mr' , last_name ='Ahmed' }) {
        this.id = id;
        this.proerty_manager_name = first_name + ' ' + last_name;
        this.submitted_lead = submitted_lead;
        this.role = role;
        this.mobile = mobile;
        this.email = email;

    }
}
