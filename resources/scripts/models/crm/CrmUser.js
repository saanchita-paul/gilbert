export default class CrmUser{
    constructor({id = 10, submitted_lead=100, role='user', phone='444444', email, first_name='Mr' , last_name ='Ahmed',
                    profile_photo='https://cdn.vuetifyjs.com/images/john.jpg' }) {
        this.id = id;
        this.proerty_manager_name = first_name + ' ' + last_name;
        this.submitted_lead = submitted_lead;
        this.role = role;
        this.phone = phone;
        this.email = email;
        this.profile_img = profile_photo;
    }
}
