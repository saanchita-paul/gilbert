import PowershopPlanDetails from '@scripts/modules/powershop/models/PowershopPlanDetails';

export default {
    mapPowershopData: (plansData) => {
        return new PowershopPlanDetails(plansData);
    }
};

