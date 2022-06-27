import ElectricityRate from "@scripts/models/ea/ElectricityRate";
import GasRate from "@scripts/models/ea/GasRate";

export const PLAN_TYPE_TOTAL = 'total_plan';
export const PLAN_TYPE_NO_FRILLS = 'no_frills';
export const PLAN_TYPE_BASIC_PLAN = 'basic_plan';
export const PLAN_TYPE_FLEXI_PLAN = 'flexi_plan';

export const SERVICE_TYPES = {
    GAS: 'gas',
    ELECTRICITY: 'electricity',
    ELECTRICITY_AND_GAS: 'electricity_and_gas'
}

export const SOLAR_PACK = {
    peak_c_kwh: "Peak applicable times:\n" +
        "(3pm-9pm Weekdays)",
    shoulder_c_kwh: "Shoulder applicable times:\n" +
        "(7am-3pm, 9pm-10pm Weekdays; 7am-10pm Weekends)",
    off_peak_c_kwh: "Off peak applicable times:\n" +
        "(10pm-7am Everyday)",
}

export const EA_PLAN_TYPES = [
     {value: "Basic - Home", key: PLAN_TYPE_BASIC_PLAN},
     {value: "No Frills (Home)", key: PLAN_TYPE_NO_FRILLS},
     {value: "Total Plan (Home)", key: PLAN_TYPE_TOTAL},
     {value: "Flexi Plan (Home)", key: PLAN_TYPE_FLEXI_PLAN},
]

export default class EnergyPlan {
    'id' = null;
    'name' = '';
    'slogan' = '';
    disclaimer = '';
    state_disclaimer = '';
    gas_plan_id = null;
    electricity_plan_id = null;
    description = '';
    customer_type = '';
    gst_inclusive_text = '';
    connection_fees = {
        gas: '',
        electricity: ''
    }
    'estimated_monthly_cost' = {
        'amount': 0,
        'title': '',
        'description': '',
    };
    'distributor_name'= {
        electricity: "",
        gas: ""
    }
    promotional_texts = {
        gas: '',
        electricity: ''
    };
    promotion_title = '';
    'promotion' = {
        'header': '',
        'lines': [],
    };
    'sign_up_credit' = '';
    'features' = [];
    'rates' = {
        'include_gst': true,
        'description': [],
        'electricity': new ElectricityRate(),
        'gas': new GasRate(),
    };
    plan_details = {
        benefit_period: '',
        exit_fees: '',
        connection_fees: '',
        rates: '',
        late_payment_fee: '',
        customer_type: '',
    };
    solar_buy_pack_rate = {
        single_rate_c_kwh: null,
        peak_c_kwh: null,
        shoulder_c_kwh: null,
        off_peak_c_kwh: null,
    };
    discounts = {
        electricity: null,
        gas: null
    }
    state_distributor_discount= {
        electricity: null,
        elec_credit_amount: null,
        gas: null,
        gas_credit_amount: null
    };

}

export const isTopEAPlan = (plan) => [PLAN_TYPE_TOTAL, PLAN_TYPE_FLEXI_PLAN].includes(plan)

