<template>
  <div>
    <v-form ref="form" autocomplete="off" >
      <div class="d-flex justify-end pt-2">
        <!-- <slot/> -->
        <!-- <div class="px-3 py-1 clearButton" @click="clearSearch">
            <v-icon medium color="black"> mdi mdi-close </v-icon>
              Clear Filter
        </div> -->
        <v-btn v-show="!isSearchEmpty" x-small tile color="#e0e0e0" @click="clearSearch">
          <v-icon small left> mdi mdi-close </v-icon>
          Clear Filter
        </v-btn>
      </div>
      <div class="d-flex">
        <v-text-field
            autocomplete="off"
          v-model="$attrs.value.tenant_name"
          full-width
          outlined
          dense
          hide-details="auto"
          placeholder="Name"
          style="background-color: white"
          class="my-1 mr-1"
        />
        <v-text-field
          v-model="$attrs.value.address"
          full-width
          outlined
          dense
          hide-details="auto"
          placeholder="Address"
          style="background-color: white"
          class="my-1 mr-1"
        />
        <v-text-field
          v-model="$attrs.value.phone"
          full-width
          outlined
          dense
          hide-details="auto"
          placeholder="Mobile"
          style="background-color: white"
          class="my-1 mr-1"
        />
        <!-- lead source start -->
        <v-select
          placeholder="Source"
          v-model="$attrs.value.source"
          item-text="text"
          item-value="value"
          :items="srcOptions"
          hide-details="auto"
          style="background-color: white"
          class="my-1 mr-1"
          outlined
          dense
        >
          <template v-slot:item="{ item, attrs, on }">
            <v-list-item link v-bind="attrs" v-on="on">
              <v-list-item-avatar>
                <v-img :src="item.icon" width="20px" />
              </v-list-item-avatar>
              <v-list-item-content>
                <v-list-item-title>{{ item.text }}</v-list-item-title>
              </v-list-item-content>
            </v-list-item>
          </template>
        </v-select>
        <!-- lead source ends -->
        <!-- tenancy type starts -->
        <v-select
          placeholder="Tenancy"
          v-model="$attrs.value.tenancy_type"
          item-text="text"
          item-value="value"
          :items="tanancyTypeOptions"
          hide-details="auto"
          style="background-color: white"
          class="my-1"
          outlined
          dense
        >
          <template v-slot:item="{ item, attrs, on }">
            <v-list-item link v-bind="attrs" v-on="on">
              <v-list-item-content>
                <v-list-item-title>{{ item.text }}</v-list-item-title>
              </v-list-item-content>
            </v-list-item>
          </template>
        </v-select>
        <!-- tenancy type ends -->
      </div>
    </v-form>
  </div>
</template>

<script>
export default {
  name: "ApplicationFilter",
  props: ["isSearchEmpty"],
  data() {
    return {
      tenant_name: "",
      name: "",
      address: "",
      mobile: "",
      srcOptions: [
        { text: "All Lead Source", value: "", icon: "" },
        {
          text: "Hood Agent Portal",
          value: "hood",
          icon: "/assets/images/icons/company/hood.png",
        },
          {
              text: "Hood.AI",
              value: "hood_ai",
              icon: "/assets/images/icons/company/hood.png",
          },
        {
          text: "Foxie CRM",
          value: "foxie",
          icon: "/assets/images/icons/company/foxie.png",
        },
        {
          text: "Ignite ",
          value: "ignite",
          icon: "/assets/images/icons/company/ignite.png",
        },
        {
          text: "Our Property",
          value: "our-property",
          icon: "/assets/images/icons/company/our-property.png",
        },
        {
          text: "PropertyMe ",
          value: "property_me",
          icon: "/assets/images/icons/company/propertyMe.png",
        },
          {text: 'TApp', value: 't_app', icon: '/assets/images/icons/company/tapp.png'},

      ],
      tanancyTypeOptions: [
        { text: "Renter", value: "renter" },
        {
          text: "Owner",
          value: "home_owner",
        },
        { text: "All", value: "" },
      ],
      leadSrc: { default: "all" },
      tenancy_Type: { default: "all" },
    };
  },
  methods: {
    clearSearch() {
      this.$refs.form.reset();
    },
  },
  watch:{
    isSearchEmpty(val){
      console.log("feea" , val)
    }
  },

};
</script>

<style lang="scss" scoped>
.clearButton {
  background: #e0e0e0;
  color: black;
  border-radius: 1px;
  &:hover {
    cursor: pointer;
  }
}
</style>
