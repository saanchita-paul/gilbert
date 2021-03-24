export default class CustomerConnection {
    /**
     *  Constructor of CustomerConnection
     *
     * @param {number | null} id
     * @param {string} connection_address_text
     * @param {string} provider
     * @param {string} plan_type
     * @param {string} selected_plan
     * @param {string} solar
     */
    constructor({id, connection_address_text, provider, energy_type, selected_plan, solar} = {}) {
        this.id = id || null;
        this.connection_address_text = connection_address_text || '';
        this.provider = provider || 'Energy Australia';
        this.energy_type = energy_type || '';
        this.selected_plan = selected_plan || 0;
        this.solar = solar || '';
    }
}
