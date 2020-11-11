import DayJS from 'dayjs'
import DATE_FORMAT from "@scripts/data/constants/DATE_FORMAT";


export default class DateRange {
    constructor({start, end} = {}) {
        this.start = start || new DayJS().format(DATE_FORMAT.DB_DATE);
        // this.start = start || '2020-03-01';
        this.end = end || new DayJS().format(DATE_FORMAT.DB_DATE);
    }
}
