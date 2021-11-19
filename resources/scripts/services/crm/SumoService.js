import SumoAPI from "@scripts/api/crm/SumoAPI"


export default {
    getPlans: async (address , service_interests , agent) => {
            try {
                let service_type = 'D';
                if( service_interests.some(n=>n=='gas') && service_interests.some(n=>n=='gas') ){
                    service_type = 'D'
                } else if(service_interests.some(n=>n=='power')){
                    service_type = 'E'
                } else if(service_interests.some(n=>n=='gas') ){
                    service_type = 'G'
                }
                console.log('service type' , service_type);
                let distributorData =  await SumoAPI.qualifyAddress(address);
                console.log('printing address ' , address)

                let agent_name = '';  
                if(agent){
                    agent_name = agent.id + '_' + agent.first_name + '_' + agent.last_name;
                }

                // let plans;
                console.log(distributorData[0])
                // if(distributorData[0].electricityDistributors.distributor){
                let plans =  await SumoAPI.products(distributorData[0] , service_type , agent_name);
                return plans;
                // }
                // throw 'No plans found'

            } catch (error) {
                throw error;
            }

    },
}
