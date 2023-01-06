import dayJs from "dayjs";

export default class PersonalDetail{
    constructor({
                    title= null,
                    first_name= null,
                    middle_name= null,
                    last_name= null,
                    email= null,
                    phone= null,
                    dob= null,
                    phone_type = null,
                    homephone =null,
                    concession_card_type =  null,
                    concession_card_number =  null,
                    concession_card_start_date =  null,
                    concession_end_date =  null,
                    business_name =  null,
                    abn =  null,

                }) {

        this.title = title;
        this.first_name = first_name;
        this.middle_name = middle_name;
        this.last_name = last_name;
        this.email = email;
        this.phone = phone;
        this.dob = dayJs(dob).format("DD/MM/YYYY");
        this.phone_type = phone_type;
        this.homephone = homephone;
        this.concession_card_type = concession_card_type;
        this.concession_card_number = concession_card_number;
        this.concession_end_date = concession_end_date;
        this.concession_card_start_date = concession_card_start_date;
        this.business_name = business_name ?? "";
        this.abn = abn ?? "";

    }
}
