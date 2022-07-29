import PowershopPlanDetails from '@scripts/modules/powershop/models/PowershopPlanDetails';

const mapPowershopData = (plansData) => {
    const planDetailsModel = new PowershopPlanDetails(plansData);
    planDetailsModel.plans.electricity = mapElectricityPlan(plansData.plans.electricity);
    planDetailsModel.plans.gas = mapGasPlan(plansData.plans.gas);
    return planDetailsModel;
}

const mapElectricityPlan = (electricity) => {
    return {
        distributor_name: electricity.distributor_name,
        fees: electricity.fees,
        supply_charge: mapSupplyCharge(electricity.supply_charge),
        usage_charge: mapUsagesCharge(electricity.usage_charge),
        offers: electricity.offers,
        bpid_links: mapBPIDLinks(electricity.bpid_links)
    }
}

const mapSupplyCharge = (supplyCharge) => {
    return {
        id: supplyCharge.id,
        description: supplyCharge.description,
        unit: supplyCharge.unit,
        gst_inc_round_2: supplyCharge.gst_inc_round_2,
    }
}

const mapUsagesCharge = (usageCharge) => {
    return {
        id: usageCharge.id,
        description: usageCharge.description,
        unit: usageCharge.unit,
        gst_inc_round_2: usageCharge.gst_inc_round_2,
    }
}

const mapBPIDLinks = (bpidLinks) => {
    return bpidLinks.map((item) => {
        return {
            id: item.id,
            title: item.title,
            file_url: item.file_url,
        }
    });
}

const mapGasPlan = (gas) => {
    return {
        distributor_name: gas.distributor_name,
        fees: gas.fees,
        supply_charge: mapSupplyCharge(gas.supply_charge),
        usage_charge: mapUsagesCharge(gas.usage_charge),
        bpid_links: mapBPIDLinks(gas.bpid_links)
    }
}

const mapServiceText = (serviceType) => {
    return serviceType === 'power' ? "Electricity"
        : serviceType === 'gas' ? "Gas"
            : "Electricity & Gas";
}

const mapElectricityBPIDLinks = (electricity) => {
    return electricity?.bpid_links;
}

const mapGasBPIDLinks = (gas) => {
    return gas?.bpid_links;
}

export default {
    mapPowershopData,
    mapServiceText,
    mapElectricityBPIDLinks,
    mapGasBPIDLinks
};

