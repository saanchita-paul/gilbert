import PowershopPlanDetails from '@scripts/modules/powershop/models/PowershopPlanDetails';

const mapPowershopData = (plansData) => {
    const planDetailsModel = new PowershopPlanDetails(plansData);
    planDetailsModel.plans.electricity = mapElectricityPlan(plansData?.plans?.electricity);
    planDetailsModel.plans.gas = mapGasPlan(plansData?.plans?.gas);
    return planDetailsModel;
}

const mapElectricityPlan = (electricity) => {
    return {
        distributor_name: electricity?.distributor_name,
        fees: electricity?.fees,
        supply_charge: mapSupplyCharge(electricity?.supply_charge),
        usage_charge: mapUsagesCharge(electricity?.usage_charge),
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
            file_url: item?.file_url,
        }
    });
}

const mapGasPlan = (gas) => {
    return {
        distributor_name: gas?.distributor_name,
        solar_feed_in_tariff: gas?.solar_fees,
        fees: gas?.fees,
        supply_charge: mapSupplyCharge(gas?.supply_charge),
        usage_charge: mapUsagesCharge(gas?.usage_charge),
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

