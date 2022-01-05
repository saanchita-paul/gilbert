import {isEmpty} from "lodash-es";

class LeadSearchFilterModel {
    constructor({
        tenant_name,
        source,
        phone,
        address,
        tenancy_type,
        app_id,
        email,
        moving_date,
        status,
        agent_id,
    } = {}) {
        this.tenant_name = tenant_name;
        this.source = source ?? "";
        this.phone = phone;
        this.address = address;
        this.tenancy_type = tenancy_type ?? "";
        this.app_id = app_id;
        this.email = email;
        this.moving_date = moving_date;
        this.status = status;
        this.agent_id = agent_id;
    }

    isSearchEmpty() {
        return isEmpty(this.tenant_name) &&
            isEmpty(this.source) &&
            isEmpty(this.phone) &&
            isEmpty(this.address) &&
            isEmpty(this.tenancy_type);
    }

    clear(){
        this.tenant_name = null;
        this.source = null;
        this.phone = null;
        this.address = null;
        this.tenancy_type = null;
        this.app_id = null;
        this.email = null;
        this.moving_date = null;
        this.status = null;
        this.agent_id = null;
    }
}

export { LeadSearchFilterModel };
