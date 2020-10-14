<template>
    <v-container>
        <ChatUsers/>
        <ChatBox/>
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
