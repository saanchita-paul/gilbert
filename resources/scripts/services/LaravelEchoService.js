import Echo from "laravel-echo"
import Pusher from "pusher-js";

export const PUSHER = Pusher;

export const echo = () => {
    return new Echo({
        broadcaster: 'pusher',
        key: process.env.MIX_PUSHER_APP_KEY,
        cluster: process.env.MIX_PUSHER_APP_CLUSTER,
        encrypted: true,
    });
};

export default {
    echo
};
