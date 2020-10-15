import DayJS from 'dayjs'
import DATE_FORMAT from "@scripts/data/constants/DATE_FORMAT";


export default class DateRange {
    constructor({start, end} = {}) {
        this.start = start || new DayJS().format(DATE_FORMAT.DB_DATE);
        this.end = end || new DayJS().format(DATE_FORMAT.DB_DATE);
    }
}
