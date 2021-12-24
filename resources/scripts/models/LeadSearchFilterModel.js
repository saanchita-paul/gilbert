class LeadSearchFilterModel {
    LeadSearchFilterModel({
        tenancy_name,
        source,
        mobile,
        address,
        tenancy_type,
    } = {}) {
        this.tenancy_name = tenancy_name;
        this.source = source;
        this.mobile = mobile;
        this.address = address;
        this.tenancy_type = tenancy_type;
    }
}

export { LeadSearchFilterModel };
