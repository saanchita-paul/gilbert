export default class OfficeApplicationMetric{

    constructor({
                    app_created,
                    app_waiting_tenant,
                    app_submitted_retialer,
                    app_successful,
                    app_closed,

                }) {
        this.app_created = app_created;
        this.app_waiting_tenant = app_waiting_tenant;
        this.app_submitted_retialer = app_submitted_retialer;
        this.app_successful = app_successful;
        this.app_closed = app_closed
    }
}
