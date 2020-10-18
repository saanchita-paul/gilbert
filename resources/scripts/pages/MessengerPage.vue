<template>
    <v-container>
        <PageHeader :breadcrumbs="getBreadcrumbs" title="Messenger" />
            <v-row
                class="mt-5"
                no-gutters
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
import ChatUsers from "@scripts/components/customer/messenger/ChatUsers";
import PageHeader from "@scripts/components/common/PageHeader";
import ChatBox from "@scripts/components/customer/messenger/ChatBox";

export default {
    name: "MessengerPage",
    components: {ChatBox, ChatUsers, PageHeader},
    computed: {
        getBreadcrumbs() {
            return [
                {
                    text: 'Dashboard',
                    disabled: false,
                    route_name: 'customerAnalytics',
                },
                {
                    text: 'Messenger',
                    disabled: false,
                    route_name: 'messenger',
                },
            ]
        },
    }
}
</script>

<style scoped>

</style>
