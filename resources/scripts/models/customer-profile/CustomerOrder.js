export default class CustomerOrder {
    /**
     *  Constructor of CustomerOrder
     *
     * @param {number | null} id
     * @param {string} total_cost
     * @param {string} service_type
     * @param {string} equipments
     * @param {string} moving_time
     * @param {string[]} extra_services
     * @param {string} payment_type
     * @param {string} status
     */
    constructor({id, total_cost, service_type, equipments, moving_time, extra_services, payment_type, status} = {}) {
        this.id = id || null;
        this.total_cost = total_cost || 0;
        this.service_type = service_type || '';
        this.equipments = equipments || [];
        this.moving_time = moving_time || '';
        this.extra_services = extra_services || [];
        this.payment_type = payment_type || 0;
        this.status = status ||'';
    }
}
