<template>
    <v-row>
      <v-col class="section-dialogs" cols="12">
        <div class="dialogs-title">
          <p>Setup Commission Profile</p>
        </div>

        <div class="dialogs-area">
          <p class="title">Per successful connection</p>
            <ValidationProvider name="Power" rules="numeric|max:2|min_value:1|required"  v-slot="{ errors }">
              <v-text-field
                label="Power"
                v-model="profile.power"
                @input="updateProfile"
                placeholder="$ 50.00"
                outlined
                dense
                prepend-inner-icon="mdi-flash yellow--text"
                :error-messages=" errors[0]"
              ></v-text-field>
            </ValidationProvider>
            <ValidationProvider name="Gas" rules="numeric|max:2|min_value:1|required"  v-slot="{ errors }">
              <v-text-field
                label="Gas"
                placeholder="$ 0.00"
                v-model="profile.gas"
                @input="updateProfile"
                outlined
                dense
                prepend-inner-icon="mdi-fire red--text"
                :error-messages=" errors[0]"
              ></v-text-field>
            </ValidationProvider>
            <ValidationProvider name="Water" rules="numeric|max:2|min_value:1|required"  v-slot="{ errors }">
              <v-text-field
                label="Water"
                placeholder="$ 50.00"
                v-model="profile.water"
                @input="updateProfile"
                outlined
                disabled
                dense
                prepend-inner-icon="mdi-water blue-grey--text"
                :error-messages=" errors[0]"
              ></v-text-field>
            </ValidationProvider>
            <ValidationProvider name="Internet" rules="numeric|max:2|min_value:1|required"  v-slot="{ errors }">
              <v-text-field
                label="Internet"
                placeholder="$ 50.00"
                v-model="profile.internet"
                @input="updateProfile"
                outlined
                disabled
                dense
                prepend-inner-icon="mdi-wifi black--text"
                :error-messages=" errors[0]"
              ></v-text-field>
            </ValidationProvider>
        </div>
      </v-col>
    </v-row>
</template>

<script>
export default {
  name: "CommissionProfile",
    props:['data'],
    data() {
      return {
          profile: {
              power: null,
              gas: null,
              water: null,
              internet: null,

          }
      }
    },

    methods: {

        updateProfile() {
            this.$emit('updateProfile', this.profile);
        },

        updateWithProps()
        {
           this.profile = this.data?.profile;
        }
    },
    mounted() {
      this.updateWithProps()
    }
};
</script>

<style scoped>
</style>
