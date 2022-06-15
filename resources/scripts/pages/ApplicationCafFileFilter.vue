<template>
    <div>
        <v-form ref="form" autocomplete="off">
            <v-row>
                <v-col cols="9">
                    <div class="d-flex">
                        <v-text-field
                            autocomplete="off"
                            v-model="name"
                            outlined
                            dense
                            hide-details="auto"
                            placeholder="Name"
                            style="background-color: white"
                            class="mr-2"
                        />
                        <v-text-field
                            v-model="address"
                            outlined
                            dense
                            hide-details="auto"
                            placeholder="Address"
                            style="background-color: white"
                            class="mr-2"
                        />
                        <v-text-field
                            v-model="phone"
                            outlined
                            dense
                            hide-details="auto"
                            placeholder="Mobile"
                            style="background-color: white"
                            class="mr-2"
                        />

                        <v-text-field
                            v-model="date"
                            outlined
                            dense
                            hide-details="auto"
                            placeholder="Date"
                            style="background-color: white"
                            class="mr-2"
                        />
                        <div
                            style="display: flex; align-items: center;">
                            <v-btn
                                small
                                tile
                                color="#e0e0e0"
                                @click="clearSearch">
                                <v-icon small left> mdi mdi-close</v-icon>
                                Reset
                            </v-btn>
                        </div>
                    </div>
                </v-col>
                <v-col cols="3">
                    <v-btn class="float-right" disabled>
                        Generate CAF File
                    </v-btn>
                </v-col>
            </v-row>
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
            phone: "",
            date: "",
        };
    },
    methods: {
        clearSearch() {
            this.$refs.form.reset();
        },
    },
    watch: {},

};
</script>

<style scoped>

</style>
