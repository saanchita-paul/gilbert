<template>
    <v-container>
        <PageHeader title="Messenger" />
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
    components: {ChatBox, ChatUsers, PageHeader}
}
</script>

<style scoped>

</style>
