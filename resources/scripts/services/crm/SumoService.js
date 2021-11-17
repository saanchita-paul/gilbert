import SumoAPI from "@scripts/api/crm/SumoAPI"


export default {
    getPlans: async (address) => {
        let distributorData =  await SumoAPI.qualifyAddress('');
        let plans =  await SumoAPI.products(distributorData[0]);
        return plans;
    },
}
