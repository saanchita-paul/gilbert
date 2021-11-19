export default class SumoPlanMapper {
    /**
     *
     * @param {boolean} is_elec_available
     * @param {string} elec_plan_name
     * @param {string} elec_distributor_name
     * @param {string} elec_charge_name
     * @param {string} elec_price_reference
     * @param {{name: string, unit: string, value_with_gst}[]} elec_charges
     * @param {string} elec_disclaimer_text
     * @param {string} elec_monthly_cost
     * @param {string} elec_yearly_cost
     * @param {boolean} is_gas_available
     * @param {string} gas_plan_name
     * @param {string} gas_price_reference
     * @param {string} gas_distributor_name
     * @param {string} gas_charge_name
     * @param {{name: string, unit: string, value_with_gst}[]} gas_charges
     * @param {string} gas_disclaimer_text
     * @param {string} gas_monthly_cost
     * @param {string} gas_yearly_cost
     */
    constructor({
                    is_elec_available,
                    elec_plan_name,
                    elec_distributor_name,
                    elec_charge_name,
                    elec_rate_name,
                    elec_charge_usage,
                    elec_charge_supply,

                    elec_price_reference,
                    elec_charges,
                    elec_disclaimer_text,
                    elec_monthly_cost,
                    elec_yearly_cost,


                    is_gas_available,
                    gas_plan_name,
                    gas_price_reference,
                    gas_distributor_name,
                    gas_charge_name,
                    
                    gas_rate_name,
                    gas_charge_usage,
                    gas_charge_supply,

                    gas_peak_usage,
                    gas_offpeak_usage,

                    gas_charges,
                    gas_disclaimer_text,
                    gas_monthly_cost,
                    gas_yearly_cost,

                    plan_name,

                } = {} ) {

        this.elec_rate_name = elec_rate_name;
        this.elec_charge_usage = elec_charge_usage;
        this.elec_charge_supply = elec_charge_supply ?? 0;
        this.is_elec_available = is_elec_available;
        this.elec_plan_name = elec_plan_name;
        this.elec_distributor_name = elec_distributor_name;
        this.elec_charge_name = elec_charge_name;
        this.elec_price_reference = elec_price_reference;
        this.elec_charges = elec_charges;
        this.elec_disclaimer_text = elec_disclaimer_text;
        this.elec_monthly_cost = elec_monthly_cost;
        this.elec_yearly_cost = elec_yearly_cost;
        
        
        this.is_gas_available = is_gas_available;
        this.gas_plan_name = gas_plan_name;
        this.gas_price_reference = gas_price_reference;
        this.gas_distributor_name = gas_distributor_name;
        this.gas_charge_name = gas_charge_name;
        this.gas_charges = gas_charges;

        this.gas_peak_usage = gas_peak_usage;
        this.gas_offpeak_usage = gas_offpeak_usage;

        this.gas_disclaimer_text = gas_disclaimer_text;
        this.gas_monthly_cost = gas_monthly_cost;
        this.gas_yearly_cost = gas_yearly_cost;
        this.gas_rate_name = gas_rate_name;
        this.gas_charge_usage = gas_charge_usage;
        this.gas_charge_supply = gas_charge_supply ?? 0 ; 

        this.plan_name = plan_name;

    }

    getPlanName (plansList){
        let planName = '';
        if(Array.isArray(plansList?.gasProducts) && plansList?.gasProducts.length > 0){
            planName = plansList?.gasProducts[0]?.gasPlanName;
        } else if(Array.isArray(plansList?.electricityProducts) && plansList?.electricityProducts.length > 0){
            planName = plansList?.electricityProducts[0]?.electricityPlanName
        }
        return planName;
    }

    getMonthlyGasCost(){
        console.log('gas charge' , this.gas_charge_supply)
        
        if(this.is_gas_available)
        {
            return this.gas_charge_supply[0]?.incGST * 30;
        }else {
            return 0;
        }
    }

    getYearlyGasCost(){
        return this.getMonthlyGasCost() * 12;
    }

    getMonthlyElectricityCost(){
        // elec_charge_supply
        console.log('gas charge' , this.elec_charge_supply)

        if(this.is_elec_available){
            return this.elec_charge_supply[0]?.incGST * 30;
        } else{
            return 0;
        }

    }

    getYearlyElectricityCost(){
        return this.getMonthlyElectricityCost() * 12;
    }

}
