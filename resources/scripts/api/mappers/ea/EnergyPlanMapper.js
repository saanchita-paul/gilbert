import EnergyPlan, {EA_PLAN_TYPES, PLAN_TYPE_NO_FRILLS, PLAN_TYPE_TOTAL, SERVICE_TYPES} from "@scripts/models/ea/EnergyPlan";
import GasRate from "@scripts/models/ea/GasRate";
import ElectricityRate from "@scripts/models/ea/ElectricityRate";

export default {
    /**
     *  mapping plan data
     *
     * @param data
     * @param plan {string}
     *
     * @returns {EnergyPlan}
     */
    fromServer(data, plan){
        const planDetails = new EnergyPlan();
        planDetails.id = data.id;
        planDetails.electricity_plan_id = data.electricity_distributor_plan_id;
        planDetails.gas_plan_id = data.gas_distributor_plan_id;
        planDetails.description = data.description;
        planDetails.name = data.name;
        planDetails.disclaimer = data.disclaimer;
        planDetails.state_disclaimer = data.state_disclaimer;
        planDetails.gst_inclusive_text = data.gst_inclusive;
        planDetails.customer_type = data.customer_type;
        planDetails.distributor_name.electricity = data.distributor_name.electricity;
        planDetails.distributor_name.gas = data.distributor_name.gas;
        planDetails.connection_fees.gas = data.connection_fees.gas;
        planDetails.connection_fees.electricity = data.connection_fees.electricity;
        // planDetails.slogan = data.slogan;
        //todo:: refactor merge with individual property mapping
        planDetails.estimated_monthly_cost = {
            ...planDetails.estimated_monthly_cost,
            ...data.estimated_annual_cost
        };
        planDetails.promotional_texts.gas = getGasPromotionalText(data, plan)
        planDetails.promotional_texts.electricity = getElectricityPromotionalText(data, plan)
        planDetails.promotion_title = data.promotion_title
        planDetails.promotion.header = data.header;
        planDetails.promotion.lines =  Object.values(data.promotion.lines);
        planDetails.features = {...planDetails.features, ...data.features};
        planDetails.sign_up_credit = '';

        planDetails.rates.include_gst = data.include_gst;
        planDetails.rates.description = data.rates.description;
        planDetails.rates.electricity = mapElectricityRate(data.rates.electricity);
        planDetails.rates.gas = mapGasRate(data.rates.gas);

        planDetails.plan_details = data.plan_details;
        planDetails.solar_buy_pack_rate = {...planDetails.solar_buy_pack_rate, ...data.solar_buy_pack_rate}

        return planDetails
    }
}

const mapGasRate = data => {
    const rate = new GasRate();
    if (data) {
        //todo:: refactor merge with individual property mapping
        rate.usage_rates = data.usage_rates
        rate.session_all_year = data.session_all_year
        return rate;
    }
    return null;
}

const mapElectricityRate = data => {
    const rate = new ElectricityRate();
    if (data) {
        //todo:: refactor merge with individual property mapping
        rate.daily_supply_charge = {...rate.daily_supply_charge, ...data.daily_supply_charge};
        rate.usage_rates = {...rate.usage_rates, ...data.usage_rates}
        return rate;
    }
    return  null;
}

const getGasPromotionalText = (planData, plan) => {
    const rate = planData.rates.gas;
    if (!rate) {
        return ''
    }
    if (plan === PLAN_TYPE_NO_FRILLS) {
        let r = rate.session_all_year
            ? rate.usage_rates.peak_usage_per_day.before_discount
            : rate.usage_rates[0].data.peak_usage_per_day.before_discount;
        r = getFloatFromString(r);
        return r.length > 0 ? r + " cents per kWh" : '';
    }
    if (plan === PLAN_TYPE_TOTAL) {
        return planData.discounts.gas + '% gas discount';
    }
    return '';
}

const getElectricityPromotionalText = (planData, plan) => {
    const rate = planData.rates.electricity;
    if (!rate) {
        return ''
    }
    if (plan === PLAN_TYPE_TOTAL) {
        return planData.discounts.electricity + '% electricity discount'
    }
    if (plan === PLAN_TYPE_NO_FRILLS  ) {
        let r = rate.usage_rates.peak_usage_per_day.before_discount;
        r = getFloatFromString(r);
        return r.length > 0 ? r + " cents per kWh" : '';
    }
    return '';
}

const getFloatFromString = text => {
    if (text && ['string', 'number'].includes(typeof text)) {
        text =  text.toString()
        const result = text.match(/[\d]*[.]?[\d]+/g)
        return result.length > 0 ? parseFloat(result[0]).toString() : ''
    }
    return '';
}

/**
 * mapping all plan list
 */
export const mapAllPlan = plans => {
    const res = Object.keys(plans).map(plan => {
        const key = EA_PLAN_TYPES.find(type => type.value === plan)?.key;
        return {
            active: false,
            key: key,
            title: plan
        }
    })
    console.log('plans', res);
    return res
}

export const mapServices = services => {
    if (services.includes('gas') && services.includes('power')) {
        return SERVICE_TYPES.ELECTRICITY_AND_GAS
    }
    if (services.includes('gas')) {
        return  SERVICE_TYPES.GAS
    }

    if (services.includes('power')) {
        return  SERVICE_TYPES.ELECTRICITY
    }
    throw new Error("Unknown service type")
}
