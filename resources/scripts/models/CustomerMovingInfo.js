export default class CustomerMovingInfo {
    /**
     *  Constructor of CustomerMovingInfo
     *
     * @param {number | null} id
     * @param {string} origin_address_text
     * @param {string} distance_type
     * @param {string} service_type
     * @param {string} house_type
     * @param {number} house_size
     * @param {boolean} has_order
     */
    constructor({id, origin_address_text, distance_type, service_type, house_type, house_size, has_order} = {}) {
        this.id = id || null;
        this.origin_address_text = origin_address_text || '';
        this.distance_type = distance_type || '';
        this.service_type = service_type || '';
        this.house_type = house_type || '';
        this.house_size = house_size || 0;
        this.has_order = has_order ||false;
    }
}
