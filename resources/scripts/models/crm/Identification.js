export default class Identification {
    type = "";
    card_number = "";
    special_number = "";
    expire_date = "";
    card_color = "";
    state = "";
    country = "";
    medicare_expire_date = "";

    /**
     * Identification info constructor
     *
     * @param type
     * @param card_number
     * @param special_number
     * @param expire_date
     * @param card_color
     * @param state
     * @param country
     * @param medicare_expire_date
     *
     */
    constructor({
                    type = "",
                    card_number = "",
                    special_number = "",
                    expire_date = "",
                    card_color = "",
                    state = "",
                    country = "",
                    medicare_expire_date = ""
                } = {}) {
        this.type = type;
        this.card_number = card_number;
        this.special_number = special_number;
        this.expire_date = expire_date;
        this.card_color = card_color;
        this.state = state;
        this.country = country;
        this.medicare_expire_date = medicare_expire_date;
    }
}
