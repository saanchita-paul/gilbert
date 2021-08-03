<template>
    <v-row>
      <v-col class="section-dialogs" cols="12">
        <div class="dialogs-title">
          <p>Setup Commission Profile</p>
        </div>

        <div class="dialogs-area">
          <p class="title">Per successful connection</p>
          <v-text-field
            label="Power"
            v-model="profile.power"
            @input="updateProfile"
            placeholder="$ 50.00"
            outlined
            dense
            prepend-inner-icon="mdi-flash yellow--text"
          ></v-text-field>

          <v-text-field
            label="Gas"
            placeholder="$ 0.00"
            v-model="profile.gas"
            @input="updateProfile"
            outlined
            dense
            prepend-inner-icon="mdi-fire red--text"
          ></v-text-field>
          <v-text-field
            label="Water"
            placeholder="$ 50.00"
            v-model="profile.water"
            @input="updateProfile"
            outlined
            dense
            prepend-inner-icon="mdi-water blue-grey--text"
          ></v-text-field>
          <v-text-field
            label="Internet"
            placeholder="$ 50.00"
            v-model="profile.internet"
            @input="updateProfile"
            outlined
            dense
            prepend-inner-icon="mdi-wifi black--text"
          ></v-text-field>
        </div>
      </v-col>
    </v-row>
</template>

<script>
export default {
  name: "CommissionProfile",
    data() {
      return {
          profile: {
              power: 0,
              gas: 0,
              water: 0,
              internet: 0,

          }
      }
    },

    methods: {

        updateProfile() {
            this.$emit('updateProfile', this.profile);
        }
    }
};
</script>

<style scoped>
</style>
