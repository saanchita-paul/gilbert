<template>
        <v-row>
            <v-col cols="12">
                <span class="popup-title">
                    Agent’s Additional Instructions
                </span>
            </v-col>
            <v-col cols="12">
                    <v-textarea
                        outlined
                        placeholder="Additional Instructions goes here."
                    ></v-textarea>
            </v-col>
            <v-col cols="12">
                    <v-btn block color="primary">Close</v-btn>
            </v-col>
        </v-row>
</template>

<script>
export default {
  name: "AgentsAdditionalInstructions",
};
</script>

<style scoped>
</style>
