import SumoDataPlanMapper from '@scripts/modules/sumo/models/SumoPlanDetails'

export default {
    mapAddress :(address , applicationId)=>{
        return {
            // address: '1/309 Cumberland Rd, Pascoe Vale VIC 3044',
            address,
            quoteNumber: 'hood_'+applicationId,
        }
    },
    mapProduct :(location , service_type , agent_name, lead_id)=>{
        return {
            campaign: 'hood',
            channel: 'crm',
            consultantID: location?.distributor ? location.distributor : "electricDistributorNotFound" ,
            electricityDistributor: agent_name,
            fuelType: service_type,
            nmi: location.nmi,
            postcode: location.postcode,
            prospectType: 'Residential',
            quoteNumber: 'hood_'+lead_id,
            suburb: location.suburbOrPlaceOrLocality,
        }
    },
    planMapper: (plansList)=>{
        let plans = new SumoDataPlanMapper();
        console.log('plan list')
        console.log(plansList)
        plans.is_elec_available = Array.isArray(plansList?.electricityProducts) && plansList?.electricityProducts.length > 0
        plans.elec_plan_name = plansList?.electricityProducts[0]?.electricityPlanName;
        plans.elec_distributor_name = plansList?.electricityProducts[0]?.distributor;
        plans.elec_charge_name = plansList?.electricityProducts[0]?.tariffs[0]?.name;
        plans.elec_price_reference = plansList?.electricityProducts[0]?.electricReferencePrice;
        plans.elec_charge_usage = plansList?.electricityProducts[0]?.tariffs[0]?.charges.usage;
        plans.elec_charge_supply = plansList?.electricityProducts[0]?.tariffs[0]?.charges.supply;
        plans.elec_disclaimer_text = plansList?.electricityProducts[0]?.disclaimer;
        plans.elec_monthly_cost = plans.getMonthlyElectricityCost();
        plans.elec_yearly_cost = plans.getYearlyElectricityCost();
        



        plans.is_gas_available = Array.isArray(plansList?.gasProducts) && plansList?.gasProducts.length > 0;
        plans.gas_plan_name = plansList?.gasProducts[0]?.gasPlanName;
        plans.gas_distributor_name = plansList?.gasProducts[0]?.distributor;
        plans.gas_charge_name = plansList?.gasProducts[0]?.tariffs[0]?.name;
        plans.gas_price_reference = plansList?.gasProducts[0]?.electricReferencePrice;
        plans.gas_charge_usage = plansList?.gasProducts[0]?.tariffs[0]?.charges.usage;

        plans.gas_peak_usage    = plansList?.gasProducts[0]?.tariffs[0]?.charges.usage[0];
        plans.gas_offpeak_usage = plansList?.gasProducts[0]?.tariffs[0]?.charges.usage[1];

        
        plans.gas_charge_supply = plansList?.gasProducts[0]?.tariffs[0]?.charges.supply;
        plans.gas_disclaimer_text = plansList?.gasProducts[0]?.disclaimer;
        plans.gas_monthly_cost = plans.getMonthlyGasCost();
        plans.gas_yearly_cost = plans.getYearlyGasCost();

        plans.plan_name = plans.getPlanName(plansList);

        return plans;
    }
};

