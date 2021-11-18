import SumoDataPlanMapper from '@scripts/modules/sumo/models/SumoPlanDetails'

export default {
    mapAddress :(address)=>{
        return {
            // address: '1/309 Cumberland Rd, Pascoe Vale VIC 3044',
            address,
            quoteNumber: 'randomQuoteNumberFromOurServer',
        }
    },
    mapProduct :(location , service_type , agent_name)=>{
        return {
            campaign: 'hood',
            channel: 'crm',
            consultantID: 'OurAgentName',
            electricityDistributor: agent_name,
            fuelType: service_type,
            nmi: location.nmi,
            postcode: location.postcode,
            prospectType: 'Residential',
            quoteNumber: 'randomQuoteNumberFromOurServer',
            suburb: location.suburbOrPlaceOrLocality,
        }
    },
    planMapper: (plansList)=>{
        let plans = new SumoDataPlanMapper();
        console.log('plan list')
        console.log(plansList)
        plans.elec_plan_name = plansList?.electricityProducts[0]?.electricityPlanName;
        plans.elec_distributor_name = plansList?.electricityProducts[0]?.distributor;
        plans.elec_charge_name = plansList?.electricityProducts[0]?.tariffs[0]?.name;
        plans.elec_price_reference = plansList?.electricityProducts[0]?.electricReferencePrice;
        plans.elec_charge_usage = plansList?.electricityProducts[0]?.tariffs[0]?.charges.usage;
        plans.elec_charge_supply = plansList?.electricityProducts[0]?.tariffs[0]?.charges.supply;
        plans.elec_disclaimer_text = plansList?.electricityProducts[0]?.disclaimer;
        plans.elec_monthly_cost = 0;
        plans.elec_yearly_cost = 0;
        

        plans.gas_plan_name = plansList?.gasProducts[0]?.gasPlanName;
        plans.gas_distributor_name = plansList?.gasProducts[0]?.distributor;
        plans.gas_charge_name = plansList?.gasProducts[0]?.tariffs[0]?.name;
        plans.gas_price_reference = plansList?.gasProducts[0]?.electricReferencePrice;
        plans.gas_charge_usage = plansList?.gasProducts[0]?.tariffs[0]?.charges.usage;
        
        plans.gas_peak_usage    = plansList?.gasProducts[0]?.tariffs[0]?.charges.usage[0];
        plans.gas_offpeak_usage = plansList?.gasProducts[0]?.tariffs[0]?.charges.usage[1];

        plans.gas_charge_supply = plansList?.gasProducts[0]?.tariffs[0]?.charges.supply;
        plans.gas_disclaimer_text = plansList?.gasProducts[0]?.disclaimer;
        plans.gas_monthly_cost = 0;
        plans.gas_yearly_cost = 0;

        return plans;
    }

};