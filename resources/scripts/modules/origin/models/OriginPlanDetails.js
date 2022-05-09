import OriginPlan from '@scripts/modules/origin/models/OriginPlan';

export default class OriginPlanDetails {
    /**
     * @param {string} title
     * @param {string} short_des
     * @param {array} plans
     * @param {string} rates
     * @param {string} exit_fees
     * @param {integer} benefit_period
     * @param {string} green_options
     */
    constructor({
        title,
        short_des,
        plans,
        rates,
        exit_fees,
        benefit_period,
        green_options,

    } = {}) {
        this.title = title;
        this.short_des = short_des;
        this.plan = plans.map(plan => {
            return new OriginPlan(plan);
        })
        this.rates = rates;
        this.exit_fees = exit_fees;
        this.benefit_period = benefit_period;
        this.green_options = green_options;
    }

}
