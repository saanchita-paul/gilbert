<template>
    <v-row justify="center">
        <v-dialog
            v-model="dialog"
            persistent
            max-width="400px"
        >
            <v-card>
                <v-container>
                    <Created  @cancelDialog="cancelDialog" :title="title" :isUpdate="isUpdate"></Created>
                </v-container>
            </v-card>
        </v-dialog>
    </v-row>
</template>

<script>
import Created from "@scripts/components/crm/Created";
export default {
    name: "CreateSuccessfulModal",
    components: {Created},
    props:['dialog','title','isUpdate'],
    methods: {
        cancelDialog() {
            this.$emit('cancel');
        }
    },

  watch:{
      title: function (newTitle, previousTitle) {
      }
  }

}
</script>

<style scoped>

</style>
