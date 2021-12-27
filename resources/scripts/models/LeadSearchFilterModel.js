import { isEmpty } from "lodash-es";

class LeadSearchFilterModel {
    LeadSearchFilterModel({
        tenancy_name,
        source,
        mobile,
        address,
        tenancy_type,
        office_id,
    } = {}) {
        this.tenancy_name = tenancy_name;
        this.source = source;
        this.mobile = mobile;
        this.address = address;
        this.tenancy_type = tenancy_type;
        this.office_id = office_id;
    }

    isSearchEmpty() {
        const isNoValue =
            isEmpty(this.tenancy_name) &&
            isEmpty(this.source) &&
            isEmpty(this.mobile) &&
            isEmpty(this.address) &&
            isEmpty(this.tenancy_type);
        
        return isNoValue ? true : false;
    }
}

export { LeadSearchFilterModel };
