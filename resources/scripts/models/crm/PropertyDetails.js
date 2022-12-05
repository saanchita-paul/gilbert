export default class PropertyDetails {
    moving_date = "";
    billing_address = "";
    property_type = "";
    life_support = "";
    solor_power = "";
    nmi = "";
    mirn = "";
    address_text = "";
    street_address = "";
    city = "";
    postcode = "";
    state = "";
    country = "";
    street_type = "";
    street_name = "";
    street_name_only = "";

    is_renovation_on = false;
    has_electricity = true;
    inspection_time = null;

    unit_number = "";
    street_number = "";
    is_billing_same = true;
    billing_address_text = "";
    billing_street_address = "";
    billing_city = "";
    billing_postcode = "";
    billing_state = "";
    billing_country = "";
    billing_unit_number = "";
    billing_street_number = "";
    billing_street_name = "";
    billing_street_name_only = "";
    connection_end_date = null;
    is_temporary_connection = null;

    is_access_require = null;
    is_gas_life_support = null;
    is_any_unrestrained_animal = null;
    additional_access_information = null;
    is_power_life_support = null;

    /**
     * Address constructor
     *
     * @param moving_date
     * @param billing_address
     * @param property_type
     * @param life_support
     * @param solor_power
     * @param nmi
     * @param mirn
     * @param address_text
     * @param street_address
     * @param city
     * @param postcode
     * @param state
     * @param country
     * @param street_type
     * @param street_name
     * @param street_name_only
     *
     * @param is_renovation_on
     * @param has_electricity
     * @param inspection_time
     *
     * @param unit_number
     * @param street_number
     * @param is_billing_same
     * @param billing_address_text
     * @param billing_street_address
     * @param billing_city
     * @param billing_postcode
     * @param billing_state
     * @param billing_country
     * @param billing_unit_number
     * @param billing_street_number
     * @param billing_street_name
     * @param billing_street_name_only
     * @param connection_end_date
     * @param is_temporary_connection
     *
     * @param is_access_require
     * @param is_gas_life_support
     * @param is_any_unrestrained_animal
     * @param additional_access_information
     * @param is_power_life_support
     *
     */
    constructor({
                    moving_date = "",
                    billing_address = "",
                    property_type = "",
                    life_support = "",
                    solor_power = "",
                    nmi = "",
                    mirn = "",
                    address_text = "",
                    street_address = "",
                    city = "",
                    postcode = "",
                    state = "",
                    country = "",
                    street_type = "",
                    street_name = "",
                    street_name_only = "",

                    is_renovation_on = false,
                    has_electricity = true,
                    inspection_time = null,

                    unit_number = "",
                    street_number = "",
                    is_billing_same = true,
                    billing_address_text = "",
                    billing_street_address = "",
                    billing_city = "",
                    billing_postcode = "",
                    billing_state = "",
                    billing_country = "",
                    billing_unit_number = "",
                    billing_street_number = "",
                    billing_street_name = "",
                    billing_street_name_only = "",
                    connection_end_date = null,
                    is_temporary_connection = null,

                    is_access_require = null,
                    is_gas_life_support = null,
                    is_any_unrestrained_animal = null,
                    additional_access_information = null,
                    is_power_life_support = null
                } = {}) {
        this.moving_date = moving_date;
        this.billing_address = billing_address;
        this.property_type = property_type;
        this.life_support = life_support;
        this.solor_power = solor_power;
        this.nmi = nmi;
        this.mirn = mirn;
        this.address_text = address_text;
        this.street_address = street_address;
        this.city = city;
        this.postcode = postcode;
        this.state = state;
        this.country = country;
        this.street_type = street_type;
        this.street_name = street_name;
        this.street_name_only = street_name_only;

        this.is_renovation_on = is_renovation_on;
        this.has_electricity = has_electricity;
        this.inspection_time = inspection_time;

        this.unit_number = unit_number;
        this.street_number = street_number;
        this.is_billing_same = is_billing_same;
        this.billing_address_text = billing_address_text;
        this.billing_street_address = billing_street_address;
        this.billing_city = billing_city;
        this.billing_postcode = billing_postcode;
        this.billing_state = billing_state;
        this.billing_country = billing_country;
        this.billing_unit_number = billing_unit_number;
        this.billing_street_number = billing_street_number;
        this.billing_street_name = billing_street_name;
        this.billing_street_name_only = billing_street_name_only;
        this.connection_end_date = connection_end_date;
        this.is_temporary_connection = is_temporary_connection;

        this.is_access_require = is_access_require;
        this.is_gas_life_support = is_gas_life_support;
        this.is_any_unrestrained_animal = is_any_unrestrained_animal;
        this.additional_access_information = additional_access_information;
        this.is_power_life_support = is_power_life_support;
    }
}
