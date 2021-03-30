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
