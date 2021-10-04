<template>
        <div class="service-box" :class="{active: isActive(title)}">
            <p class="mb-0 font-weight-bold"><v-icon :color="getColor(title)">{{icon}}</v-icon> {{title}}</p>
        </div>
</template>

<script>
export default {
name: "EnergyService",
    props: {
        title: {
            require: true,
        },
        leadSummary: {
            require: true
        }
    },
    data() {
        return {
            icon: null,
        }
    },
    methods: {
        isActive(service) {
            return this.leadSummary.service_interests.includes(service.toLowerCase())?true:false;

        },

        getColor(service) {
            if(this.isActive(service)) {
                if(service.toLowerCase() === 'power') {
                    return 'yellow';
                }
                if(service.toLowerCase() === 'gas') {
                    return 'orange';
                }
                if(service.toLowerCase() === 'internet') {
                    return '#9C27B0';
                }
                if(service.toLowerCase() === 'water') {
                    return 'blue';
                }
            }
            return 'grey lighten-1';
        },

        setIcon(service)
        {
            if(service.toLowerCase() === 'power') {
                this.icon = 'mdi-flash'
            }
            if(service.toLowerCase() === 'gas') {
                this.icon = 'mdi-fire'
            }
            if(service.toLowerCase() === 'internet') {
                this.icon = 'mdi-wifi'
            }
            if(service.toLowerCase() === 'water') {
                this.icon = 'mdi-water'
            }
        },

        updateService(service)
        {
            this.$emit('updateService', service);
        }
    },



    mounted() {
        this.setIcon(this.title);
    }
}
</script>

<style scoped>

</style>
