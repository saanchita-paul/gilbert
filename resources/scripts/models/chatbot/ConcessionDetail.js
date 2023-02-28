import dayJs from "dayjs";
import DayJs from "dayjs";

export default class ConcessionDetail{
    constructor({

                    concession_card_type =  null,
                    concession_card_value =  null,
                    concession_card_start_date =  null,
                    concession_end_date =  null,

                }) {

        this.concession_card_type = concession_card_type;
        this.concession_card_value = concession_card_value;
        this.concession_end_date = concession_end_date ?? "" ;
        this.concession_card_start_date = concession_card_start_date ;

    }
}
