<template>
    <v-app id="inspire" v-if="user && agency" style="position: relative" ref="mainSpinner">
        <v-app-bar app class="app-app-bar" dark>
            <v-img
                class="mx-2"
                src="/assets/images/logo/hood_logo_while.png"
                max-height="40"
                max-width="40"
                contain
            ></v-img>

            <v-toolbar-title class="ml-2">
                {{getHeader()}}
            </v-toolbar-title>

            <v-spacer/>

            <v-toolbar-title class="user-name-area">
                <h6>{{ user.profile.first_name }} {{ user.profile.last_name }}</h6>
                <span>{{ user.email }}</span>
            </v-toolbar-title>

            <v-menu offset-y>
                <template v-slot:activator="{ on, attrs }">
                    <v-avatar v-if="profileImageFlag" size="50" class="mr-3">
                           <v-img  v-bind="attrs"
                    v-on="on"
                        class="mx-2"
                        src="https://cdn.vuetifyjs.com/images/john.jpg"
                        max-height="40"
                        max-width="40"
                        contain
                    ></v-img>
                    </v-avatar>
                  <v-avatar v-if="!profileImageFlag" size="40" class="mr-3" color="white">
                    <span class="black--text">{{profile_display_name}}</span>
                  </v-avatar>

                </template>
                <ProfileDropdown @onLogout="onLogout" @editProfile="editProfile"></ProfileDropdown>
            </v-menu>
        </v-app-bar>

        <EditProfileModal v-if="editProfileFlag" :user="user" dialog="editProfileFlag"
                          @saveUserSuccess="saveUserSuccess"
                          @cancleModal="cancleModal"></EditProfileModal>
        <v-main>

            <CreateSuccessfulModal v-if="userProfileUpdateFlag"
                                   :dialog="userProfileUpdateFlag"  :isUpdate="userProfileUpdateFlag" title="Your profile"  @cancel="cancelSuccessfulModal">
            </CreateSuccessfulModal>

            <router-view></router-view>
        </v-main>

    </v-app>
</template>

<script>
import ProfileDropdown from "@scripts/components/crm/ProfileDropdown";
import ApplicationService from "../services/ApplicationService";
import AuthService from "@scripts/services/AuthService";
import EditProfileModal from "@scripts/components/crm/modals/EditProfileModal";
import CreateSuccessfulModal from "@scripts/components/crm/modals/CreateSuccessfulModal";
import AgencyService from "@scripts/services/crm/AgencyService";

export default {
    name: "NewDashboardLayout",
    components: {
        EditProfileModal,
        ProfileDropdown,
        CreateSuccessfulModal
    },
    data() {
        return {
            user: null,
            profile_display_name: '',
            agency: null,
            drawer: null,
            editProfileFlag: false,
            userProfileUpdateFlag: false,
            routes: ApplicationService.getMainNavigationRoutes()
        }
    },
    computed:{
        profileImageFlag()
        {
          const flag = Boolean(this.user?.profile?.profile_photo);
          this.profile_display_name = this.user?.profile?.first_name.charAt(0) + this.user?.profile?.last_name.charAt(0)
          return flag;
        }

    },
     methods: {
         getHeader() {
             return this.agency.title + ' / ' + this.user.profile.office.name;
         },

         async getAgency(agencyId) {
             return await AgencyService.loadAgencyById(agencyId);
         },

        async onLogout() {
            await AuthService.logout();
        },

         editProfile() {
            this.editProfileFlag = true;
         },

         cancleModal() {
             this.editProfileFlag = false;
         },

         saveUserSuccess() {
             this.editProfileFlag = false;
             this.userProfileUpdateFlag = true;
         },

         cancelSuccessfulModal() {
             this.userProfileUpdateFlag = false;
         }
    },
    async mounted() {
      this.user = AuthService.getAuthUser();
      this.agency = await this.getAgency(this.user.profile.agency_id);
      setInterval(AuthService.authUser, 300000)
    },
}
</script>

<style scoped>
.app-app-bar {
    background-color: #542E89 !important;
    max-height: 76px !important;
}

.app-bar-user-name {
    font-size: 14px !important;
    font-Weight: 600 !important;
    line-height: 20px !important;
    letter-spacing: .2px;
    margin-top: 15px;
    color: rgb(223, 224, 235, 1);
}
</style>
