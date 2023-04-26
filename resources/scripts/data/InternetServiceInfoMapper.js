import InternetServiceInfo from "@scripts/models/crm/InternetServiceInfo";
import Address from "@scripts/models/crm/Address";

export default {
    mapData: internetServiceInfo => {
        let data = internetServiceInfo ? internetServiceInfo : new InternetServiceInfo();
        let address = new Address({
            is_same: data.is_shipping_same === 1,
            address_text: data.address_text ?? null,
            street_address: data.street_address ?? null,
            city: data.city ?? null,
            postcode: data.postcode ?? null,
            state: data.state ?? null,
            street_type: data.street_type ?? null,
            street_name_only: data.street_name_only ?? null,
            unit_number: data.unit_number ?? null,
            street_number: data.street_number ?? null,
        });
        return new InternetServiceInfo({
            is_need_home_phone: data.is_need_home_phone ?? false,
            is_back_to_base: data.is_back_to_base ?? false,
            is_security_alarm: data.is_security_alarm ?? false,
            is_existing_landline: data.is_existing_landline ?? false,
            otp: data.otp ?? null,
            modem_type: data.modem_type ?? null,
            charity: data.charity ?? null,
            is_shipping_same: data.is_shipping_same === 1,
            home_phone_number: data.home_phone_number ?? null,
            current_provider: data.current_provider ?? null,
            account_number: data.account_number ?? null,
            home_phone_provider: data.home_phone_provider ?? null,
            home_phone_plan: data.home_phone_plan ?? null,
            address: address,
            connection_service: data.connection_service ?? {},
        })
    },
}
