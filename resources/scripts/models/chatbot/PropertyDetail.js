import dayJs from "dayjs";

export default class PropertyDetail{
    constructor({
                    which_utility= null,
                    account_type= null,
                    rent= null,
                    solar_panel= null,
                    moved_at= null,
                    flat_or_unit_number= null,
                    street_number= null,
                    street_name = null,
                    street_type =null,
                    to_postcode =null,
                    suburb =null,
                    state =null,
        
                    mirn =null,
                    nmi =null,
                    has_concession_card = null,
                    concession_card_type = null,
                    concession_card_value = null,
                    concession_card_start_date = null,
                    to_address = null,
                    full_address = null,

                }) {

        this.which_utility = which_utility;
        this.account_type = account_type;
        this.rent = rent;
        this.solar_panel = solar_panel;
        this.moved_at = moved_at;
        this.flat_or_unit_number = flat_or_unit_number;
        this.street_number = street_number;
        this.to_postcode = to_postcode;
        this.suburb = suburb;
        this.state = state;
        this.mirn = mirn;
        this.nmi = nmi;
        this.has_concession_card = has_concession_card;
        this.concession_card_type = concession_card_type;
        this.concession_card_value = concession_card_value;
        this.concession_card_start_date = concession_card_start_date;
        this.concession_card_start_date = concession_card_start_date;
        this.to_address = to_address.split(',').join(', ');
        this.street_name = street_name;
        this.street_type = street_type;
        this.full_address ='U'+' '+ flat_or_unit_number+' '+ street_number+' '+street_name+' '+street_type +', '+suburb+' ' +state +' '+to_postcode;
    }
}
