export default class CustomerOtherService {
    /**
     * CustomerOtherService constructor
     *
     * @param id
     * @param category_name
     * @param postcode
     * @param business_name
     */
    constructor({id, category_name, postcode, business_name } = {}) {
        this.id = id || null;
        this.category_name =  category_name || '';
        this.postcode =  postcode || '';
        this.business_name =  business_name || '';
    }
}
