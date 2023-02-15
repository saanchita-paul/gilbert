class Source {
    /**
     * Source Filter
     *
     * @param {number} id
     * @param {string} name
     * @param {string} value
     * @param {string} logo
     */
    constructor({id, name, value, logo} = {}) {
        this.id = id;
        this.text = name;
        this.value = value;
        this.icon = logo;
    }
}

export default Source;
