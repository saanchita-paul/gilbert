<template>
    <v-row>
      <v-col class="section-dialogs" cols="12">
        <div class="dialogs-title">
          <p>Office Allocator Details</p>
        </div>

        <div class="dialogs-area">
          <p class="title">Person to allocate commisions</p>
          <v-text-field
            label="Fistname"
            placeholder="Fistname"
            outlined
            dense
          ></v-text-field>
          <v-text-field
            label="Lastname"
            placeholder="Lastname"
            outlined
            dense
          ></v-text-field>

          <v-select
            outlined
            dense
            label="Job Title"
            placeholder="Job title selector"
          ></v-select>

          <v-text-field label="212f ID" outlined dense></v-text-field>

          <v-text-field
            label="Email Address*"
            placeholder="firstname.lastname@barryplantcamberwell.com.au"
            outlined
            dense
          ></v-text-field>
          <v-text-field
            label="Phone Number (Optional)"
            outlined
            dense
          ></v-text-field>
        </div>
      </v-col>
    </v-row>
</template>

<script>
export default {
  name: "AllocatorDetails",
};
</script>

<style scoped>
</style>
