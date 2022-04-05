import DayJs from "dayjs";
import DATE_FORMAT from "@scripts/data/constants/DATE_FORMAT";
import {isNull} from "lodash-es";
import { street_type } from "@scripts/data/constants/StreetType";

export default class ApplicationSummary {
    id = null;
    first_name = '';
    middle_name = '';
    last_name = '';
    dob = null; //dob
    phone = null;
    inspection_time = null;
    has_electricity = 1;
    international_phone = null;
    homephone = null;
    phone_type = null;
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
    after_hour_payee =  null;
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
            international_phone = null,
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
            state_short = null,
            unit_number = null,
            street_number = null,
            street_name = null,
            street_name_only = null,
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
            billing_street_type = null,
            billing_street_number = null,
            billing_street_name = null,
            billing_address_text = null,
            billing_address_unit = null,
            billing_street_address = null,
            billing_city = null,
            billing_postcode = null,
            billing_state = null,
            billing_mannual_address = false,
            is_billing_same = true,
            authorizedPersonName = null,
            is_contacted = true,
            agent_name = '',
            agency_office = '',
            lead_source = '',
            lead_source_description = '',
            fast_connect_customer_reference = null,
            is_auto_water_submit = null,
            source = 0,
            created_by_agent = null,
            is_temporary_connection = 0,
            connection_end_date = null,
            after_hour_payee = null,
            mannual_address = false,
            street_type = null,
            billing_state_short = null,
            billing_street_name_only = null,
            is_address_complete = null,
            billing_is_address_complete = null,
        }
    ) {

        this.id = id;
        this.applicant_name = ( title == null ? '' : title ) + ' ' + first_name + ' '+ ( isNull(middle_name)?'': middle_name) + ' ' + last_name;
        this.first_name = first_name;
        this.middle_name = middle_name;
        this.last_name = last_name;
        this.date_of_birth = date_of_birth;
        this.inspection_time = inspection_time;
        this.has_electricity = has_electricity;
        this.dob = date_of_birth;
        this.phone = phone;
        this.international_phone = international_phone;
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
        this.billing_unit_number = billing_unit_number,
        this.billing_street_number = billing_street_number,
            this.billing_street_name = billing_street_name,
            this.billing_state = billing_state,
            this.billing_street_type = this.mapStreetType(billing_street_type),
            this.billing_address_text = billing_address_text,
            this.billing_street_number = billing_street_number,
            this.billing_address_unit = billing_address_unit,
            this.billing_street_address = billing_street_address,
            this.billing_city = billing_city,
            this.billing_postcode = billing_postcode,
            this.billing_mannual_address = billing_mannual_address,
            this.is_billing_same = is_billing_same,
            this.is_contacted = is_contacted,
        this.agent_name =    agent_name
        this.agency_office =    agency_office

        this.lead_source = lead_source
        this.lead_source_description = lead_source_description
        this.source = source
        this.created_by_agent = created_by_agent
        this.is_temporary_connection = is_temporary_connection
        this.connection_end_date = connection_end_date
        this.fast_connect_customer_reference = fast_connect_customer_reference
        this.is_auto_water_submit = is_auto_water_submit
        this.after_hour_payee = after_hour_payee
        this.mannual_address = mannual_address
        this.street_type = this.mapStreetType(street_type)
        this.state_short = state_short
        this.street_name_only = street_name_only
        this.billing_state_short = billing_state_short
        this.billing_street_name_only = billing_street_name_only
        this.is_address_complete = is_address_complete
        this.billing_is_address_complete = billing_is_address_complete
    }

    mapStreetType(type){
        let streetType = null
        street_type.forEach(element => {
            if(element.text == type){
                streetType = element.value
            }
        })
        return streetType ?? type;
    }

    mapStatus(status) {
        status = status - 1;
        if (status < 0) return '';

        const statusList = ['Unassigned', 'Assigned', 'Escalated', 'Submitted', 'Accepted', 'Rejected', 'Inprogress', 'Closed'];
        return statusList[status];
    }
}
