<template>
    <v-col cols="4">
        <p class="mb-0  text-center service"><v-icon :color="getColor()">{{icon}}</v-icon>{{title}}</p>
        <v-row>
            <v-col>
                <v-row class="matrics text-center">
                    <p class="matrics-title mb-1">{{ data.submitted }}</p>
                    <p class="matrics-subtitle">submitted</p>
                </v-row>
            </v-col>
            <v-col cols="1" class="text-center">
                <v-divider vertical></v-divider>
            </v-col>
            <v-col>
                <v-row class="matrics text-center">
                    <p class="matrics-title mb-1">{{  data.connected }}</p>
                    <p class="matrics-subtitle">Connected</p>
                </v-row>
            </v-col>
        </v-row>
    </v-col>
</template>

<script>
export default {
name: "ServiceMetrics",
    props: ['data', 'title'],
    data() {
    return {
      icon: ''
    }
    },
    methods: {
        getColor()
        {
            switch (this.title.toLowerCase()) {
                case 'electricity':
                    this.icon = 'mdi-flash';
                    return 'yellow'
                case 'gas':
                    this.icon = 'mdi-fire';
                    return 'orange';
                case 'internet':
                    this.icon = 'mdi-wifi';
                    return '#9C27B0';
                default:
                    this.icon = 'mdi-wifi';
                    return '#9C27B0';
            }
        }
    }
}
</script>

<style scoped>


.matrics{
    display: flex;
    flex-direction: column;
}
.matrics-title {
    font-size: 1.5em;
    color: #542E89;
    font-weight: 700;
}
.matrics-subtitle{
    font-size:  0.75em;
    font-weight: normal;
    color: #7E8A8F;
}
.service {
    font-size: 0.875em;
    font-weight: 700;
}


</style>
