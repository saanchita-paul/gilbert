export default class MriOffice {
    /**
     * @param {string} key
     * @param {string} company_name
     * @param {string} activation_date
     */
    constructor({key, company_name, activation_date} = {}) {
        this.key = key;
        this.company_name = company_name;
        this.activation_date = activation_date;
    }
}

