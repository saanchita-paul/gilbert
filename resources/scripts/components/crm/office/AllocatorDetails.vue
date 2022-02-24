<template>
        <v-row>
      <v-col class="section-dialogs" cols="12">
        <div class="dialogs-title">
          <p>Office Allocator Details</p>
        </div>

        <div class="dialogs-area">
          <p class="title">Person to allocate commisions</p>
            <ValidationProvider name="Firstname" rules="required"  v-slot="{ errors }">
              <v-text-field
                label="Firstname*"
                @input="updateAllocator"
                placeholder="Firstname"
                v-model="allocator.first_name"
                outlined
                dense
                :error-messages=" errors[0]"
              ></v-text-field>
            </ValidationProvider>
            <ValidationProvider name="Lastname" rules="required"  v-slot="{ errors }">
          <v-text-field
            label="Lastname*"
            @input="updateAllocator"
            v-model="allocator.last_name"
            placeholder="Lastname"
            outlined
            dense
            :error-messages=" errors[0]"
          ></v-text-field>
            </ValidationProvider>
<!--            <ValidationProvider name="Jobtitle" rules="required"  v-slot="{ errors }">-->
<!--              <v-select v-model="allocator.job_title" @input="updateAllocator"-->
<!--                outlined-->
<!--                dense-->
<!--                label="Job Title"-->

<!--                placeholder="Job title selector"-->
<!--              ></v-select>-->
<!--            </ValidationProvider>-->

          <v-text-field  v-model="allocator.f_id_12" @input="updateAllocator" label="212f ID" outlined dense></v-text-field>
            <ValidationProvider name="Email" rules="required|email|unique-user-email"  v-slot="{ errors }">
              <v-text-field
                label="Email Address*"
                v-model="allocator.email"
                @input="updateAllocator"
                placeholder="firstname.lastname@barryplantcamberwell.com.au"
                outlined
                dense
                :error-messages=" errors[0]"
              ></v-text-field>
            </ValidationProvider>
            <ValidationProvider name="PhoneNumber" rules="cv-phone|length:10"  v-slot="{ errors }">
              <v-text-field
                  v-model="allocator.phone_number" @input="updateAllocator"
                label="Phone Number (Optional)"
                outlined
                dense
                  :error-messages=" errors[0]"
              ></v-text-field>
            </ValidationProvider>
        </div>
      </v-col>
    </v-row>
</template>

<script>
export default {
  name: "AllocatorDetails",
    props: ['data'],
    data() {
      return {
          allocator: {
              first_name: '',
              last_name: '',
              job_title: '',
              f_id_12: '',
              email: '',
              phone_number: '',
          }
      }
    },

    methods: {

        updateAllocator() {
            // console.log(this.allocator);
            this.$emit('updateAllocator', this.allocator);
        },

        updateWithProps()
        {
            console.log(this.data);
            this.allocator = this.data?.allocator;
        },

    },

    mounted() {
     this.updateWithProps();
    }

}
</script>

<style scoped>
</style>
