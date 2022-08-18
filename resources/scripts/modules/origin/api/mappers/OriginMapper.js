import OriginPlanDetails from '@scripts/modules/origin/models/OriginPlanDetails';

export default {
    mapOriginData: (plansData) => {
        let model = new OriginPlanDetails({...plansData});
        model.plans.gas.plan_name = plansData?.plans?.gas?.plan_name || 'Origin Advantage Variable New';
        return model;
    }
};

