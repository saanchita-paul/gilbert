export default class CrmUser{
    constructor({id = 10, submitted_lead=0, role='user', phone, email, first_name='Mr' , last_name ='Ahmed',
                    profile_photo='https://cdn.vuetifyjs.com/images/john.jpg', user , role_value }) {
        this.id = id;
        this.proerty_manager_name = first_name + ' ' + last_name;
        this.submitted_lead = submitted_lead;
        this.role = role;
        this.role_value = role_value;
        this.first_name = first_name
        this.last_name = last_name
        this.phone = phone;
        this.email = user?.email;
        this.profile_img = profile_photo;
    }
}
