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
                    homephone =null
                }) {

        this.title = title;
        this.first_name = first_name;
        this.middle_name = middle_name;
        this.last_name = last_name;
        this.email = email;
        this.phone = phone;
        this.dob = dob;
        this.phone_type = phone_type;
        this.homephone = homephone;

    }
}
