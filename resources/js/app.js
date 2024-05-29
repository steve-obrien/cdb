import './bootstrap';
import '../css/app.css';

import { createApp, h } from 'vue';
import { createInertiaApp, router } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m';

const appName = import.meta.env.VITE_APP_NAME || 'Newicon';


const app = createInertiaApp({
	title: (title) => `${title} - ${appName}`,
	resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
	setup({ el, App, props, plugin }) {
		const app = createApp({
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
			},
		});
		app.use(plugin)
			.use(ZiggyVue, Ziggy)
			.mount(el);
		app.config.globalProperties.$route = route
		app.config.globalProperties.$router = router
	},
	progress: {
		color: '#4B5563',
	},
});