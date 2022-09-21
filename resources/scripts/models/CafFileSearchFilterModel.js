import {isEmpty} from "lodash-es";

class CafFileSearchFilterModel {
    constructor({
                    name,
                    address,
                    business_name,
                    abn,
                    start_date,
                    end_date,
                } = {}) {
        this.name = name;
        this.address = address;
        this.business_name = business_name;
        this.abn = abn;
        this.start_date = start_date;
        this.end_date = end_date;
    }

    isSearchEmpty() {
        return isEmpty(this.name) &&
            isEmpty(this.address) &&
            isEmpty(this.business_name) &&
            isEmpty(this.abn);
    }

}

export {CafFileSearchFilterModel};
