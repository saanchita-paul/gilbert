<template>
    <v-row justify="center">
        <v-dialog
            v-model="dialog"
            persistent
            max-width="350px"
        >
            <v-card>
                <v-toolbar
                    dark
                    color="primary"
                >
                    <v-toolbar-title>Offices</v-toolbar-title>
                    <v-spacer></v-spacer>
                    <v-toolbar-items>
                        <v-btn
                            icon
                            dark
                            @click="closeModal"
                        >
                            <v-icon>mdi-close</v-icon>
                        </v-btn>
                    </v-toolbar-items>
                </v-toolbar>
                <v-container>
                    <v-list>
                        <div class="assigneesearch">
                            <v-text-field
                                label="Search"
                                outlined
                                dense
                                prepend-inner-icon="mdi-magnify"
                                hide-details="auto"
                            ></v-text-field>
                        </div>
                        <v-virtual-scroll
                            :items="items"
                            height="300"
                            item-height="64"
                        >
                            <template v-slot:default="{ item, index }">
                                <v-list-item :key="index" class="cursor-pointer list-tile" @click="openAgentsModal">
                                    <v-list-item-title>{{ item.title }}</v-list-item-title>
                                </v-list-item>
                                <v-divider></v-divider>
                            </template>
                        </v-virtual-scroll>
                    </v-list>
                </v-container>
            </v-card>
        </v-dialog>

        <AgentsModal v-if="showAgentsModal"
                     :dialog="showAgentsModal"
                     @closeAgentsModal="closeAgentsModal"/>
    </v-row>
</template>

<script>
import AgentsModal from "@scripts/components/crm/modals/AgentsModal";

export default {
    name      : "OfficesModal",
    props     : {
        dialog: {
            require: true,
        },
    },
    components: {
        AgentsModal
    },
    data      : () => ({
        showAgentsModal: false,
        items          : [
            {title: 'Click Me'},
            {title: 'Click Me'},
            {title: 'Click Me'},
            {title: 'Click Me 2'},
            {title: 'Click Me 2'},
            {title: 'Click Me 2'},
            {title: 'Click Me 2'},
            {title: 'Click Me 2'},
            {title: 'Click Me 2'},
            {title: 'Click Me 2'},
        ],
    }),
    methods   : {
        closeModal() {
            this.$emit('closeOfficesModal');
        },
        openAgentsModal() {
            this.showAgentsModal = true;
        },
        closeAgentsModal() {
            this.showAgentsModal = false;
        },
    }
}
</script>
