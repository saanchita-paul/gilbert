<template>
        <v-tooltip bottom >
            <template v-slot:activator="{ on, attrs }">
                <v-card v-if="tooltip" class="widget mx-2" v-bind="attrs"
                        v-on="on">
                    <div class="widget-container">
                        <h2 class="widget-value">{{value}}</h2>
                        <p class="mb-5 lead-title">{{title}}</p>
                        <div class="small-chart">
                            <slot/>
                        </div>
                    </div>
                </v-card>
                <v-card class="widget mx-2" v-else>
                    <div class="widget-container">
                        <h2 class="widget-value">{{value}}</h2>
                        <p class="mb-5 lead-title">{{title}}</p>
                        <div class="small-chart">
                            <slot/>
                        </div>
                    </div>
                </v-card>
            </template>
            <v-row class="small-text">
                <v-col md="6" v-for="(item, index) in tooltip" :key="index">
                    <p>{{item.value}}</p>
                    <p>{{item.title}}</p>
                </v-col>
            </v-row>
        </v-tooltip>
</template>

<script>

export default {
    name: "LeadWidget",
    props: {
        tooltip: {
            default: null
        },
        title: {
            required: true,
            type: String
        },
        value: {
            required: true,
            type: String | Number
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

.widget-value {
    font-weight: 500;
    font-size: 58px !important;
    color: #263238 !important;
    margin-bottom: -4px;
}

.small-chart {
    width: 100%;
}

.lead-title {
    color: #828282 !important;
    font-size: .8rem;
}

.small-text {
    p {
        margin: 0px;
        text-align: center;
    }
}
</style>
