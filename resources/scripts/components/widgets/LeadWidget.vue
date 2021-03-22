<template>
        <v-tooltip bottom >
            <template v-slot:activator="{ on, attrs }">
                <v-card v-if="tooltip" class="widget mx-2" v-bind="attrs"
                        v-on="on">
                    <div class="widget-container">
                        <h2 class="title">12.6K</h2>
                        <p class="mb-5">Total Leads</p>
                        <div class="small-chart">
                            <slot/>
                        </div>
                    </div>
                </v-card>
                <v-card class="widget mx-2" v-else>
                    <div class="widget-container">
                        <h2 class="title">12.6K</h2>
                        <p class="mb-5">Total Leads</p>
                        <div class="small-chart">
                            <slot/>
                        </div>
                    </div>
                </v-card>
            </template>
            <v-row class="small-text">
                <v-col md="6">
                    <p>10%</p>
                    <p>Qualified Lead</p>
                </v-col>
                <v-col md="6">
                    <p>90%</p>
                    <p>Total Conversion</p>
                </v-col>
            </v-row>
        </v-tooltip>
</template>

<script>

export default {
    name: "LeadWidget",
    props: {
        tooltip: {
            type: Boolean,
            default: false
        }
    }
}
</script>

<style scoped lang="scss">
.widget {
    width: 100%;
    height: 227px;
    background: #fff;
    overflow: hidden;
    padding: 0px 20px 20px 20px;
}

.widget-container {
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: flex-end;
    align-items: center;
    margin: auto;
}

.title {
    font-weight: 500;
    font-size: 58px !important;
    color: #263238 !important;
    margin-bottom: 15px;
}

.small-chart {
    width: 100%;
}

.small-text {
    p {
        margin: 0px;
        text-align: center;
    }
}
</style>
