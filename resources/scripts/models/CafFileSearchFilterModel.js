import {isEmpty} from "lodash-es";

class CafFileSearchFilterModel {
    constructor({
                    name,
                    address,
                    business_name,
                    abn,
                    start_date,
                    end_date,
                    tenant_name,
                    provider_name,
                    app_type,
                    status,
                    application_service_type,
                } = {}) {
        this.name = name;
        this.address = address;
        this.business_name = business_name;
        this.abn = abn;
        this.start_date = start_date;
        this.end_date = end_date;
        this.tenant_name = tenant_name;
        this.provider_name = provider_name;
        this.app_type = app_type;
        this.status = status;
        this.application_service_type = application_service_type;
    }

    isSearchEmpty() {
        return isEmpty(this.name) &&
            isEmpty(this.address) &&
            isEmpty(this.business_name) &&
            isEmpty(this.abn) &&
            isEmpty(this.provider_name) &&
            isEmpty(this.app_type) &&
            isEmpty(this.power_status);
    }

}

export {CafFileSearchFilterModel};
