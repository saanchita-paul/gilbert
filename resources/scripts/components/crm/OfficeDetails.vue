<template>
  <v-container>
    <v-row>
      <v-col class="section-dialogs" cols="12">
        <div class="dialogs-title">
          <p>Let’s setup a new office</p>
        </div>

        <div class="dialogs-area">
          <p class="title">Office Details</p>
          <v-text-field
            label="Office Name Location*"
            outlined
            dense
          ></v-text-field>
          <v-text-field
            label="Office Address*"
            outlined
            dense
          ></v-text-field>
          <v-text-field
            label="Contact Phone Number*"
            outlined
            dense
          ></v-text-field>
          <v-text-field
            label="Contact Email Address*"
            outlined
            dense
          ></v-text-field>
          <v-text-field
            label="ABN (Optional)"
            outlined
            dense
          ></v-text-field>
        </div>

        <div class="d-flex justify-space-between">
          <v-btn>Cancel</v-btn>
          <v-btn color="primary">Next</v-btn>
        </div>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
export default {
  name: "OfficeDetails",
};
</script>

<style scoped>
</style>
