<template>
    <v-container>
        <PageHeader :breadcrumbs="getBreadcrumbs" title="Conversations" />
            <v-row
                class="mt-2"
            >
                <v-col cols="4">
                    <ChatUsers/>
                </v-col>
                <v-col cols="8">
                    <ChatBox/>
                </v-col>
            </v-row>
    </v-container>
</template>

<script>
import ChatUsers from "@scripts/components/messenger/ChatUsers";
import PageHeader from "@scripts/components/common/PageHeader";
import ChatBox from "@scripts/components/messenger/ChatBox";

export default {
    name: "MessengerPage",
    components: {ChatBox, ChatUsers, PageHeader},
    computed: {
        getBreadcrumbs() {
            return [
                {
                    text: 'Customer',
                    disabled: false,
                    route_name: 'dashboard',
                },
                {
                    text: 'Conversations',
                    disabled: false,
                    route_name: 'customers.conversation',
                },
            ]
        },
    }
}
</script>

<style scoped>

</style>
