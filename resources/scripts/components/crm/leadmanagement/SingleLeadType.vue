<template>
    <div class="applications-badge" v-bind:class="{active:title == active }" @click="changeLeadType(title)">
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
        }

    },
    methods: {
        changeLeadType(title) {
            this.$emit('changeLeadType',title);
        }
    }
}
</script>

<style scoped>

</style>
