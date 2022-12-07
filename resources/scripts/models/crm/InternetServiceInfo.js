import Address from "@scripts/models/crm/Address";

export default class InternetServiceInfo {
    is_need_home_phone = false;
    is_back_to_base = false;
    is_security_alarm = false;
    is_existing_landline = false;
    otp = null;
    modem_type = null;
    charity_state = null;
    home_phone_number = null;
    current_provider = null;
    account_number = null;
    home_plan_provder = null;
    home_plan_type = null;
    is_shipping_same = true;
    address = new Address();

    /**
     * Internet service info constructor
     *
     * @param is_need_home_phone
     * @param is_back_to_base
     * @param is_security_alarm
     * @param is_existing_landline
     * @param otp
     * @param modem_type
     * @param charity_state
     * @param home_phone_number
     * @param current_provider
     * @param account_number
     * @param home_plan_provder
     * @param home_plan_type
     * @param is_shipping_same
     * @param address
     *
     */
    constructor({
                    is_need_home_phone = false,
                    is_back_to_base = false,
                    is_security_alarm = false,
                    is_existing_landline = false,
                    otp = null,
                    modem_type = null,
                    charity_state = null,
                    home_phone_number = null,
                    current_provider = null,
                    account_number = null,
                    home_plan_provder = null,
                    home_plan_type = null,
                    is_shipping_same = true,
                    address = new Address(),
                } = {}) {
        this.is_need_home_phone = is_need_home_phone;
        this.is_back_to_base = is_back_to_base;
        this.is_security_alarm = is_security_alarm;
        this.is_existing_landline = is_existing_landline;
        this.otp = otp;
        this.modem_type = modem_type;
        this.charity_state = charity_state;
        this.home_phone_number = home_phone_number;
        this.current_provider = current_provider;
        this.account_number = account_number;
        this.home_plan_provder = home_plan_provder;
        this.home_plan_type = home_plan_type;
        this.is_shipping_same = is_shipping_same;
        this.address = address;
    }
}
