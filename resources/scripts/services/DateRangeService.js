import dayjs from "dayjs";
import DATE_FORMAT from "@scripts/data/constants/DATE_FORMAT";

export const getTodayString = () => {
    return dayjs().format(DATE_FORMAT.DATE_DASH);
}
export const getYesterdayString = () => {
    return dayjs().subtract(1, "day").format(DATE_FORMAT.DATE_DASH);
}
export const getFormattedDateString = (date) => {
    return dayjs(date).format(DATE_FORMAT.DATE_DASH);
}
export const getToday = () => {
    return dayjs(dayjs().format(DATE_FORMAT.DATE_DASH));
}
export const getYesterday = () => {
    return dayjs(dayjs().subtract(1, "day").format(DATE_FORMAT.DATE_DASH));
}
export const isSame = (date1, date2) => {
    return dayjs(date1).isSame(dayjs(date2), "day");
}
export const isBefore = (date1, date2) => {
    return dayjs(date1).isBefore(dayjs(date2), "day");
}
export const isAfter = (date1, date2) => {
    return dayjs(date1).isBefore(dayjs(date2), "day");
}
export const getSlashDate = (date) => {
    return dayjs(date).format(DATE_FORMAT.DATE_SHASH);
}