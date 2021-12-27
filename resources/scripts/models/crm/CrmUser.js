export default class CrmUser{
    constructor({id = 10, submitted_lead=0, role='user', phone, email, first_name='Mr' , last_name ='Ahmed',
                    profile_photo='https://cdn.vuetifyjs.com/images/john.jpg', user  }) {
        this.id = id;
        this.proerty_manager_name = first_name + ' ' + last_name;
        this.submitted_lead = submitted_lead;
        this.role = role;
        this.first_name = first_name
        this.last_name = last_name
        this.phone = phone;
        this.email = user?.email;
        this.profile_img = profile_photo;
        this.is_active = user?.is_active;
        this.last_submitted = '02/22/2022'; //todo: need to get from server
        this.visa = 1;  //todo: need to get from server
        this.cvr = '76%'  //todo: need to get from server
    }
}
