// Modem types
export const MODEM_TYPES = [
    {text: "BYO Modem", value: "byo"},
    {text: "Standard Modem $225 once off", value: "standard"},
    {text: "Upgraded Modem $325 once off", value: "upgraded"},
];

// Charity
export const CHARITY = [
    {text: "OzHarvest", value: "oz_harvest"},
    {text: "Indigenous Literacy Foundation", value: "indigenous_literacy_foundation"},
    {text: "HeartKids", value: "heart_kids"},
    {text: "National Breast Cancer Foundation", value: "national_breast_cancer_foundation"},
    {text: "Asylum Seeker Resource Centre", value: "asylum_seeker_resource_centre"},
    {text: "ChildFund Australia", value: "child_fund_australia"},
    {text: "Rainforest Rescue", value: "rainforest_rescue"},
    {text: "Sleepbus", value: "sleepbus"},
    {text: "Burn Bright", value: "burn_bright"},
    {text: "Save-A-Dog Scheme", value: "save_a_dog_scheme"},
];

export const CHARITY_MAP = {
    "oz_harvest": "OzHarvest",
    "indigenous_literacy_foundation": "Indigenous Literacy Foundation",
    "heart_kids": "HeartKids",
    "national_breast_cancer_foundation": "National Breast Cancer Foundation",
    "asylum_seeker_resource_centre": "Asylum Seeker Resource Centre",
    "child_fund_australia": "ChildFund Australia",
    "rainforest_rescue": "Rainforest Rescue",
    "sleepbus": "Sleepbus",
    "burn_bright": "Burn Bright",
    "save_a_dog_scheme": "Save-A-Dog Scheme"
};

export const MODEM_TYPE_MAP = {
    "byo": "BYO Modem",
    "standard": "Standard Modem $225 once off",
    "upgraded": "Upgraded Modem $325 once off"
};

export default {
    MODEM_TYPES,
    CHARITY,
    CHARITY_MAP,
    MODEM_TYPE_MAP
};
