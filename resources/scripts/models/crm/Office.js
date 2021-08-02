export default class Office {
    constructor({id = 1, office_name = '', total_leads = 0, last_updated = '' , user_account = ''}) {
        this.id = id;
        this.office_name = office_name;
        this.total_leads = total_leads;
        this.last_updated = last_updated;
        this.user_account = user_account;

    }

}
