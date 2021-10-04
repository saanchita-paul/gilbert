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
                    sentiment,

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
                    manualInterventionAt,
                    eQuoteID,
                    gQuoteID,
                    eReason,
                    gReason,
                    eConnectionStatus,
                    gConnectionStatus,
                    overallProgress,
                    movingUtilityId

                } = {}) {
        this.id = id;
        this.name = full_name;
        this.profile_pic = avatar
        this.last_interaction = last_interaction;
        this.hood_uid = hood_ui;
        this.messager_id = facebook_id;
        this.email = email;
        this.ph = phone;
        this.sentiment = sentiment;

        this.property_profile_id = propertyProfileId || '';
        this.property_account_type = propertyAccountType || '';
        this.property_estimated_moving_period = propertyEstimatedMovingPeriod || '';
        this.property_currnt_address = propertyCurrentAddress || '';
        this.property_previous_address = propertyPriviousAddress || '';
        this.property_tenancy_type = propertyTenancyType || '';
        this.property_house_type = propertyHouseType || '--';
        this.property_house_size = propertyHouseSize || '--';
        this.property_solar_powered = propertySolarPowered ;
        this.property_life_support = propertyLifeSupport || '';
        this.is_manual_address = isManualAddress || '';
        this.property_ea_response_time = propertyEAResponseTime || '';
        this.user_agree_time = userAgreeTime || '';
        this.property_id_type = propertyIdType || '';

        this.connection_id = connectionId || '';
        this.connection_provider = connectionProvider || '';
        this.connection_selected_plan = connectionSelectedPlan || '';
        this.connection_energy_type = connectionEneryType;
        this.connection_address = connectionAddress || '';
        this.e_destributor = connectionEDestributor || '';

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
        this.e_quote_id = eQuoteID || null;
        this.g_quote_id = gQuoteID || null;
        this.e_reason = eReason || null;
        this.g_reason = gReason || null;
        this.e_connection_status = eConnectionStatus || null;
        this.g_connection_status = gConnectionStatus || null;
        this.overall_progress = overallProgress;
        this.moving_utility_id = movingUtilityId
    }

}
