import dayJs from "dayjs";

export default class IdDetail{
    constructor({
                    identification_type= null,
                    driving_license_number= null,
                    driving_license_state= null,
                    passport_number= null,
                    passport_country= null,
                    medicare_card_color= null,
                    medicare_card_number= null,
                    individual_reference_number= null,
                    identification_expire_date= null,
                }) {

        this.identification_type = identification_type;
        this.driving_license_number = driving_license_number;
        this.driving_license_state = driving_license_state;
        this.passport_number = passport_number;
        this.passport_country = passport_country;
        this.medicare_card_color = medicare_card_color;
        this.medicare_card_number = medicare_card_number;
        this.individual_reference_number = individual_reference_number;
        this.identification_expire_date = dayJs(identification_expire_date).format("DD/MM/YYYY");
    }
}
