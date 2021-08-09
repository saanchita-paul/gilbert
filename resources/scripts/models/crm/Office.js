export default class Office {
    constructor({id , name , total_leads = 0, updated_at  , applications_count, agents_count = 0}) {
        this.id = id;
        this.title = name;
        this.total_leads = applications_count;
        this.last_updated = updated_at;
        this.user_count = agents_count;

    }

}
