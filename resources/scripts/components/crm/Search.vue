<template>
    <v-text-field
        label="Search"
        outlined
        dense
        prepend-inner-icon="mdi-magnify"
        hide-details="auto"
        v-model="search"
        @input="changeInput"
    ></v-text-field>
</template>

<script>
export default {
  name: "Search",
    data() {
      return {
          search: '',
      }
    },
    methods: {
        changeInput() {
            this.$emit('updateSearch', this.search);
        }
    }
};
</script>

<style scoped>
</style>
