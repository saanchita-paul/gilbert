export default class CustomerDetails {
    constructor({
                    id,
                    full_name,
                    avatar,
                    last_interaction,
                    hood_ui,
                    facebook_id,
                    email,
                    phone,

                    propertyProfileId,
                    propertyAccountType,
                    propertyLifeSupport,
                    propertyTenancyType,
                    propertyIdType,
                    propertyEAResponseTime,
                    propertyEstimatedMovingPeriod,
                    propertyCurrentAddress,
                    propertyPriviousAddress,
                    propertyHouseType,
                    propertyHouseSize,
                    propertySolarPowered,
                    estimetedMovingPeriod,
                    userAgreeTime,
                    isManualAddress,
                    connectionId,
                    connectionProvider,
                    connectionSelectedPlan,
                    connectionEneryType,
                    connectionAddress,
                    connectionEnergyType,
                    connectionGasProvider,
                    connectionElectricyFee,
                    connectionEDestributor,
                    gasMeterCharge,
                    connectionMernNo,
                    connectionNmiNo,
                    connectionStatus,
                    connectionReason,
                    manualInterventionStatus,
                    manualInterventionIsActive,
                    manualInterventionAt

                } = {}) {
        this.id = id;
        this.name = full_name;
        this.profile_pic = avatar
        this.last_interaction = last_interaction;
        this.hood_uid = hood_ui;
        this.messager_id = facebook_id;
        this.email = email;
        this.ph = phone;

        this.property_profile_id = propertyProfileId || '#1901';
        this.property_account_type = propertyAccountType || '#1901';
        this.property_estimated_moving_period = propertyEstimatedMovingPeriod || '20/03/2020';
        this.property_currnt_address = propertyCurrentAddress || '26 Highpoint, Sunbury VIC 3429 Australia';
        this.property_previous_address = propertyPriviousAddress || '26 Highpoint, Sunbury VIC 3429 Australia';
        this.property_tenancy_type = propertyTenancyType || 'Rent';
        this.property_house_type = propertyHouseType || '--';
        this.property_house_size = propertyHouseSize || '--';
        this.property_solar_powered = propertySolarPowered || 'yes';
        this.property_life_support = propertyLifeSupport || 'Y/N';
        this.is_manual_address = isManualAddress || 'Y/N';
        this.estimeted_moving_period = estimetedMovingPeriod || '';
        this.property_ea_response_time = propertyEAResponseTime || '';
        this.user_agree_time = userAgreeTime || '';
        this.property_id_type = propertyIdType || '';

        this.connection_id = connectionId || '#1901';
        this.connection_provider = connectionProvider || 'Energy Australia';
        this.connection_selected_plan = connectionSelectedPlan || 'Total Plan';
        this.connection_energy_type = connectionEneryType || '--';
        this.connection_address = connectionAddress || '26 Highpoint, Sunbury VIC 3429 Australia';
        this.e_destributor = connectionEDestributor || 'Energy Australia';

        this.connection_energy_type = connectionEnergyType || '';
        this.connection_gas_provider = connectionGasProvider || '';
        this.connection_electricy_fee = connectionElectricyFee || '';
        this.connection_mern_no = connectionMernNo || '';
        this.connection_nmi_no = connectionNmiNo || '';
        this.connection_status = connectionStatus || '';
        this.connection_reason = connectionReason || '';
        this.gas_meter_charge = gasMeterCharge || '';
        this.manualInterventionStatus = manualInterventionStatus || null;
        this.manualInterventionIsActive = manualInterventionIsActive || false;
        this.manualInterventionAt = manualInterventionAt || null;
    }

}
