import 'pusher-js';
import Echo from "laravel-echo";
import axios from 'axios';


export const initializeBroadcasting = (userId) => {
    const echo = new Echo({
        broadcaster: "pusher",
        cluster: process.env.MIX_PUSHER_APP_CLUSTER,
        encrypted: true,
        key: process.env.MIX_PUSHER_APP_KEY,
        authorizer: (channel, options) => {
            return {
                authorize: (socketId, callback) => {
                    console.log('CHANNEL', channel.name, socketId)
                    axios.post('/api/broadcasting/auth', {
                        socket_id: socketId,
                        channel_name: channel.name
                    })
                        .then(response => {
                            callback(false, response.data, "ECHO SUCCESS");
                        })
                        .catch(error => {
                            callback(true, error, "NO! ERROR!!!");
                        });
                }
            };
        },
    })
        .private(`users.${userId}`)
        // .channel(`private-test-${userId}`)
        .notification((message) => {
            handleNotification(message);
        });

    // echo.private(`users.${userId}`)
    //     .listen('App\\Events\\TestingEvent', event => handleEvent(event))
}

const handleEvent = event => {
    console.log('EVENT', event)
}
const handleNotification = (notification) => {
    console.log('notification', event)
}
