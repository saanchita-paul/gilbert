<template>
    <v-row>
      <v-col class="section-dialogs" cols="12">
        <div class="dialogs-title d-flex justify-center">
          <p>{{title}} has been created!</p>
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
    props:['title'],
    methods: {
        cancel() {
            this.$emit('cancelDialog');
        }
    }
};
</script>

<style scoped>
</style>
