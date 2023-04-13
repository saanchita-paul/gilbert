export default class ConnectionService {
    id = null;
    service_type = null;
    created_at = null;
    updated_at = null;
    status = null;
    reason = null;
    connection_date = null;
    provider_name = null;
    plan_type = null;
    submitted_at = null;
    load_reference = null;
    quote_reference = null;
    accepted_at = null;
    rejected_at = null;
    distributor = null;


    /**
     * Connection Service constructor
     *
     * @param id
     * @param service_type
     * @param created_at
     * @param updated_at
     * @param status
     * @param reason
     * @param connection_date
     * @param provider_name
     * @param plan_type
     * @param submitted_at
     * @param load_reference
     * @param quote_reference
     * @param accepted_at
     * @param rejected_at
     * @param distributor
     *
     */
    constructor({
                    id = null,
                    service_type = null,
                    created_at = null,
                    updated_at = null,
                    status = null,
                    reason = null,
                    connection_date = null,
                    provider_name = null,
                    plan_type = null,
                    submitted_at = null,
                    load_reference = null,
                    quote_reference = null,
                    accepted_at = null,
                    rejected_at = null,
                    distributor = null,
                } = {}) {
        this.id = id;
        this.service_type = service_type;
        this.created_at = created_at;
        this.updated_at = updated_at;
        this.status = status;
        this.reason = reason;
        this.connection_date = connection_date;
        this.provider_name = provider_name;
        this.plan_type = plan_type;
        this.submitted_at = submitted_at;
        this.load_reference = load_reference;
        this.quote_reference = quote_reference;
        this.accepted_at = accepted_at;
        this.rejected_at = rejected_at;
        this.distributor = distributor;
    }
}
