import dayjs from "dayjs";

const VICTORIA_TIME = '15:00:00';

const validateSameDayConnection = (movingDate, state) => {
     // validate cutoff time electricity
    return isValidElectricity(movingDate, state);
}

const isValidElectricity = (movingDate, state) => {
    const today = new Date();
    const currentTime = today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();

    if (isSameDay(movingDate) && state === 'Victoria' && currentTime >= VICTORIA_TIME) {
        return false;
    }

    if (isSameDay(movingDate) && state === 'New South Wales' && currentTime > '1 PM') {
        return false;
    }

    if (isSameDay(movingDate) && state === 'Australian Capital Territory') {
        return false;
    }

    return true;
}

const isSameDay = movingDate => dayjs().isSame(dayjs(movingDate, 'DD/MM/YYYY').format('YYYY-MM-DD'),'day');

export default {
    validateSameDayConnection
};
