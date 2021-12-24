class LeadSearchFilter {
    LeadSearchFilter({
        tenancyname,
        source,
        mobile,
        address,
        tenancytype,
    } = {}) {
        this.tenancyname = tenancyname;
        this.source = source;
        this.mobile = mobile;
        this.address = address;
        this.tenancytype = tenancytype;
    }
}

export { LeadSearchFilter };
