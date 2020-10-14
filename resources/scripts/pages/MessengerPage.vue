<template>
    <v-container>
        <v-row
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
import ChatBox from "@scripts/components/customer/messenger/ChatBox";

export default {
    name: "MessengerPage",
    components: { ChatBox, ChatUsers }
}
</script>

<style scoped>

</style>
