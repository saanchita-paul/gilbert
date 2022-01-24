import {isEmpty} from "lodash-es";
export class AgencyFilter{

    start = null;
    end = null;
    state = null;
    account_manager_id = null;
    agency_id = null;
    office_id = null;
    constructor(
        { 
            start,
            end,
            state,
            account_manager_id,
            agency_id,
            office_id
        } = {}
    ) {
        this.start = start
        this.end = end
        this.state = state
        this.account_manager_id = account_manager_id
        this.agency_id = agency_id
        this.office_id = office_id
    }

    clear(){
        this.start = null;
        this.end = null;
        this.state = null;
        this.account_manager_id = null;
        this.agency_id = null;
        this.office_id = null;
    }

    isSearchEmpty() {
        return isEmpty(this.start) &&
            isEmpty(this.end) &&
            isEmpty(this.state) &&
            isEmpty(this.account_manager_id) &&
            isEmpty(this.agency_id) &&
            isEmpty(this.office_id);
    }

};
