export default class {
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
                    gas_charges,
                    gas_disclaimer_text,
                    gas_monthly_cost,
                    gas_yearly_cost,

                }) {

    }
}
