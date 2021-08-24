<template>
     <v-row class="edit-profile-modal">
        <v-col class="section-dialogs" cols="12">
          <div class="dialogs-title">
            <p>Editing your profile.</p>
          </div>

          <div class="dialogs-area">
                <p class="sub-title">Your Details</p>

                <v-text-field
                    label="First Name*"
                    v-model="profile.first_name"
                    outlined
                    dense
                    placeholder="First Name"
                ></v-text-field>
              <v-text-field
                  label="Last Name*"
                  v-model="profile.last_name"
                  outlined
                  dense
                  placeholder="Last Name"
              ></v-text-field>

                <v-text-field
                    label="Phone Number (Optional)"
                    v-model="profile.phone"
                    outlined
                    dense
                    placeholder="+16"
                ></v-text-field>

                <v-text-field
                    v-model="profile.email"
                    label="Email Address (Optional)"
                    outlined
                    dense
                    placeholder="examples@domim.com"
                ></v-text-field>
          </div>

          <div>
              <v-btn @click="cancleModal">Cancel</v-btn>
              <v-btn @click="saveUser" color="primary">Save</v-btn>
          </div>
        </v-col>
      </v-row>
</template>

<script>
import CrmUserService from "@scripts/services/crm/CrmUserService";

export default {
  name: "EditingProfile",
    props:['user'],
    data() {
        return {
            profile :{
                first_name: '',
                last_name: '',
                email: '',
                phone: '',
            }
        }
    },
    methods: {
        cancleModal() {
            this.$emit('cancleModal');
        },
        synForm() {
            this.profile.first_name = this.user?.profile?.first_name;
            this.profile.last_name = this.user?.profile?.last_name;
            this.profile.email = this.user?.email;
            this.profile.phone = this.user?.profile?.phone;
            this.profile.id = this.user.profile?.id;
        },

        async saveUser() {
               await CrmUserService.updateUserProfile(this.profile, this.user.id);
                this.$emit('saveUserSuccess');
        }
    },
    mounted() {
      this.synForm();
    }
};
</script>

<style scoped>
.edit-profile-modal{
    background: white;
}
</style>
