<template>
    <div>
        <div class="d-flex">
            <div class="service-box" :class="{active: active}">
                <p class="mb-0"><v-icon :color="getColor(title)">{{icon}}</v-icon> {{title}}</p>
            </div>
        </div>
    </div>
</template>

<script>
export default {
name: "EneryService",
    props: {
        title: {
            require: true,
        },
        active: {
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
            return this.leadSummary.service_types.includes(service.toLowerCase())?true:false;

        },

        getColor(service) {
            if(this.isActive(service)) {
                if(service.toLowerCase() === 'power') {
                    return 'yellow';
                }
                if(service.toLowerCase() === 'gas') {
                    return 'red';
                }
                if(service.toLowerCase() === 'internet') {
                    return 'red';
                }
                if(service.toLowerCase() === 'water') {
                    return 'red';
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
        }
    },



    mounted() {
        this.setIcon(this.title);
    }
}
</script>

<style scoped>

</style>
