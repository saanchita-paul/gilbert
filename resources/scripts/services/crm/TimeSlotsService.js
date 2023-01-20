import dayjs from "dayjs";

const getFullDayTime = timeSlot => {
    const startTime = dayjs(timeSlot.start_time, "HH:mm").format('HH:mm');
    const endTime = dayjs(timeSlot.end_time, "HH:mm").format('HH:mm');
    return startTime === '00:00' && endTime === '23:59';
}

export default {
    getFullDayTime
}
