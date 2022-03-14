import EnergyApi from "@scripts/api/ea/EnergyApi";
import EAPlanService from "@scripts/services/ea/EAPlanService";
import {STATES} from "@scripts/data/constants/STATES";
import dayJs from "dayjs";

const SELECTED_STATE_DISTRIBUTOR = [
    {
        state: 'VICTORIA',
        distributor: 'AUSNET SERVICES'
    },
    {
        state: 'SOUTH AUSTRALIA',
        distributor: 'ETSA'
    },
    {
        state: 'NEW SOUTH WALES',
        distributor: 'AUSGRID'
    },

];

function isTodayAfterHourFlag(ea_distributor, state) {
    const selectedState = STATES.find(st => st.value === state);
    const selectedDistributor = SELECTED_STATE_DISTRIBUTOR.find(dis => {
        return dis.distributor === ea_distributor && dis.state === selectedState.text?.toUpperCase()
        return dis.distributor === ea_distributor && dis.state === selectedState.value?.toUpperCase()
    })
    let  time = parseInt(dayJs().format('Hm'));

    if(selectedDistributor && time <= 1130) {
        return false;
    }

    return true;

}

function isTomorrowAfterHourFlag() {
    const  time = parseInt(dayJs().format('Hm'));

    if(time <= 1130) {
        return false;
    }

    return true;
}

export default {

    getElectricityDistributor: async (services, plan, postcode, state) => {
        if (!plan || !postcode || !state) {
            return '';
        }
        try{
            const data = await EAPlanService.getPlanDetailsByPlanType({
                service_type: services,
                plan_type: plan,
                postcode: postcode,
                state: state
            });
            const electricityPlan = data?.distributor_name?.electricity?.toUpperCase();
            if(!electricityPlan) {
                return '';
            }
            return electricityPlan;
        }catch (e) {
            console.log('error', e);
            return '';
        }
    },

    calculateAfterHourFlag: (ea_distributor, movingDate, state) => {
        if(!ea_distributor) {
            return false;
        }

       let currentDate = parseInt(dayJs().format('D'));
       let givenDate = parseInt(dayJs(movingDate).format('D'));

       if(currentDate === givenDate) {
           return isTodayAfterHourFlag(ea_distributor, state);
       }
       if(currentDate + 1 === givenDate) {
            return isTomorrowAfterHourFlag();
        }
       return false;
    },



}
