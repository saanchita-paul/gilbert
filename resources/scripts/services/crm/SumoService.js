import SumoAPI from "@scripts/api/crm/SumoAPI"


export default {
    getPlans: async (address , service_interests , agent , lead) => {
            try {
                let service_type = '';
                if( service_interests.some(n=>n=='power') && service_interests.some(n=>n=='gas') ){
                    service_type = 'D'
                } else if(service_interests.some(n=>n=='power')){
                    service_type = 'E'
                } else if(service_interests.some(n=>n=='gas') ){
                    service_type = 'G'
                }else {
                    throw 'plan not selected';
                }

                let distributorData =  await SumoAPI.qualifyAddress(address , lead.id);

                let agent_name = '';
                if(agent){
                    agent_name = agent.id + '_' + agent.first_name + '_' + agent.last_name;
                } else {
                    agent_name = 'Hood CSR'
                }

                // let plans;
                // if(distributorData[0].electricityDistributors.distributor){
                let plans =  await SumoAPI.products(distributorData[0] , service_type , agent_name , lead.id);
                return plans;
                // }
                // throw 'No plans found'

            } catch (error) {
                console.log('in the error of sumo service service , getPlans method')
                throw error;
            }

    },
}
