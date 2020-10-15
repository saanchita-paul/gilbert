export default class DateRange {
    constructor({start, end} = {}) {
        this.start = start || (new Date()).toISOString();
        this.end = end || (new Date()).toISOString();
    }
}
