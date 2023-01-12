export default {
    SPECIAL_NUMBER_DD: [1,2,3,4,5,6,7,8],
    ACCESS_REQUIREMENT: [
        {
            text: "Yes",
            value: 1,
        },
        {
            text: "No",
            value: 0,
        },
    ],
    SOLAR_POWER_DD: [
        {
            text: "Yes",
            value: 'solar',
        },
        {
            text: "No",
            value: "no_solar",
        },
    ],
    IDENTIFICATION_TYPE_DD: [
        {
            text: "Passport",
            value: 'identity_passport',
        },
        {
            text: "Driver's License",
            value: 'identity_driving_license',
        },
        {
            text: "Medicare Card",
            value: 'identity_medicare',
        },
    ],
    COLOR_DD: [
        {
            text: "Green",
            value: "green",
        },
        {
            text: "Blue",
            value: "blue",
        },
        {
            text: "Yellow",
            value: "yellow",
        },
    ],
    STATES_DD: [
        { text: "NSW", value: "New South Wales" },
        { text: "VIC", value: "Victoria" },
        { text: "QLD", value: "Queensland" },
        { text: "SA", value: "South Australia" },
        { text: "NT", value: "Northern Territory" },
        { text: "TAS", value: "Tasmania" },
        { text: "ACT", value: "Australian Capital Territory" },
        { text: "WA", value: "Western Australia" }, // TODO state definition can be updated
    ],
    TENANT_TYPE_DD: [
        {
            text: "Residential",
            value: "1",
        },
        {
            text: "Business",
            value: "0",
        },
        {
            text: "Rea Partner",
            value: "2",
        },
    ],

    HOME_RENOVATION_DD: [
        {
            text: "No",
            value: 0,
        },
        {
            text: "Yes",
            value: 1,
        },
    ],
    ELECTRICITY_DD: [
        {
            text: "No",
            value: 0,
        },
        {
            text: "Yes",
            value: 1,
        },
    ],
    FORM_DATA : {
        power_status: null,
        gas_status: null,
    },
    POWER_STATUS : [
        {
            id: 13,
            type: "service",
            display_text: "Accepted",
            display_text_alias: "Connected",
            status_value: 5,
            text: "Accepted",
            value: 5
        },
        {
            id: 15,
            type: "service",
            display_text: "Not Submitted",
            display_text_alias: "In progress",
            status_value: 7,
            text: "Not Submitted",
            value: 7
        },
        {
            id: 17,
            type: "service",
            display_text: "Rejected",
            display_text_alias: "Rejected",
            status_value: 9,
            text: "Rejected",
            value: 9
        },
        {
            id: 19,
            type: "service",
            display_text: "Manual Processing",
            display_text_alias: "Manual Processing",
            status_value: 11,
            text: "Manual Processing",
            value: 11
        },
        {
            id: 20,
            type: "service",
            display_text: "In Progress",
            display_text_alias: "In Progress",
            status_value: 12,
            text: "In Progress",
            value: 12
        }
    ],
    GAS_STATUS : [
        {
            id: 13,
            type: "service",
            display_text: "Accepted",
            display_text_alias: "Connected",
            status_value: 5,
            text: "Accepted",
            value: 5
        },
        {
            id: 15,
            type: "service",
            display_text: "Not Submitted",
            display_text_alias: "In progress",
            status_value: 7,
            text: "Not Submitted",
            value: 7
        },
        {
            id: 17,
            type: "service",
            display_text: "Rejected",
            display_text_alias: "Rejected",
            status_value: 9,
            text: "Rejected",
            value: 9
        },
        {
            id: 19,
            type: "service",
            display_text: "Manual Processing",
            display_text_alias: "Manual Processing",
            status_value: 11,
            text: "Manual Processing",
            value: 11
        },
        {
            id: 20,
            type: "service",
            display_text: "In Progress",
            display_text_alias: "In Progress",
            status_value: 12,
            text: "In Progress",
            value: 12
        }
    ],
    EXPANSION_PANEL : [0],
    CONCESSION_CARD: [
        {
            text: "DVA Health",
            value: 1,
        },
        {
            text: "Health Care Card",
            value: 2,
        },
        {
            text: "Pensioner Concession",
            value: 3,
        },
        {
            text: "Queensland Seniors",
            value: 4,
        },
    ],
}
