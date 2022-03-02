<template>
    <v-text-field
        label="Search"
        outlined
        dense
        prepend-inner-icon="mdi-magnify"
        hide-details="auto"
        v-model="search"
        @input="changeInput"
        clearable
        class="app-search"
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
    },
    watch: {
        '$route': {
            handler() {
                this.search = '';
                this.changeInput();
            }
        },
    },
};
</script>

<style scoped>
.app-search{
    border-radius: 8px !important;
    background: white;
}
</style>
