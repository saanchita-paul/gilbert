<template>
    <div class="applications-badge" v-bind:class="{active:type == active }" @click="changeLeadType(type)">
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
        }

    },
    methods: {
        changeLeadType(type) {
            this.$emit('changeLeadType',type);
        }
    }
}
</script>

<style scoped>

</style>
