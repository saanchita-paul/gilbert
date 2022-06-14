<template>
  <div>
    <v-form ref="form" autocomplete="off" >
      <div class="d-flex">
        <v-text-field
          autocomplete="off"
          outlined
          dense
          hide-details="auto"
          placeholder="Name Search"
          style="background-color: white"
          class="my-1 mr-1"
        />
        <v-text-field
          outlined
          dense
          hide-details="auto"
          placeholder="Address"
          style="background-color: white"
          class="my-1 mr-1"
        />
        <v-text-field
          outlined
          dense
          hide-details="auto"
          placeholder="Contact Number"
          style="background-color: white"
          class="my-1 mr-1"
        />
      </div>
    </v-form>
  </div>
</template>

<script>
export default {
  name: "ApplicationCafFileFilter",
  data() {
    return {
      name: "",
      address: "",
      mobile: "",
    };
  },
  methods: {

  },
  watch:{

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
