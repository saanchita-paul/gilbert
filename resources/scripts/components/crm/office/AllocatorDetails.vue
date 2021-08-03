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
            @input="updateAllocator"
            placeholder="Fistname"
            v-model="allocator.first_name"
            outlined
            dense
          ></v-text-field>
          <v-text-field
            label="Lastname"
            @input="updateAllocator"
            v-model="allocator.last_name"
            placeholder="Lastname"
            outlined
            dense
          ></v-text-field>

          <v-select v-model="allocator.job_title" @input="updateAllocator"
            outlined
            dense
            label="Job Title"
            placeholder="Job title selector"
          ></v-select>

          <v-text-field  v-model="allocator.id_212f" @input="updateAllocator" label="212f ID" outlined dense></v-text-field>

          <v-text-field
            label="Email Address*"
            v-model="allocator.email"
            @input="updateAllocator"
            placeholder="firstname.lastname@barryplantcamberwell.com.au"
            outlined
            dense
          ></v-text-field>
          <v-text-field
              v-model="allocator.phone_number" @input="updateAllocator"
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
    props: ['agency'],
    data() {
      return {
          allocator: {
              first_name: '',
              last_name: '',
              job_title: '',
              id_212f: '',
              email: '',
              phone_number: '',
          }
      }
    },

    methods: {
        updateAllocator() {
            console.log(this.allocator);
            this.$emit('updateAllocator', this.allocator);
        }
    }

}
</script>

<style scoped>
</style>
