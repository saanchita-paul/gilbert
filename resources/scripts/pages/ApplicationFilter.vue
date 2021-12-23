<template>
  <div>
    <div class="d-flex justify-end pt-2">
      <div class="px-3 py-1 clearButton" @click="clearSearch">
        <v-icon medium color="black"> mdi mdi-close </v-icon>
        Clear Filter
      </div>
    </div>
    <div class="d-flex">
      <v-text-field
        v-model="name"
        full-width
        outlined
        dense
        hide-details="auto"
        placeholder="Name"
        style="background-color: white"
        class="my-1 mr-1"
      />
      <v-text-field
        v-model="address"
        full-width
        outlined
        dense
        hide-details="auto"
        placeholder="Address"
        style="background-color: white"
        class="my-1 mr-1"
      />
      <v-text-field
        v-model="mobile"
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
        v-model="leadSrc"
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
          <v-list-item
            link
            @change="onSrcChange(item.value)"
            v-bind="attrs"
            v-on="on"
          >
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
        v-model="tenancyType"
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
          <v-list-item
            link
            @change="onSrcChange(item.value)"
            v-bind="attrs"
            v-on="on"
          >
            <v-list-item-content>
              <v-list-item-title>{{ item.text }}</v-list-item-title>
            </v-list-item-content>
          </v-list-item>
        </template>
      </v-select>
      <!-- tenancy type ends -->
    </div>
  </div>
</template>

<script>
export default {
  name: "ApplicationFilter",
  data() {
    return {
      name: "",
      address: "",
      mobile: "",
      srcOptions: [
        // {text: 'Select a lead', value: '', disabled: true},
        { text: "All Lead Source", value: "all", icon: "" },
        {
          text: "Hood Agent Portal",
          value: "hood",
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
      ],
      tanancyTypeOptions: [
        // {text: 'Select a lead', value: '', disabled: true},
        { text: "Renter", value: "renter" },
        {
          text: "Owner",
          value: "owner",
        },
        { text: "All", value: "all" },
      ],
      leadSrc: { default: "all" },
      tenancyType: { default: "all" },
    };
  },
  methods: {
    onSrcChange(value) {
      this.$router.push({
        name: "application.list",
        query: { ...this.$route.query, ...{ source: value } },
      });
    },
    clearSearch(){
        this.name = ""
        this.address = ""
        this.mobile = ""
        this.leadSrc = "all"
        this.tenancyType = "all"

        this.$router.push({
        name: "application.list",
        query: {  },
      });
    }
  },
  watch: {
    name() {
      console.log("search printing");
      this.$router.push({
        name: "application.list",
        query: { ...this.$route.query, ...{ name: this.name } },
      });
    },
    address() {
      console.log("search printing");
      this.$router.push({
        name: "application.list",
        query: { ...this.$route.query, ...{ address: this.address } },
      });
    },
    mobile() {
      console.log("search printing");
      this.$router.push({
        name: "application.list",
        query: { ...this.$route.query, ...{ mobile: this.mobile } },
      });
    },
    leadSrc() {
      console.log("search printing");
      this.$router.push({
        name: "application.list",
        query: { ...this.$route.query, ...{ src: this.leadSrc } },
      });
    },
    tenancyType() {
      console.log("search printing");
      this.$router.push({
        name: "application.list",
        query: { ...this.$route.query, ...{ tenancy: this.tenancyType } },
      });
    },
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