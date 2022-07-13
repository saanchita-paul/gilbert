export default class OriginPlan {
    /**
     * @param {string} title
     * @param {integer} charge 
     * @param {string} slogan
     * @param {string} details
     * @param {array} supply_charge
     * @param {array} usage_charge
     */
    constructor({
        title,
        charge,
        slogan,
        details,
        supply_charge,
        usage_charge,
        fees

    } = {}) {
        this.title = title;
        this.charge = charge;
        this.slogan = slogan;
        this.details = details;
        this.supply_charge = supply_charge;
        this.usage_charge = usage_charge;
        this.fees = fees;
    }

}
