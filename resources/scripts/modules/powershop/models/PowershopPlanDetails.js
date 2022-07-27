export default class PowershopPlanDetails {
    /**
     * @param {string} title
     * @param {string} short_des
     * @param {array} plans
     * @param {string} rates
     * @param {string} exit_fees
     * @param {integer} benefit_period
     * @param {string} green_options
     * @param {array} bpid_links
     * @param offers
     */
    constructor({
                    title,
                    short_des,
                    plans,
                    rates,
                    exit_fees,
                    benefit_period,
                    green_options,
                    bpid_links,
                    offers,

                } = {}) {
        this.title = title;
        this.short_des = short_des;
        this.plans = plans;
        this.rates = rates;
        this.exit_fees = exit_fees;
        this.benefit_period = benefit_period;
        this.green_options = green_options;
        this.bpid_links = bpid_links;
        this.offers = offers;
    }

}
