<template>
    <div class="d-flex justify-space-between">
        <div class="d-flex mt-5 mb-1">
            <div class="mx-2 buttonLarge">
                <v-btn
                    :text="dateType === 'BasedOnSubmittedDate'"
                    @click="changeType('created_date')"
                    :class="getButtonClass('BasedOnCreatedDate')"
                >
                    Based on applications created on this date (subset of above)
                </v-btn>
            </div>

            <div class="buttonLarge">
                <v-btn
                    :text="dateType === 'BasedOnCreatedDate'"
                    @click="changeType('submitted_date')"
                    :class="getButtonClass('BasedOnSubmittedDate')"
                >
                    Based on applications submitted on this date
                </v-btn>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "OpsDateTypeToggler",
    props: ['type'],
    data() {
        return {
            dateType: this.type,
        };
    },
    methods: {
        changeType(name) {
            if(this.$route.query.type !== name){
                this.dateType = name === 'created_date' ? 'BasedOnCreatedDate' : 'BasedOnSubmittedDate';
                this.$emit('changeType', name);
            }
        },
        getButtonClass(name){
            return this.dateType === name ? 'buttonActive' : 'buttonInactive';
        },
    }
}
</script>

<style scoped>
    .buttonActive{
        background: #DDE2FF !important;
        color:  #542E89 !important;
        cursor: default !important;
    }
    .buttonInactive{
        /* background: #C0C3C4 !important; */
        color: #263238 !important;
    }
</style>
