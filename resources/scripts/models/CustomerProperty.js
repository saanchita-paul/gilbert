export default class CustomerProperty {
    /**
     *  Constructor of CustomerProperty
     *
     * @param {number | null} id
     * @param {string} property_address_text
     * @param {string} occupation_type
     * @param {string} house_type
     * @param {number} house_size
     * @param {string[]} activities
     */
    constructor({id, property_address_text, occupation_type, house_type, house_size, activities} = {}) {
        this.id = id || null;
        this.property_address_text = property_address_text || '';
        this.occupation_type = occupation_type || '';
        this.house_type = house_type || '';
        this.house_size = house_size || 0;
        this.activities = activities || [];
    }
}
