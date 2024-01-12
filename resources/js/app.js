import './bootstrap';
import '../css/app.css';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m';

const appName = import.meta.env.VITE_APP_NAME || 'Newicon';


createInertiaApp({
	title: (title) => `${title} - ${appName}`,
	resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
	setup({ el, App, props, plugin }) {
		return createApp({
			data() {
				return {
					channelTeam: null,
					users: []
				}
			},
			render: () => h(App, props),
			unmounted() {
				window.Echo.leave(`team`)
			},
			mounted(){
				window.app = this
				// global public websocket:
				// this.channelTeam = window.Echo.join(`team`)
				// 	.here((users) => {
				// 		console.log(users, 'team channel');
				// 		users.forEach(item => {
				// 			this.users[item.id] = item
				// 		})
				// 	})
				// 	.joining((user) => {
				// 		console.log('joining', user.name);


				// 	})
				// 	.leaving((user) => {
				// 		console.log('LEAVING', user.name);
				// 	})
				// 	.error((error) => {
				// 		console.error(error);
				// 	});
				// this.channelTeam.whisper('whisper', {
				// 	user_id: this.$page.props.auth.user.id,
				// 	location: window.location.href
				// })
				
				// this.channelTeam.listenForWhisper('whisper', (data) => {
					
				// })
				
			},
		})
			.use(plugin)
			.use(ZiggyVue, Ziggy)
			.mount(el);
	},
	progress: {
		color: '#4B5563',
	},
});
