<template>
    <div class="d-flex">
        <v-tooltip bottom content-class='custom-tooltip'>
            <template v-slot:activator="{ on, attrs }">
                <span v-bind="attrs" v-on="on" class="mr-1">
                    <v-icon color="#000000">mdi-information-outline</v-icon>
                </span>
            </template>

            <v-card
                max-width="400"
                outlined
                elevation="24"
                class="rounded-xl"
            >
                <v-card-title>
                    <span>
                        <v-icon color="#000000" class="mr-1">
                            mdi-information-outline
                        </v-icon>
                    </span>
                    <p class="sub-title">Automatically assign leads to Chatbot.</p>
                </v-card-title>
                <v-card-text>
                    <p class="leade-text">Gilbert will automatically assign every future applications from opt-in REA
                        offices directly to the Chatbot.</p>
                </v-card-text>
            </v-card>
        </v-tooltip>

        <span class="mr-2">Automatically assign leads to Chatbot.</span>
        <v-switch
            inset
            style="margin: 0 !important;"
            v-model="auto_assign_to_chatbot"
            @change="save"
        >
        </v-switch>
    </div>
</template>

<script>
import AssignToChatbotSettingService from "@scripts/services/crm/AssignToChatbotSettingService";

export default {
    name: "AssignToChatbotSetting",
    props: {},
    data() {
        return {
            auto_assign_to_chatbot: false,
            test: false
        }
    },
    computed: {},
    mounted() {
        this.setData()
    },
    methods: {
        async save() {
            const response = await AssignToChatbotSettingService.saveData({
                'auto_assign_to_chatbot': this.auto_assign_to_chatbot
            });
            this.test = response.assignToChatbot;
        },
        setData() {
            this.auto_assign_to_chatbot = this.test
        }
    }
};
</script>

<style scoped>
.custom-tooltip {
    opacity: 1!important;
}
.v-tooltip__content {
    background-color: transparent;
}
.v-input--selection-controls {
    padding-top: 0 !important;
}
.sub-title {
    font-size: 16px !important;
}
.v-card__title {
    align-items: start !important;
    padding: 16px 16px 0 16px !important;
}
</style>
