import dayJs from "dayjs";

export default class ConnectionService{
    constructor({
                    id= null,
                    service_type= null,
                    lead_reference= null,
                    status= null,
                    connection_date= null,
                    provider_name= null,
                    plan_type= null,
                    submitted_at= null,
                    quote_reference= null,

                }) {

        this.id = id;
        this.service_type = service_type;
        this.lead_reference = lead_reference;
        this.status = status;
        this.connection_date = dayJs(connection_date).format("DD/MM/YYYY h:mm A");
        this.provider_name = provider_name;
        this.plan_type = plan_type;
        this.submitted_at = dayJs(submitted_at).format("DD/MM/YYYY h:mm A");
        this.quote_reference = quote_reference;

    }
}
