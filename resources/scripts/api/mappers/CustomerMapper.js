import Customer from "@scripts/models/Customer";
import CustomerProperty from "@scripts/models/CustomerProperty";
import CustomerConnection from "@scripts/models/CustomerConnection";

export default {
    toClientDetail: (data) => {
        const { from : fromOriginal = {}, to : toOriginal = {}, ...others } = data || {};
        const { bedrooms, house_type, ...from } = fromOriginal;
        const { rent, people, energy_usage, solar_panel, ...to } = toOriginal;

        return new Customer({ ...others, bedrooms, house_type, rent, people, energy_usage, solar_panel, from, to });
    },

    /**
     * mapping CustomerProperty from API data
     *
     * @param propertyData
     *
     * @returns {CustomerProperty}
     */
    mapPropertyInfo: propertyData =>  new CustomerProperty({
        id: propertyData.id,
        property_address_text: propertyData.property_address,
        occupation_type: propertyData.occupation_type,
        // activities : propertyData.activities,
        activities : ['Moving Calculator', 'Energy Connection', 'Reminder'],
        house_type: propertyData.house_type,
        house_size: propertyData.house_size,
    }),
    /**
     * mapping CustomerProperty from API data
     *
     *
     * @returns {CustomerConnection}
     * @param connectionData
     */
    mapConnectionInfo: connectionData =>  new CustomerConnection({
        id: connectionData.id,
        connection_address_text: connectionData.connection_address,
        provider: connectionData.provider,
        energy_type: connectionData.which_utility,
        selected_plan: connectionData.plan,
        solar: connectionData.solar,
    })
};
