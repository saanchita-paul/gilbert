export default class CustomerDetails {
    constructor({ name,
                    profilePic,
                    lastInteractiveTime,
                    hoodUid,
                    messagerId,
                    email,
                    ph,
                    propertyPhofileId,
                    propertyEstimatedMovingPeriod,
                    propertyCurrentAddress,
                    propertyPriviousAddress,
                    propertyTenancyType,
                    propertyHouseType,
                    propertyHouseSize,
                    propertySolarPowered,
                    propertyLifeSupport,
                    connectionId,
                    connectionProvider,
                    connectionSelectedPlan,
                    connectionEneryType,
                    connectionAddress

                } = {}) {
        this.name = name || 'Sazzad';
        this.profile_pic = profilePic || 'https://fiverr-res.cloudinary.com/images/q_auto,f_auto/gigs/106438752/original/3c4d95e3604313ecca407541a45b6a58dcc67c5c/update-your-online-dating-profile-bio-to-get-you-more-matches.jpg';
        this.last_interactive_time = lastInteractiveTime || '10m';
        this.hood_uid = hoodUid || '#1671408219574925';
        this.messager_id = messagerId || '#1671408219574925';
        this.email = email || 'sazzadahmed41@gmail.com';
        this.ph = ph || '1671408219574925';

        this.property_profile_id = propertyPhofileId || '#1901';
        this.property_estimated_moving_period = propertyEstimatedMovingPeriod || '20/03/2020';
        this.property_currnt_address = propertyCurrentAddress || '26 Highpoint, Sunbury VIC 3429 Australia';
        this.property_previous_address = propertyPriviousAddress || '26 Highpoint, Sunbury VIC 3429 Australia';
        this.property_tenancy_type = propertyTenancyType || 'Rent';
        this.property_house_type = propertyHouseType || '--';
        this.property_house_size = propertyHouseSize || '--';
        this.property_solar_powered = propertySolarPowered || 'yes';
        this.property_life_support = propertyLifeSupport || 'Y/N';

        this.connection_id = connectionId || '#1901';
        this.connection_provider = connectionProvider || 'Energy Australia';
        this.connection_selected_plan = connectionSelectedPlan || 'Total Plan';
        this.connection_energy_type = connectionEneryType || '--';
        this.connection_address = connectionAddress || '26 Highpoint, Sunbury VIC 3429 Australia';
    }

}
