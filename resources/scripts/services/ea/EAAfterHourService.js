import EAPlanService from "@scripts/services/ea/EAPlanService";
import {STATES} from "@scripts/data/constants/STATES";
import dayJs, * as dayjs from "dayjs";
import utc from "dayjs/plugin/utc"


dayjs.extend(utc);

const envTimezone = process.env.MIX_EA_AU_TIME_ZONE || 11;


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
        return dis.distributor === ea_distributor && dis.state === selectedState.value?.toUpperCase()
    })

    let dateTime = dayJs().utcOffset(parseInt(envTimezone) * 60);
    let  time = parseInt(dateTime.format('Hm'));
    if (selectedDistributor && time <= 1130) {
        return false;
    }

    return true;

}

function isTomorrowAfterHourFlag() {
    let dateTime = dayJs().utcOffset(parseInt(envTimezone) * 60);
    const  time = parseInt(dateTime.format('Hm'));

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

        let currentDate = parseInt(dayJs().utcOffset(parseInt(envTimezone) * 60).format('D'))
        // let currentDate = parseInt(dayJs().format('D'));
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
