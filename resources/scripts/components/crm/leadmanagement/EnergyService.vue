<template>
        <div class="service-box" :class="{active: isActive(title), 'not-editable': !isServiceEditable }">
            <p class="mb-0 font-weight-bold"><v-icon :color="getColor(title)">{{icon}}</v-icon> {{title}}</p>
            <p
                v-if="this.statusObj"
                class="ma-0"
                :style="{color: this.statusObj.color}"
            >{{this.statusObj.text}}
            </p>
            <p class="reason ma-0" v-for="reason in reasons" :key="reason">
                {{reason}}
            </p>
        </div>
</template>

<script>
import LeadApplicationService from "@scripts/services/crm/LeadApplicationService";

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
    computed: {
        statusObj() {
            return this.title && this.service ? LeadApplicationService.mapStatus(this.service.status) : null
        },
        service() {
            if(this.title) {
                return this.leadSummary.connection_services?.find(service => service.service_type === this.title.toLowerCase())
            }
            return null
        },
        reasons() {
            const reasons = this.service?.reasons;

            if (Array.isArray(reasons)) {
                return reasons.map(reason => reason?.reason_text || '')
            }
            return []
        },
        isServiceEditable() {
            return LeadApplicationService.canEditService(this.leadSummary.connection_services, this.title?.toLowerCase())
        },

    },
    methods: {

        isActive(service) {
            return this.leadSummary.service_interests.includes(service.toLowerCase());
        },

        getColor(service) {
            if (this.isActive(service)) {
                if (service.toLowerCase() === 'power') {
                    return 'yellow';
                }
                if (service.toLowerCase() === 'gas') {
                    return 'orange';
                }
                if (service.toLowerCase() === 'internet') {
                    return '#9C27B0';
                }
                if (service.toLowerCase() === 'water') {
                    return 'blue';
                }
            }
            return 'grey lighten-1';
        },

        setIcon(service) {
            if (service.toLowerCase() === 'power') {
                this.icon = 'mdi-flash'
            }
            if (service.toLowerCase() === 'gas') {
                this.icon = 'mdi-fire'
            }
            if (service.toLowerCase() === 'internet') {
                this.icon = 'mdi-wifi'
            }
            if (service.toLowerCase() === 'water') {
                this.icon = 'mdi-water'
            }
        },

        updateService(service) {
            this.$emit('updateService', service);
        }
    },


    mounted() {
        this.setIcon(this.title);
    }
}
</script>

<style scoped>
.not-editable {
    cursor: not-allowed;
}
.reason {
    font-size: .8em;
}
.status {

}
</style>
