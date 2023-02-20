import dayJs from "dayjs";

function getFullName(title, firstName, lastName) {
    let fullName = ((title?? ' ') + ' ' + (firstName?? ' ') + ' ' + (lastName?? '')).trim();
    fullName = fullName.split(' ').map(word => word.charAt(0).toUpperCase() + word.slice(1) );
    return fullName.join(' ')


}

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
                    billing_preference = null,
                    enabled_marketing_offer = null,
                    is_property_on_life_support = null,
                    connection_services = []

                }) {

        this.title = title;
        this.first_name = first_name;
        this.middle_name = middle_name;
        this.last_name = last_name;
        this.email = email;
        this.phone = phone;
        this.dob = dob
        this.phone_type = phone_type;
        this.homephone = homephone;
        this.business_name = business_name ?? "";
        this.abn = abn ?? "";
        this.titleUcFirst = title?.charAt(0)?.toUpperCase() + title?.slice(1);
        this.firstnameUcFirst = first_name?.charAt(0)?.toUpperCase() + first_name?.slice(1);
        this.lastnameUcFirst = last_name?.charAt(0)?.toUpperCase() + last_name?.slice(1);
        this.fullName = getFullName(this.titleUcFirst, this.firstnameUcFirst, this.lastnameUcFirst);
        this.connection_type = this.generateConnectionType(abn, business_name);
        this.billing_preference = billing_preference; //value-> email/post
        this.enabled_marketing_offer = this.mapMarketingOffer(enabled_marketing_offer, connection_services); //value-> yes/no
        this.is_property_on_life_support = parseInt(is_property_on_life_support); //value-> yes/no
    }
    generateConnectionType(abn, business_name){
        if(abn || business_name) return "Temporary";
        return "Default"
    }

    mapMarketingOffer(offer, service) {
        if(service.length > 0 &&  service[0].provider_name === 'origin') {
            return offer !== 1 ? 0 : 1;
        }
        return offer;
    }


}
