import Location from "@scripts/models/Location";

export default class Customer {
    constructor(
        {
            id,
            avatar,
            email,
            facebook_id,
            first_name,
            last_name,
            full_name,
            has_booked_movers,
            has_finished_onboarding,
            has_finished_utility_flow,
            has_setup_reminders,
            moving_date,
            phone,
            uin,
            bedrooms,
            house_type,
            rent,
            people,
            energy_usage,
            solar_panel,
            from,
            to,
        } = {}
    ) {
        this.id = id || null;
        this.avatar = avatar || null;
        this.email = email || null;
        this.facebook_id = facebook_id || null;
        this.first_name = first_name || null;
        this.last_name = last_name || null;
        this.full_name = full_name || null;
        this.has_booked_movers = has_booked_movers || false ;
        this.has_finished_onboarding = has_finished_onboarding || false;
        this.has_finished_utility_flow = has_finished_utility_flow || false;
        this.has_setup_reminders = has_setup_reminders || false;
        this.moving_date = moving_date || null;
        this.phone = phone || null;
        this.uin = uin || null;
        this.bedrooms = bedrooms || null;
        this.house_type = house_type || null;
        this.rent = rent || null;
        this.people = people || null;
        this.energy_usage = energy_usage || null;
        this.solar_panel = solar_panel || null;
        this.from = new Location(from);
        this.to = new Location(to);
    }
}
