<template>
    <div>
        <v-timeline dense>
            <v-timeline-item color="primary" small v-for="log in logs" :color="getColor(log.active)" :key="log.id">
                <v-card class="elevation-2 timeline-card status-log" color="gray">
                    <p class="heading">Status Change by Admin</p>
                    <p class="normal-text">00/00/2021_00:00:00</p>
                    <p class="normal-text-bolder">Reason: <span class="normal-text">This is where status change reason is displayed</span>
                    </p>
                    <!-- new status-->
                    <div class="mt-5">
                        <p class="normal-text-bolder">New Status:</p>
                        <p class="normal-text">Application: <span class="normal-text">Closed</span></p>
                        <p class="normal-text">Power: <span class="normal-text">Accepted</span></p>
                        <p class="normal-text">Gas: <span class="normal-text">Accepted</span></p>
                        <p class="normal-text">Water: <span class="normal-text">Accepted</span></p>
                        <p class="normal-text">Internet: <span class="normal-text">N/A</span></p>
                    </div>
                    <!-- previous status-->
                    <div class="mt-5">
                        <p class="normal-text-bolder">Previous Status:</p>
                        <p class="normal-text">Application: <span class="normal-text">Assigned</span></p>
                        <p class="normal-text">Power: <span class="normal-text">In Progress</span></p>
                        <p class="normal-text">Gas: <span class="normal-text">In Progress</span></p>
                        <p class="normal-text">Water: <span class="normal-text">In Progress</span></p>
                        <p class="normal-text">Internet: <span class="normal-text">N/A</span></p>
                    </div>
                </v-card>
            </v-timeline-item>
        </v-timeline>

        <v-overlay :value="overlay" absolute opacity="0.3">
            <v-progress-circular
                indeterminate
                size="64"
            ></v-progress-circular>
        </v-overlay>
    </div>
</template>

<script>
export default {
    name: "StatusLog",
    data() {
        return {
            overlay: false
        }
    },
    computed: {
        logs() {
            return [
                {id: 1, active: true},
                {id: 2, active: false},
                {id: 3, active: false},
            ]
        }
    },
    mounted() {
        this.overlay = true
    },
    methods: {
        getColor(isActive) {
            return isActive === true ? 'primary' : 'gray'
        }
    }
}
</script>

<style scoped>
.status-log {
    font-size: 14px;
    line-height: 20px !important;
    letter-spacing: 0.02em;
}

.heading {
    font-weight: 700;
    color: #263238;
}

.normal-text {
    font-weight: 400;
    color: #7E8A8F;
}

.normal-text-bolder {
    font-weight: 700 !important;
    color: #7E8A8F !important;
}
</style>
