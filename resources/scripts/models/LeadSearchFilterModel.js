import {isEmpty} from "lodash-es";

class LeadSearchFilterModel {
    constructor({
        tenant_name,
        source,
        phone,
        address,
        tenancy_type,
        app_id,
        tenant_email,
        moving_date,
        active_lead_type,
        agent_id,
        agent_name,
        triage
    } = {}) {
        this.tenant_name = tenant_name;
        this.source = source ?? null;
        this.phone = phone;
        this.address = address;
        this.tenancy_type = tenancy_type ?? null;
        this.app_id = app_id;
        this.tenant_email = tenant_email;
        this.moving_date = moving_date;
        this.active_lead_type = active_lead_type;
        this.agent_id = agent_id;
        this.agent_name = agent_name;
        this.triage = triage ?? null;
    }

    isSearchEmpty() {
        return isEmpty(this.tenant_name) &&
            isEmpty(this.source) &&
            isEmpty(this.phone) &&
            isEmpty(this.address) &&
            isEmpty(this.tenancy_type) &&
            isEmpty(this.triage);
    }

    clear(){
        this.tenant_name = null;
        this.source = null;
        this.phone = null;
        this.address = null;
        this.tenancy_type = null;
        this.app_id = null;
        this.tenant_email = null;
        this.moving_date = null;
        this.active_lead_type = null;
        this.agent_id = null;
        this.agent_name = null;
        this.triage = null;
    }
}

export { LeadSearchFilterModel };
