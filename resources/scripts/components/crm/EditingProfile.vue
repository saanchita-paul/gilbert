<template>
     <v-row>
        <v-col class="section-dialogs" cols="12">
          <div class="dialogs-title">
            <p>Editing your profile.</p>
          </div>

          <div class="dialogs-area">
                <p class="sub-title">Your Details</p>

                <v-text-field
                    label="Full Name*"
                    outlined
                    dense
                    placeholder="First Name Last Name"
                ></v-text-field>

                <v-text-field
                    label="Phone Number (Optional)"
                    outlined
                    dense
                    placeholder="+16"
                ></v-text-field>

                <v-text-field
                    label="Email Address (Optional)"
                    outlined
                    dense
                    placeholder="examples@domim.com"
                ></v-text-field>
          </div>

          <div>
              <v-btn >Cancel</v-btn> 
              <v-btn color="primary">Save</v-btn> 
          </div>
        </v-col>
      </v-row>
</template>

<script>
export default {
  name: "EditingProfile",
};
</script>

<style scoped>
</style>
