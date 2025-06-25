import axios from 'axios';
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.axios = axios;
window.Pusher = Pusher;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY || process.env.MIX_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER || process.env.MIX_PUSHER_APP_CLUSTER,
    wsHost: import.meta.env.VITE_PUSHER_HOST || 'ws-' + (import.meta.env.VITE_PUSHER_APP_CLUSTER || process.env.MIX_PUSHER_APP_CLUSTER) + '.pusher.com',
    wsPort: import.meta.env.VITE_PUSHER_PORT || 80,
    wssPort: import.meta.env.VITE_PUSHER_PORT || 443,
    forceTLS: (import.meta.env.VITE_PUSHER_SCHEME || process.env.MIX_PUSHER_APP_SCHEME) === 'https',
    enabledTransports: ['ws', 'wss'],
});
