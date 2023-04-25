export default class PersonDetails {
    title = "";
    first_name = "";
    middle_name = "";
    last_name = "";
    dob = "";
    phone = "";
    homephone = "";
    phone_type = "";
    email = "";
    is_email_billing = "";
    tenancy_type = "";
    family_violance = "";
    additional_instruction = "";

    is_email_marketing = null;
    concession_card_type = null;
    concession_card_number = null;
    concession_start_date = null;
    concession_end_date = null;
    email_manually_verified_by = null;

    /**
     * Person details constructor
     *
     * @param title
     * @param first_name
     * @param middle_name
     * @param last_name
     * @param dob
     * @param phone
     * @param homephone
     * @param phone_type
     * @param email
     * @param is_email_billing
     * @param tenancy_type
     * @param family_violance
     * @param additional_instruction
     *
     * @param is_email_marketing
     * @param concession_card_type
     * @param concession_card_number
     * @param concession_start_date
     * @param concession_end_date
     * @param email_manually_verified_by
     *
     */
    constructor({
                    title = "",
                    first_name = "",
                    middle_name = "",
                    last_name = "",
                    dob = "",
                    phone = "",
                    homephone = "",
                    phone_type = "",
                    email = "",
                    is_email_billing = "",
                    tenancy_type = "",
                    family_violance = "",
                    additional_instruction = "",

                    is_email_marketing = null,
                    concession_card_type = null,
                    concession_card_number = null,
                    concession_start_date = null,
                    concession_end_date = null,
                    email_manually_verified_by = null
                } = {}) {
        this.title = title;
        this.first_name = first_name;
        this.middle_name = middle_name;
        this.last_name = last_name;
        this.dob = dob;
        this.phone = phone;
        this.homephone = homephone;
        this.phone_type = phone_type;
        this.email = email;
        this.is_email_billing = is_email_billing;
        this.tenancy_type = tenancy_type;
        this.family_violance = family_violance;
        this.additional_instruction = additional_instruction;

        this.is_email_marketing = is_email_marketing;
        this.concession_card_type = concession_card_type;
        this.concession_card_number = concession_card_number;
        this.concession_start_date = concession_start_date;
        this.concession_end_date = concession_end_date;
        this.email_manually_verified_by = email_manually_verified_by;
    }
}
