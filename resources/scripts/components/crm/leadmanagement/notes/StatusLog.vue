<template>
    <div>
        <v-timeline dense>
            <v-timeline-item color="primary" small v-for="(log, key) in statusLogs"
                             :color="getColor(key)"
                             :key="key">
                <v-card class="elevation-2 timeline-card status-log" color="gray">
                    <p class="heading">{{ log.title }}</p>
                    <p class="normal-text">{{ log.created_at | formatDate }}</p>
                    <p class="normal-text-bolder">
                        Reason: <span class="normal-text">{{ log.status_change_reason || 'N/A' }}</span>
                    </p>
                    <!-- new status-->
                    <div class="mt-5" v-if="log.data.hasOwnProperty('new_status')">
                        <p class="normal-text-bolder">New Status:</p>
                        <p class="normal-text">
                            Application: <span class="normal-text">{{ log.data.new_status.application || 'N/A' }}</span>
                        </p>
                        <p class="normal-text">
                            Power: <span class="normal-text">{{ log.data.new_status.power || 'N/A' }}</span>
                        </p>
                        <p class="normal-text">
                            Gas: <span class="normal-text">{{ log.data.new_status.gas || 'N/A' }}</span>
                        </p>
                        <p class="normal-text">
                            Water: <span class="normal-text">{{ log.data.new_status.water || 'N/A' }}</span>
                        </p>
                        <p class="normal-text">
                            Internet: <span class="normal-text">{{ log.data.new_status.internet || 'N/A' }}</span>
                        </p>
                    </div>
                    <!-- previous status-->
                    <div class="mt-5" v-if="log.data.hasOwnProperty('old_status')">
                        <p class="normal-text-bolder">Previous Status:</p>
                        <p class="normal-text">
                            Application: <span class="normal-text">{{ log.data.old_status.application || 'N/A' }}</span>
                        </p>
                        <p class="normal-text">
                            Power: <span class="normal-text">{{ log.data.old_status.power || 'N/A' }}</span>
                        </p>
                        <p class="normal-text">
                            Gas: <span class="normal-text">{{ log.data.old_status.gas || 'N/A' }}</span>
                        </p>
                        <p class="normal-text">
                            Water: <span class="normal-text">{{ log.data.old_status.water || 'N/A' }}</span>
                        </p>
                        <p class="normal-text">
                            Internet: <span class="normal-text">{{ log.data.old_status.internet || 'N/A' }}</span>
                        </p>
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
import ApplicationServiceStatusChangeService from "@scripts/services/crm/ApplicationServiceStatusChangeService";
import dayJs from "dayjs";

export default {
    name: "StatusLog",
    props: {
        applicationId: {
            required: true,
            type: Number
        }
    },
    data() {
        return {
            overlay: false,
            statusLogs: [],
        }
    },
    filters: {
        formatDate: function (value) {
            if (value) {
                return dayJs(value).format('DD/MM/YYYY_hh:mm:ss')
            }
        }
    },
    mounted() {
        this.overlay = true
        this.getAllLogs();
        this.$eventBus.$on("manual_status_changed", () => {
            this.getAllLogs();
            this.$eventBus.$off("manual_status_changed");
        });
    },
    methods: {
        getColor(key) {
            return key === 0 ? 'primary' : 'gray'
        },
        async getAllLogs() {
            this.statusLogs = await ApplicationServiceStatusChangeService.getAllLogs(this.applicationId);
            this.overlay = false;
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
