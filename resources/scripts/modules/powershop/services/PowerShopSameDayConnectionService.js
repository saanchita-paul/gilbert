import dayjs from "dayjs";

const VICTORIA_TIME = '15:00:00';

const validateSameDayConnection = (data) => {
     // validate cutoff time electricity
    return isValidElectricity(data);
}

const isValidElectricity = (data) => {
    const today = new Date();
    const currentTime = today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();
    console.log('Current ', currentTime);
    console.log('Victoria ', VICTORIA_TIME);

    if (isSameDay(data.moving_date) && data.state === 'Victoria' && currentTime <= VICTORIA_TIME) {
        return 'Invalid';
    }

    if (isSameDay(data.moving_date) && data.state === 'New South Wales' && currentTime > '1 PM') {
        return 'Invalid';
    }

    if (isSameDay(data.moving_date) && data.state === 'Australian Capital Territory') {
        return false;
    }
    return true;
}

const isSameDay = movingDate => dayjs().isSame(dayjs(movingDate, 'DD/MM/YYYY').format('YYYY-MM-DD'), 'day');

export default {
    validateSameDayConnection
};
