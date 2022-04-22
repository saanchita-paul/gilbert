<template>
    <div v-if="leadSummary.is_temporary_connection">
        <v-col cols="12">
            <b>
                There is a request for temporary connection for this property.
            </b>
        </v-col>
        <ValidationObserver ref="temporaryConnection">
            <v-col cols="12">
                <v-row>
                    <v-col cols="3" class="pt-5">
                        <b>Temp Connection</b>
                    </v-col>
                    <v-col cols="4">
                        <v-menu
                            v-model="connection_date_menu"
                            :close-on-content-click="false"
                            :nudge-right="40"
                            transition="scale-transition"
                            offset-y
                            min-width="290px"
                        >
                            <template v-slot:activator="{ on, attrs }">
                                <ValidationProvider
                                    name="Connection Date"
                                    rules="required|valid-date|not-holiday:@h_state"
                                    v-slot="{ errors }"
                                >
                                    <v-text-field
                                        placeholder="DD/MM/YYYY"
                                        outlined
                                        dense
                                        v-bind="attrs"
                                        append-icon="mdi-calendar"
                                        v-model="modified_moving_date"
                                        :error-messages="errors[0]"
                                        hide-details="auto"
                                    >
                                        <template slot="append">
                                            <v-icon v-on="on">mdi-calendar</v-icon>
                                        </template>
                                    </v-text-field>
                                </ValidationProvider>
                            </template>
                            <v-date-picker
                                v-model="moving_date"
                                @input="updateMovingDate"
                            ></v-date-picker>
                        </v-menu>
                    </v-col>
                </v-row>
            </v-col>
        </ValidationObserver>
    </div>          
</template>

<script>
import { formatDate } from "@scripts/services/others/DateService";

export default {
    name: "TemporaryConnection",
    props: {
        leadSummary: {
            require: true
        },
    },
    data() {
        return {
             connection_date_menu: false,
             moving_date: null,
             modified_moving_date: null,
        };
    },
    mounted() {
        this.modified_moving_date = formatDate(this.leadSummary.moving_date);
        this.moving_date = this.leadSummary.moving_date;

        const updateMovingDate = async value => {
            this.modified_moving_date = formatDate(value);
            await this.$refs.temporaryConnection?.validate();
        };

        this.$eventBus.$on("update_temporary_date", updateMovingDate);
        this.$once("hook:beforeDestroy", () => {
            this.$eventBus.$off("update_temporary_date", updateMovingDate);
        });
    },
    methods: {
        updateMovingDate(value) {
            this.connection_date_menu = false;
            this.modified_moving_date = formatDate(this.moving_date)
            this.$eventBus.$emit("update_moving_date", this.modified_moving_date)
        },
    },
};
</script>
