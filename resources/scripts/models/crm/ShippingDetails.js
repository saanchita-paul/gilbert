export default class ShippingDetails {
    is_shipping_same = true;
    unit_number = "";
    street_number = "";
    shipping_address_text = "";
    shipping_street_address = "";
    shipping_city = "";
    shipping_postcode = "";
    shipping_state = "";
    shipping_country = "";
    shipping_unit_number = "";
    shipping_street_number = "";
    shipping_street_name = "";
    shipping_street_name_only = "";

    /**
     * Address constructor
     *
     * @param unit_number
     * @param street_number
     * @param is_shipping_same
     * @param shipping_address_text
     * @param shipping_street_address
     * @param shipping_city
     * @param shipping_postcode
     * @param shipping_state
     * @param shipping_country
     * @param shipping_unit_number
     * @param shipping_street_number
     * @param shipping_street_name
     * @param shipping_street_name_only
     *
     */
    constructor({
                    unit_number = "",
                    street_number = "",
                    is_shipping_same = true,
                    shipping_address_text = "",
                    shipping_street_address = "",
                    shipping_city = "",
                    shipping_postcode = "",
                    shipping_state = "",
                    shipping_country = "",
                    shipping_unit_number = "",
                    shipping_street_number = "",
                    shipping_street_name = "",
                    shipping_street_name_only = ""
                } = {}) {
        this.unit_number = unit_number;
        this.street_number = street_number;
        this.is_shipping_same = is_shipping_same;
        this.shipping_address_text = shipping_address_text;
        this.shipping_street_address = shipping_street_address;
        this.shipping_city = shipping_city;
        this.shipping_postcode = shipping_postcode;
        this.shipping_state = shipping_state;
        this.shipping_country = shipping_country;
        this.shipping_unit_number = shipping_unit_number;
        this.shipping_street_number = shipping_street_number;
        this.shipping_street_name = shipping_street_name;
        this.shipping_street_name_only = shipping_street_name_only;
    }
}
