// Email marketing dropdown options
export const BOOLEAN_DD = [
    {
        text: "Yes",
        value: 1,
    },
    {
        text: "No",
        value: 0,
    },
];

// States dropdown options
export const STATES_DD = [
    {text: "NSW", value: "New South Wales"},
    {text: "VIC", value: "Victoria"},
    {text: "QLD", value: "Queensland"},
    {text: "SA", value: "South Australia"},
    {text: "NT", value: "Northern Territory"},
    {text: "TAS", value: "Tasmania"},
    {text: "ACT", value: "Australian Capital Territory"},
    {text: "WA", value: "Western Australia"}, // TODO state definition can be updated
];

// Tenant type dropdown options
export const TENANT_TYPE_DD = [
    {
        text: "Renter",
        value: 1,
    },
    {
        text: "Owner",
        value: 2,
    },
];

// Phone type dropdown options
export const PHONE_TYPE_DD = [
    {
        text: "Mobile",
        value: 1,
    },
    {
        text: "Homephone",
        value: 2,
    },
    {
        text: "I. Mobile Number",
        value: 3,
    },
];

// Family violence type dropdown options
export const FAMILY_VIOLENCE_TYPE_DD = [
    {
        text: "Yes",
        value: 1,
    },
    {
        text: "No",
        value: 2,
    },
    {
        text: "Not Applicable",
        value: 3,
    },
];

// Property type dropdown options
export const PROPERTY_TYPE_DD = [
    {
        text: "Residential",
        value: 1,
    },
    {
        text: "Business",
        value: 2,
    },
];

// Inspection time NSW dropdown options
export const INSPECTION_TIME_NSW_DD = [
    {
        text: "8AM - 12PM (ENERGYAP)",
        value: "8:00am - 12:00pm"
    },
    {
        text: "1PM - 5PM (ENERGYAP)",
        value: "1:00pm - 5:00pm",
    },
];

// Additional access info dropdown options
export const ADDITIONAL_ACCESS_INFO_DD = [
    {
        text: "Customer on site",
        value: "CUST ON SITE",
    },
    {
        text: "Keys in meter box",
        value: "KEYS IN METER BOX",
    },
    {
        text: "Keys in letter box",
        value: "KEYS IN LETTER BOX",
    },
];

// Additional access info nsw dropdown options
export const ADDITIONAL_ACCESS_INFO_NSW_DD = [
    {
        text: "Customer on site",
        value: "CUST ON SITE",
    },
];


// Additional access info sa dropdown options
export const ADDITIONAL_ACCESS_INFO_SA_DD = [
    {
        text: "Customer consultation",
        value: "Customer Consultation",
    }
];

// Additional access info act dropdown options
export const ADDITIONAL_ACCESS_INFO_ACT_DD = [
    {
        text: "Off Supply",
        value: "off supply",
    }
];

// Inspection time QLD dropdown options
export const INSPECTION_TIME_QLD_DD = [
    {
        text: "8AM - 1PM (ENERGYXP)",
        value: "8:00am - 1:00pm",
    },
    {
        text: "9AM - 2PM (ENERGYXP)",
        value: "9:00am - 2:00pm",
    },
    {
        text: "10AM - 3PM  (ENERGYXP)",
        value: "10:00am - 3:00pm",
    },
    {
        text: "11AM - 4PM (ENERGYXP)",
        value: "11:00am - 4:00pm",
    },
    {
        text: "12PM - 5PM (ENERGYXP)",
        value: "12:00pm - 5:00pm",
    },
    {
        text: "1PM - 6PM (ENERGYXP)",
        value: "1:00pm - 6:00pm",
    },
];

// Life support DD
export const LIFE_SUPPORT_DD = [
    {
        text: "Yes",
        value: 1,
    },
    {
        text: "No",
        value: 2,
    },
];

// Identificatin type DD
export const IDENTIFICATION_TYPE_DD = [
    {
        text: "Passport",
        value: 1,
    },
    {
        text: "Driver's License",
        value: 2,
    },
    {
        text: "Medicare Card",
        value: 3,
    },
];

// Concession card DD
export const CONCESSION_CARD_DD = [
    {
        text: "DVA Health",
        value: "DVA",
    },
    {
        text: "Health Care Card",
        value: "HCC",
    },
    {
        text: "Pensioner Concession",
        value: "PCC",
    },
    {
        text: "Queensland Seniors",
        value: "QSC",
    },
];

// Color DD
export const COLOR_DD = [
    {
        text: "Green",
        value: "GREEN",
    },
    {
        text: "Blue",
        value: "BLUE",
    },
    {
        text: "Yellow",
        value: "YELLOW",
    },
];

export default {
    BOOLEAN_DD,
    STATES_DD,
    TENANT_TYPE_DD,
    PHONE_TYPE_DD,
    FAMILY_VIOLENCE_TYPE_DD,
    PROPERTY_TYPE_DD,
    INSPECTION_TIME_NSW_DD,
    ADDITIONAL_ACCESS_INFO_DD,
    ADDITIONAL_ACCESS_INFO_NSW_DD,
    ADDITIONAL_ACCESS_INFO_SA_DD,
    ADDITIONAL_ACCESS_INFO_ACT_DD,
    INSPECTION_TIME_QLD_DD,
    LIFE_SUPPORT_DD,
    IDENTIFICATION_TYPE_DD,
    CONCESSION_CARD_DD,
    COLOR_DD
};
