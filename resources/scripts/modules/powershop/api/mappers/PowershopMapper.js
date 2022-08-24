import PowershopPlanDetails from '@scripts/modules/powershop/models/PowershopPlanDetails';

const mapPowershopData = (plansData) => {
    const planDetailsModel = new PowershopPlanDetails(plansData);
    planDetailsModel.plans.electricity = mapElectricityPlan(plansData?.plans?.electricity[0]);
    planDetailsModel.plans.gas = mapGasPlan(plansData?.plans?.gas[0]);
    return planDetailsModel;
}

const mapElectricityPlan = (electricity) => {
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
    return {
        distributor_name: gas?.distributor_name,
        fees: gas?.price,
        supply_charge: gas?.daily_charge,
        usage_charge: gas?.anytime_charge,
        bpid_links: mapBPIDLinks(gas?.bpid_links)
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

