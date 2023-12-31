<template>
	<Head title="Dashboard" />

	<AuthenticatedLayout>
		<template #header>
			<h2 class="font-semibold text-xl text-gray-800 leading-tight">Chat</h2>
		</template>

		<div class="flex h-full">
			<div class="h-full relative w-80 flex flex-col stretch">
				<div class="shrink p-2">
					<Link :href="linkChat" class="flex">
					<div class="grow">Create new</div>
					<svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
						<path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
					</svg>
					</Link>
				</div>
				<div class="grow relative stretch">
					<div class="absolute inset-0 overflow-scroll space-y-2 p-2 ">
						<div class="group border-b relative" v-for="session in sessions" :key="session.id">
							<Link :class="{'font-bold': isCurrent(session.id)}" class="block" :href="linkSession(session.id)">{{ sessionName(session) }} </Link>
							<button class="hidden group-hover:block absolute top-0 right-0" @click="deleteSession(session.id)">delete</button>
						</div>
					</div>
				</div>
			</div>
			<div class="@container h-full grow flex flex-col bg-gray-100">
				<slot></slot>
			</div>
		</div>

	</AuthenticatedLayout>
</template>
<script >
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { defineComponent, ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import { Axios } from 'axios';

export default defineComponent({
	props: {
		sessions: Array,
	},
	components: { AuthenticatedLayout, Head, Link },
	computed: {
		linkChat() {
			return route('chat')
		}
	},
	methods: {
		isCurrent(id) {
			return this.$page.props.sessionId == id
		},
		sessionName(session) {
			if (session.prompt) {
				return session.prompt.slice(0,25)
			}
			return session.id
		},
		linkSession(id) {
			return route('chat.session', id)
		},
		deleteSession: async function (id) {
			axios.delete(route('api.chatSessionDelete', id)).then(() => {
				let index = this.sessions.findIndex(item => item.id === id);
				// If the object exists, use splice() to remove the object
				if (index !== -1) {
					this.sessions.splice(index, 1);
				}
			})
		}
	}
})
</script>

<style>
.prose {
	--tw-prose-pre-bg: black
}
</style>