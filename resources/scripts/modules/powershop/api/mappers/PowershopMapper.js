import PowershopPlanDetails from '@scripts/modules/powershop/models/PowershopPlanDetails';
import {isNull} from "lodash-es";

const mapPowershopData = (plansData) => {
    const planDetailsModel = new PowershopPlanDetails(plansData);
    planDetailsModel.plans.electricity = mapElectricityPlan(plansData?.plans?.electricity);
    planDetailsModel.plans.gas = mapGasPlan(plansData?.plans?.gas);
    return planDetailsModel;
}

const mapElectricityOffer = (vdo, distributor_name) => {
    return {
        'title': "$" + vdo.vdo_dmo_amount + "/Year",
        'line_1' : "For an average household using "+ vdo.consumption +" kWh/year, the estimated annual cost of this electricity plan is $" + vdo.vdo_dmo_amount + " in the "+ distributor_name +" network with single rate tariff.",
        'line_2' :  vdo.vdo_dmo_percentage + "% less then the",
    }
}

const mapElectricityPlan = (electricity) => {

    if(isNull(electricity)) {
        return null;
    }
   return  electricity.vdo.map(vdo => {
       return {
           distributor_name: electricity?.distributor_name,
           fees: electricity?.price,
           supply_charge: electricity?.daily_charge,
           usage_charge: electricity?.anytime_charge,
           solar_buy_pack_value: electricity?.solar_buy_pack_value,
           offers: mapElectricityOffer(vdo, electricity?.distributor_name),
           bpid_links: mapBPIDLinks(electricity?.bpid_links),
           name: vdo.marketing_offer_name,
           title: vdo.marketing_offer_name,
        }
    });

    return {
        distributor_name: electricity?.distributor_name,
        fees: electricity?.price,
        supply_charge: electricity?.daily_charge,
        usage_charge: electricity?.anytime_charge,
        solar_buy_pack_value: electricity?.solar_buy_pack_value,
        offers: electricity?.offers,
        bpid_links: mapBPIDLinks(electricity?.bpid_links)
    }
}

const mapSupplyCharge = (supplyCharge) => {
    return {
        description: supplyCharge?.description,
        unit: supplyCharge?.unit,
        value: supplyCharge?.value,
    }
}

const mapUsagesCharge = (usageCharge) => {
    return {
        description: usageCharge?.description,
        unit: usageCharge?.unit,
        value: usageCharge?.value,
    }
}

const mapBPIDLinks = (bpidLinks) => {
    return bpidLinks.map((item) => {
        return {
            id: item?.id,
            title: item?.title,
            file_url: item?.link,
        }
    });
}

const mapGasPlan = (gas) => {

    console.log('gas plan details', gas);


    if(isNull(gas)) {
        return null;
    }
    return  gas.vdo.map(vdo => {
        return {
            distributor_name: gas?.distributor_name,
            fees: gas?.price,
            supply_charge: gas?.daily_charge,
            usage_charge: gas?.anytime_charge,
            bpid_links: mapBPIDLinks(gas?.bpid_links),
            name: vdo.marketing_offer_name,
            title: vdo.marketing_offer_name,
        }
    });


    return {
        distributor_name: gas?.distributor_name,
        fees: gas?.price,
        supply_charge: gas?.daily_charge,
        usage_charge: gas?.anytime_charge,
        bpid_links: mapBPIDLinks(gas?.bpid_links),
        name: 'Gas Plan A',
        title: 'gas_plan_a',
    }
}

const mapServiceText = (serviceType) => {
    return serviceType === 'power' ? "Electricity"
        : serviceType === 'gas' ? "Gas"
            : "Electricity & Gas";
}




export default {
    mapPowershopData,
    mapServiceText,
};

