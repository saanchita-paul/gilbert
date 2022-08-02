<template>
    <div class="applications-badge" v-bind:class="{active:isActive }" @click="changeLeadType(type)">
        <p class="font-weight-bold" style="font-size: 14px;">{{title}}</p>
        <p class="font-weight-bold">{{count}}</p>
        <p style="font-size: 12px;">{{subtext}}</p>
    </div>
</template>

<script>
export default {
name: "SingleLeadType",
    props:{

        title:{
            required:true
        },
        subtext: {
            required:true
        },

        count: {
            required:true
        },

        active: {
            required:true
        },

        type: {
            required:true
        },
        showDuplicate: {
            required:false
        }

    },
    methods: {
        changeLeadType(type) {
            this.$emit('changeLeadType',type);
        }
    },

    computed: {
        isActive() {
            return (this.active === this.type) || (this.type === 'duplicates' && this.showDuplicate) ;
        }
    }
}
</script>

<style scoped>

</style>
