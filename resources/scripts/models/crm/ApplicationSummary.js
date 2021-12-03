import DayJs from "dayjs";
import DATE_FORMAT from "@scripts/data/constants/DATE_FORMAT";
import {isNull} from "lodash-es";

export default class ApplicationSummary {
    id = null;
    first_name = '';
    middle_name = '';
    last_name = '';
    dob = null; //dob
    phone = null;
    inspection_time = null;
    has_electricity = 1;
    homephone = null;
    phone_type = 1;
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
    is_renovation_on = 1;
    unit_number = null;
    street_number = null;
    service_interests = ['gas', 'power', 'water', 'internet'];
    connection_services = null;
    additional_instruction = null;
    applicant_name = null;
    identification = null;
    mirn = null;
    nmi = null;
    property_type = null;
    family_violance = 3;
    has_life_support = null;
    has_solar = null;
    status = null;
    billing_unit_number = null;
    billing_street_number = null;
    billing_street_name = null;
    billing_address_text = null;
    billing_address_unit = null;
    billing_street_address = null;
    billing_city = null;
    billing_postcode = null;
    is_billing_same = null;
    authorizedPersonName = null;
    is_contacted = true;
    agent_name = '';
    agency_office = '';
    source= 0;
    created_by_agent = null
    constructor(
        {
            id = null,
            title = '',
            first_name = '',
            middle_name = '',
            last_name = '',
            date_of_birth = null, //dob
            phone = null,
            homephone = null,
            inspection_time = null,
            has_electricity = 1,
            phone_type = 1,
            email = null,
            moving_date = null,
            family_violance = 3,
            is_email_billing = null, //is_email_billing
            tenancy_type = null,
            address_unit = null,
            is_renovation_on = 1,
            street_address = null,
            city = null,
            state = null,
            unit_number = null,
            street_number = null,
            street_name = null,
            country = 'Australia',
            postcode = null,
            address_text = null,
            services = ['gas', 'power', 'water', 'internet'],
            connection_services = null,
            additional_instruction = null,
            identification = null,
            mirn = null,
            nmi = null,
            has_life_support = null,
            has_solar = null,
            property_type = null,
            status = null,
            billing_unit_number = null,
            billing_street_number = null,
            billing_street_name = null,
            billing_address_text = null,
            billing_address_unit = null,
            billing_street_address = null,
            billing_city = null,
            billing_postcode = null,
            is_billing_same = null,
            authorizedPersonName = null,
            is_contacted = true,
            agent_name = '',
            agency_office = '',
            lead_source = '',
            lead_source_description = '',
            source = 0,
            created_by_agent = null
        }
    ) {

        this.id = id;
        this.applicant_name = first_name + ' '+ ( isNull(middle_name)?'': middle_name) + ' ' + last_name;
        this.first_name = first_name;
        this.middle_name = middle_name;
        this.last_name = last_name;
        this.date_of_birth = date_of_birth;
        this.inspection_time = inspection_time;
        this.has_electricity = has_electricity;
        this.dob = date_of_birth;
        this.phone = phone;
        this.homephone = homephone;
        this.phone_type = phone_type;
        this.email = email;
        this.family_violance = family_violance;
        this.moving_date = moving_date ? new DayJs(moving_date).format('YYYY-MM-DD') : null;
        this.is_email_billing = is_email_billing;
        this.tenancy_type = tenancy_type;
        this.is_renovation_on = is_renovation_on;
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
        this.connection_services = connection_services;
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
        this.authorizedPersonName = authorizedPersonName;
        this.billing_unit_number = billing_unit_number, this.billing_street_number = billing_street_number,
            this.billing_street_name = billing_street_name,
            this.billing_address_text = billing_address_text,
            this.billing_address_unit = billing_address_unit,
            this.billing_street_address = billing_street_address,
            this.billing_city = billing_city,
            this.billing_postcode = billing_postcode,
            this.is_billing_same = is_billing_same,
            this.is_contacted = is_contacted,
        this.agent_name =    agent_name
        this.agency_office =    agency_office

        this.lead_source = lead_source
        this.lead_source_description = lead_source_description
        this.source = source
        this.created_by_agent = created_by_agent

    }

    mapStatus(status) {
        status = status - 1;
        if (status < 0) return '';
        const statusList = ['Unassigned', 'Assigned', 'Escalated', 'Submitted', 'Accepted', 'Rejected', 'Inprogress', 'Closed'];
        return statusList[status];
    }
}
