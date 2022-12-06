export default class InternetServiceInfo {
    is_shipping_same = true;
    unit_number = "";
    street_number = "";
    street_name_only = "";
    address_text = "";
    street_address = "";
    street_type = "";
    city = "";
    postcode = "";
    state = "";

    /**
     * Internet service info constructor
     *
     * @param is_shipping_same
     * @param unit_number
     * @param street_number
     * @param street_name_only
     * @param address_text
     * @param street_address
     * @param street_type
     * @param city
     * @param postcode
     * @param state
     *
     */
    constructor({
                    is_shipping_same = true,
                    unit_number = "",
                    street_number = "",
                    street_name_only = "",
                    address_text = "",
                    street_address = "",
                    street_type = "",
                    city = "",
                    postcode = "",
                    state = ""
                } = {}) {
        this.is_shipping_same = is_shipping_same;
        this.unit_number = unit_number;
        this.street_number = street_number;
        this.street_name_only = street_name_only;
        this.address_text = address_text;
        this.street_address = street_address;
        this.street_type = street_type;
        this.city = city;
        this.postcode = postcode;
        this.state = state;
    }
}
