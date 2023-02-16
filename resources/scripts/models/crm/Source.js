class Source {
    /**
     * Source Filter
     *
     * @param {number} id
     * @param {string} name
     * @param {string} source_id
     * @param {string} logo
     */
    constructor({id, name, source_id, logo} = {}) {
        this.id = id;
        this.text = name;
        this.value = source_id;
        this.icon = logo;
    }
}

export default Source;
