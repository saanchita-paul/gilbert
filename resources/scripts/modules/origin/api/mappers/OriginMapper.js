import OriginPlanDetails from '@scripts/modules/origin/models/OriginPlanDetails';

export default {
    mapOriginData: (plansData) => {
        
        let plans = new OriginPlanDetails(plansData);
        
        return plans;
    }
};

