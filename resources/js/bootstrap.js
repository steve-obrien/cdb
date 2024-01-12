/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

import axios from 'axios';

window.axios = axios;

// axios.defaults.withCredentials = true;
// axios.defaults.withXSRFToken = true;
// Get the CSRF token from the meta tag and set it as a default Axios header
// let token = document.head.querySelector('meta[name="csrf-token"]');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
window.axios.defaults.withXSRFToken = true
// window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;
// Enable pusher logging - don't include this in production
Pusher.logToConsole = true;


// laravel websockets
// window.Echo = new Echo({
// 	broadcaster: 'pusher',
// 	key: import.meta.env.VITE_PUSHER_APP_KEY,
// 	cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER ?? 'mt1',
// 	wsHost: import.meta.env.VITE_PUSHER_HOST,
// 	wsPort: import.meta.env.VITE_PUSHER_PORT ?? 6001,// 80,
// 	wssPort: import.meta.env.VITE_PUSHER_PORT ?? 6001,// 443,
// 	forceTLS: (import.meta.env.VITE_PUSHER_SCHEME ?? 'https') === 'https',
// 	enabledTransports: ['ws', 'wss'],
// });

// soketi
// window.Echo = new Echo({
// 	broadcaster: 'pusher',
// 	key: import.meta.env.VITE_PUSHER_APP_KEY,
// 	cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER ?? 'mt1',
// 	wsHost: import.meta.env.VITE_PUSHER_HOST ? import.meta.env.VITE_PUSHER_HOST : `ws-${import.meta.env.VITE_PUSHER_APP_CLUSTER}.pusher.com`,
// 	wsPort: import.meta.env.VITE_PUSHER_PORT ?? 80,
// 	wssPort: import.meta.env.VITE_PUSHER_PORT ?? 443,
// 	forceTLS: false,
// 	enabledTransports: ['ws', 'wss'],
// });

// pusher soketi
window.Echo = new Echo({
	broadcaster: 'pusher',
	key: 'd0e865d808ad494a0c37',
	cluster: 'eu',
	wsHost: import.meta.env.VITE_PUSHER_HOST ? import.meta.env.VITE_PUSHER_HOST : `ws-${import.meta.env.VITE_PUSHER_APP_CLUSTER}.pusher.com`,
	wsPort: import.meta.env.VITE_PUSHER_PORT,
	wssPort: import.meta.env.VITE_PUSHER_PORT,
	forceTLS: true,
	enabledTransports: ['ws', 'wss'],
});