export const energyType = {
    ELECTRICITY: 'Electricity',
    GAS: 'Gas',
    ELECTRICITY_AND_GAS: 'Electricity and gas',
}

export const mapEnergyType = key => energyType[key.toUpperCase()]

export const accountType = {
    BUSINESS: 'Business',
    RESIDENTIAL: 'Residential',
}

export const mapAccountType = key => accountType[key.toUpperCase()]

export const interventionStatus = {
    NO_ISSUE: 'No issue',
    NEED_ASSISTANCE: 'Need assistance',
    IN_PROGRESS: 'In progress',
    RESOLVED: 'Resolved',
}

export const mapInterventionStatus = key => interventionStatus[key.toUpperCase()]



const planTypes = {
    basic_plan: 'Basic - Home',
    no_frills: 'No Frills (Home)',
    total_plan: 'Total Plan (Home)'
}
export const mapPlanType = key => planTypes[key?.toLowerCase()]


