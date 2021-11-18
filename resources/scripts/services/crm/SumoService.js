import SumoAPI from "@scripts/api/crm/SumoAPI"


export default {
    getPlans: async (address , service_interests) => {
            try {
                let service_type = 'D';
                if( service_interests.some(n=>n=='gas') ){
                    service_type = 'G'
                } else if(service_interests.some(n=>n=='power')){
                    service_type = 'E'
                } else if(service_interests.some(n=>n=='gas') && service_interests.some(n=>n=='gas') ){
                    service_type = 'D'
                }
                console.log('service type ' , service_type);
                let distributorData =  await SumoAPI.qualifyAddress(address);
                console.log('printing address ' , address)
                let plans =  await SumoAPI.products(distributorData[0] , service_type);
                return plans;
            } catch (error) {
                throw error;
            }

    },
}
