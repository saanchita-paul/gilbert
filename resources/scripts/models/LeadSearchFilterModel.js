import {isEmpty} from "lodash-es";

class LeadSearchFilterModel {
    constructor({
        tenant_name,
        source,
        phone,
        address,
        tenancy_type,
    } = {}) {
        this.tenant_name = tenant_name;
        this.source = source ?? "";
        this.phone = phone;
        this.address = address;
        this.tenancy_type = tenancy_type ?? "";
    }

    isSearchEmpty() {
        return isEmpty(this.tenant_name) &&
            isEmpty(this.source) &&
            isEmpty(this.phone) &&
            isEmpty(this.address) &&
            isEmpty(this.tenancy_type);
    }
}

export { LeadSearchFilterModel };
