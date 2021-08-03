export default class AppMetric{
    constructor({id, title, lead_count, status }) {
        this.id = id;
        this.title = title;
        this.lead_count = lead_count;
        this.status = status;
        this.color = '';
        this.icon = '';
    }
}
