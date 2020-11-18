import Customer from "@scripts/models/Customer";

export default {
    toClientDetail: (data) => {
        const { from : fromOriginal = {}, to : toOriginal = {}, ...others } = data || {};
        const { bedrooms, house_type, ...from } = fromOriginal;
        const { rent, people, energy_usage, solar_panel, ...to } = toOriginal;

        return new Customer({ ...others, bedrooms, house_type, rent, people, energy_usage, solar_panel, from, to });
    }
};
