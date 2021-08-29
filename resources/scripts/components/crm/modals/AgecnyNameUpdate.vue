<template>
    <ValidationObserver ref="create_agency">
        <v-row>
            <v-col class="section-dialogs" cols="12">
                <div class="dialogs-title">
                    <p>Agency Details</p>
                </div>

                <div class="dialogs-area">
                    <ValidationProvider name="Title" rules="required"  v-slot="{ errors }">
                        <v-text-field v-model="agency.title"
                                      label="Company Name"
                                      placeholder="Barry Plant"
                                      outlined
                                      dense
                                      :error-messages=" errors[0]"
                        ></v-text-field>
                    </ValidationProvider>
                </div>
                <div class="d-flex justify-space-between">
                    <v-btn @click="cancel"
                    >Cancel
                    </v-btn>
                    <v-btn @click="saveAgency"
                           color="primary"
                    >Save
                    </v-btn>
                </div>
            </v-col>

        </v-row>
    </ValidationObserver>
</template>

<script>
export default {
name: "AgecnyNameUpdate",

    props:['title'],
    data() {
        return {
            agency :{
                title: ''
            }
        }
    },
    methods: {
        cancel() {
            this.$emit('cancelDialog');
        },
        async saveAgency() {

            let v = await this.$refs.create_agency.validate();
            if (v) {
                this.$emit('saveAgency',this.agency.title);
            }
            return v;
        }
    },
    mounted() {
        this.agency.title = this.title;
    }
}
</script>

<style scoped>

</style>
