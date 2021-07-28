<template>
    <v-row>
      <v-col class="section-dialogs" cols="12">
        <div class="dialogs-title">
          <p>Setup Commission Profile</p>
        </div>

        <div class="dialogs-area">
          <p class="title">Per successful connection</p>
          <v-text-field
            label="Power"
            placeholder="$ 50.00"
            outlined
            dense
            prepend-inner-icon="mdi-flash yellow--text"
          ></v-text-field>

          <v-text-field
            label="Gas"
            placeholder="$ 0.00"
            outlined
            dense
            prepend-inner-icon="mdi-fire red--text"
          ></v-text-field>
          <v-text-field
            label="Water"
            placeholder="$ 50.00"
            outlined
            dense
            prepend-inner-icon="mdi-water blue-grey--text"
          ></v-text-field>
          <v-text-field
            label="Internet"
            placeholder="$ 50.00"
            outlined
            dense
            prepend-inner-icon="mdi-wifi black--text"
          ></v-text-field>
        </div>
      </v-col>
    </v-row>
</template>

<script>
export default {
  name: "CommissionProfile",
};
</script>

<style scoped>
</style>
