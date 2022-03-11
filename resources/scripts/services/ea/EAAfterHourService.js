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
        if (!plan || !postcode || !state) return  '';
        try{
            const data = await EAPlanService.getPlanDetailsByPlanType({
                service_type: services,
                plan_type: plan,
                postcode: postcode,
                state: state
            });
            return data?.distributor_name?.electricity?.toUpperCase();
        }catch (e) {
            console.log('error', e);
            return '';
        }
    },

    calculateAfterHourFlag: (ea_distributor, movingDate, state) => {
        if(ea_distributor?.length === 0) {
            return false;
        }

       let currentDate = parseInt(dayJs().format('D'));
       let givenDate = parseInt(dayJs(movingDate).format('D'));
        // console.log('calculateAfterHourFlag',currentDate, givenDate, ea_distributor ,state);

       if(currentDate === givenDate) {
           // console.log('today distributor_name', ea_distributor ,state);
           return isTodayAfterHourFlag(ea_distributor, state);
       }
       if(currentDate + 1 === givenDate) {
           // console.log('tomorrow distributor_name', ea_distributor, state);
            return isTomorrowAfterHourFlag();
        }
       return false;
    },



}
