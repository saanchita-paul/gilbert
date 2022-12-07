export default class Address {
    is_same = true;
    unit_number = null;
    street_number = null;
    street_name_only = null;
    address_text = null;
    street_address = null;
    street_type = null;
    city = null;
    postcode = null;
    state = null;
    country = null;

    /**
     * Address constructor
     *
     * @param is_same
     * @param address_text
     * @param street_address
     * @param city
     * @param postcode
     * @param state
     * @param country
     * @param street_type
     * @param street_name_only
     * @param unit_number
     * @param street_number
     *
     */
    constructor({
                    is_same = true,
                    address_text = null,
                    street_address = null,
                    city = null,
                    postcode = null,
                    state = null,
                    country = null,
                    street_type = null,
                    street_name_only = null,
                    unit_number = null,
                    street_number = null,
                } = {}) {
        this.is_same = is_same;
        this.address_text = address_text;
        this.street_address = street_address;
        this.city = city;
        this.postcode = postcode;
        this.state = state;
        this.country = country;
        this.street_type = street_type;
        this.street_name_only = street_name_only;
        this.unit_number = unit_number;
        this.street_number = street_number;
    }
}
