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
                    business_name =  null,
                    abn =  null,
                    titleUcFirst =  null,
                    firstnameUcFirst =  null,
                    lastnameUcFirst =  null,
                    fullName =  null,


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
        this.business_name = business_name ?? "";
        this.abn = abn ?? "";
        this.titleUcFirst = title.charAt(0).toUpperCase() + title.slice(1);
        this.firstnameUcFirst = first_name.charAt(0).toUpperCase() + first_name.slice(1);
        this.lastnameUcFirst = last_name.charAt(0).toUpperCase() + last_name.slice(1);
        this.fullName = title.charAt(0).toUpperCase() + title.slice(1) + '. ' + first_name.charAt(0).toUpperCase() + first_name.slice(1) + ' ' + last_name.charAt(0).toUpperCase() + last_name.slice(1);

    }


}
