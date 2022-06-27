import SumoDataPlanMapper from '@scripts/modules/sumo/models/SumoPlanDetails'

export default {
    mapAddress :(address , sumoUuid)=>{
        return {
            // address: '1/309 Cumberland Rd, Pascoe Vale VIC 3044',
            address,
            quoteNumber: 'hood_'+sumoUuid.data,
        }
    },
    mapProduct :(sumoInfo , service_type , agent_name, sumoUuid)=>{
        
        let distributorName = sumoInfo?.electricityDistributors[0]?.distributor ??  'electricDistributorNotFound';

        return {
            campaign: 'hood',
            channel: 'crm',
            consultantID: agent_name ,
            electricityDistributor: distributorName,
            fuelType: service_type,
            nmi: sumoInfo.nmi,
            postcode: sumoInfo.postcode,
            prospectType: 'Residential',
            quoteNumber: 'hood_'+sumoUuid.data,
            suburb: sumoInfo.suburbOrPlaceOrLocality,
        }
    },
    planMapper: (plansList)=>{
        let plans = new SumoDataPlanMapper();
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

