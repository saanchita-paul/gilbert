<template>
    <v-row>
      <v-col class="section-dialogs" cols="12">
        <div class="dialogs-title d-flex justify-center">
          <p v-if="!isUpdate">{{title}} has been created!</p>
          <p v-if="isUpdate">{{title}} has been Updated!</p>
        </div>
          <div class="d-flex justify-center">
              <v-btn @click="cancel"
                     color="primary"
              >Done
              </v-btn>
          </div>
      </v-col>

    </v-row>
</template>

<script>
export default {
  name: "Created",
    props:['title','isUpdate'],
    methods: {
        cancel() {
            this.$emit('cancelDialog');
        }
    }
};
</script>

<style scoped>
</style>
