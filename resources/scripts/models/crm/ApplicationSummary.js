import DayJs from "dayjs";
import DATE_FORMAT from "@scripts/data/constants/DATE_FORMAT";

export default class ApplicationSummary {
    id = null;
    first_name = null;
    last_name = null;
    dob = null; //dob
    phone = null;
    email = null;
    moving_date = null;
    email_billing = null; //is_email_billing
    tenancy_type = null;
    address_unit = null;
    street_address = null;
    street_name = null;
    city = null;
    state = null;
    country = 'Australia';
    postcode = null;
    address_text = null;
    unit_number = null;
    street_number = null;
    service_interests = [];
    additional_instruction = null;
    applicant_name = null;
    identification = null;
    mirn = null;
    nmi = null;
    property_type = null;
    has_life_support = null;
    has_solar = null;
    status = null;
    constructor(
        {
            id = null,
            title = 'Mr',
            first_name = null,
            last_name = null,
            date_of_birth = null, //dob
            phone = null,
            email = null,
            moving_date = null,
            is_email_billing = null, //is_email_billing
            tenancy_type = null,
            address_unit = null,
            street_address = null,
            city = null,
            state = null,
            unit_number = null,
            street_number = null,
            street_name = null,
            country = 'Australia',
            postcode = null,
            address_text = null,
            services = [],
            additional_instruction = null,
            identification= null,
            mirn = null,
            nmi = null,
            has_life_support = null,
            has_solar = null,
            property_type = null,
            status = null,
        }
    ) {

        this.id = id;
        this.applicant_name = first_name + ' ' + last_name;
        this.first_name = first_name;
        this.last_name = last_name;
        this.date_of_birth = date_of_birth;
        this.dob = date_of_birth;
        this.phone = phone;
        this.email = email;
        this.moving_date = moving_date? new DayJs(moving_date).format(DATE_FORMAT.DB_DATE):null;
        this.is_email_billing = is_email_billing;
        this.tenancy_type = tenancy_type;
        this.tenancy_type = tenancy_type;
        this.address_unit = address_unit;
        this.street_address = street_address;
        this.street_address = street_address;
        this.city = city;
        this.state = state;
        this.country = country;
        this.postcode = postcode;
        this.address_text = address_text;
        this.service_interests = services;
        this.additional_instruction = additional_instruction;
        this.title = title;
        this.identification = identification;
        this.mirn = mirn;
        this.nmi = nmi;
        this.has_life_support = has_life_support;
        this.has_solar = has_solar;
        this.property_type = property_type;
        this.status = this.mapStatus(status);
        this.street_number = street_number;
        this.unit_number = unit_number;
        this.street_name = street_name;

    }

     mapStatus(status)
    {
        status = status - 1;
        if(status < 0) return  '';
        const statusList = ['UnAssigned','Assigned', 'Escalated','Submitted', 'Accepted', 'Rejected'];
        return statusList[status];
    }
}
