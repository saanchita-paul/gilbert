import OriginPlanDetails from '@scripts/modules/origin/models/OriginPlanDetails';

export default {
    mapOriginData: (plansData) => {
        return new OriginPlanDetails({...plansData});
    }
};

