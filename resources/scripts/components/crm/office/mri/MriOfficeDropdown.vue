<template>
    <div>
        <v-checkbox
            v-model="isMriOffice"
            :label="`MRI Office`"
        >
        </v-checkbox>

        <ValidationProvider name="MRI Office" rules="required" v-slot="{ errors }" v-if="isMriOffice">
            <v-select
                @change="changeMRI"
                return-object
                outlined
                dense
                :items="mriOfficeList"
                item-text="company_name"
                item-value="key"
                v-model="mriOffice"
                :error-messages=" errors[0]"
                label="Please select MRI office">
            </v-select>
        </ValidationProvider>
    </div>
</template>

<script>
import MRIService from "@scripts/services/crm/MRIService";

export default {
    name: "MriOfficeDropdown",
    props: {
        mriOfficeData: {
            required: false
        }
    },
    data() {
        return {
            isMriOffice: false,
            mriOffice: null,
            mriOfficeList: []
        };
    },
    async mounted() {
        await this.getMRIOffices();
        await this.updateMriData();
    },
    methods: {
        async getMRIOffices() {
            this.mriOfficeList = await MRIService.getMriOfficeList();
        },
        changeMRI(item) {
            this.$emit('saveMriOffice', item);
        },
        updateMriData() {
            this.isMriOffice = this.mriOfficeData ? this.mriOfficeData?.isMriOffice : false;
            this.mriOffice = this.mriOfficeData ? this.mriOfficeData?.selectedMriDropdownItem : null;
        },
    },
    watch: {
        isMriOffice(isChecked) {
            if (!isChecked) {
                this.$emit('saveMriOffice', null);
            }
        },
    },
};
</script>

<style scoped>
</style>
