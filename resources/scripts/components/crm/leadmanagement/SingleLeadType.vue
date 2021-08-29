<template>
    <div class="applications-badge" v-bind:class="{active:type == active }" @click="changeLeadType(type)">
        <p class="title">{{title}}</p>
        <p class="number">{{count}}</p>
        <p class="text">{{subtext}}</p>
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
