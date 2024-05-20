<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref, reactive, watch } from 'vue'
import { Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot } from '@headlessui/vue'
import { XMarkIcon } from '@heroicons/vue/24/outline'
import { router } from '@inertiajs/vue3'
import axios from 'axios';

// Props:
///////////////////////////////////////////////////////////////////////////////
const props = defineProps({
	auth: { type: Object },
	users: { type: Object },
	invites: { type: Object },
});

// State:
///////////////////////////////////////////////////////////////////////////////
const localInvites = ref([]);
const tab = ref('Users');
const invite = reactive({
	state: false,
	name: null,
	email: null,
	errors: {}
})
window.invite = invite

// Watchers:
///////////////////////////////////////////////////////////////////////////////

// Watch the invites prop and update the localInvites
watch(() => props.invites, (newInvites) => {
	localInvites.value = newInvites;
}, { immediate: true });


window.localInvites = localInvites

// Methods:
///////////////////////////////////////////////////////////////////////////////

const open = () => {
	invite.state = 'open'
	invite.name = ''
	invite.email = ''

	Object.keys(invite.errors).forEach(key => delete invite.errors[key]);
}

const close = () => {
	invite.state = 'closed'
}

const inviteUser = async () => {
	try {

		const response = await axios.post(route('team.invite'), invite);
		console.log('User added successfully:', response);
		// Handle success (e.g., reset form, show success message)
		response.data.invite.isNew = true
		localInvites.value.unshift(response.data.invite)
		invite.name = '';
		invite.email = '';
		invite.state = 'success'
		tab.value = 'Invites'

		// Remove the isNew flag after a short delay
		setTimeout(() => {
			response.data.invite.isNew = false;
		}, 2000);


	} catch (error) {
		if (error.response && error.response.data && error.response.data.errors) {
			// Handle validation errors
			invite.errors = error.response.data.errors;
		} else {
			// Handle other errors (e.g., network issues)
			console.error('An error occurred:', error);
		}
	}
}

const deleteInvite = async (token) => {
	axios.delete(route('team.invite.delete', { token: token })).then(() => {
		const index = localInvites.value.findIndex(invite => invite.token === token);
		if (index !== -1) {
			localInvites.value.splice(index, 1);
		}
	})
}

</script>

<template>

	<Head title="Team" />

	<AuthenticatedLayout class="bg-gray-100 dark:bg-black">
		<template #header>
			<h2 class="font-semibold text-xl text-gray-800 leading-tight">Team</h2>
		</template>

		<div class="p-4 md:p-8">
			<div>
				<div class="sm:hidden">
					<label for="tabs" class="sr-only">Select a tab</label>
					<!-- Use an "onChange" listener to redirect the user to the selected tab URL. -->
					<select v-model="tab" @change="tab = this.value" id="tabs" name="tabs" class="block w-full rounded-md border-gray-300 py-2 pl-3 pr-10 text-base focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm">
						<option :selected="tab == 'Users'">Users</option>
						<option :selected="tab == 'Invites'">Invites</option>
					</select>
				</div>
				<div class="hidden sm:block">
					<div class="border-b border-gray-200 ">
						<nav class="-mb-px flex justify-between space-x-8" aria-label="Tabs">
							<div class="flex space-x-8">
								<a href="#" @click="tab = 'Users'" :class="[tab == 'Users' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:border-gray-200 hover:text-gray-700', 'flex whitespace-nowrap border-b-2 py-4 px-1 text-sm font-medium']" :aria-current="tab.current ? 'page' : undefined">
									Users
									<span v-if="users.length" :class="[tab == 'Users' ? 'bg-indigo-100 text-indigo-600' : 'bg-gray-100 text-gray-900', 'ml-3 hidden rounded-full py-0.5 px-2.5 text-xs font-medium md:inline-block']">{{ users.length }}</span>
								</a>
								<a href="#" @click="tab = 'Invites'" :class="[tab == 'Invites' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:border-gray-200 hover:text-gray-700', 'flex whitespace-nowrap border-b-2 py-4 px-1 text-sm font-medium']" :aria-current="tab.current ? 'page' : undefined">
									Invites
									<span v-if="localInvites.length" :class="[tab == 'Invites' ? 'bg-indigo-100 text-indigo-600' : 'bg-gray-100 text-gray-900', 'ml-3 hidden rounded-full py-0.5 px-2.5 text-xs font-medium md:inline-block']">{{ localInvites.length }}</span>
								</a>
							</div>
							<button @click="open()" type="button" class="ml-auto block my-2 rounded-full bg-black px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Add user</button>
						</nav>
					</div>
				</div>
			</div>

			<div v-if="tab == 'Users'" class="flow-root">
				<div class="-mx-4 overflow-x-auto sm:-mx-6 lg:-mx-8">
					<div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
						<div v-for="user in users" class="bg-white dark:bg-gray-800 py-4 px-7 grid grid-cols-3 my-2 rounded-sm">
							<div class="flex items-center">
								<img class="w-8 rounded-full mr-2" :src="user.avatar_url" />
								<span class="font-bold text-lg text-gray-900 dark:text-gray-100">{{ user.name }}</span>
								<span v-if="auth.user.id == user.id" class="bg-gray-200 dark:bg-gray-700 rounded-md px-2 ml-2">You</span>
							</div>
							<div class="flex">
								<span class="font-normal text-lg text-gray-700 dark:text-gray-300">{{ user.email }}</span>
							</div>
						</div>
					</div>
				</div>
			</div>


			<div v-if="tab == 'Invites'" class="flow-root">
				<div class="-mx-4 overflow-x-auto sm:-mx-6 lg:-mx-8">
					<div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
						<div v-if="localInvites.length">
							<transition-group name="flash" tag="div">
								<div v-for="user in localInvites" :key="user.email" class="group bg-white py-4 px-7 grid grid-cols-4 my-2 rounded-sm" :class="{ 'new-item': user.isNew }">
									<div class="flex items-center">
										<span class="font-bold text-lg">{{ user.name }}</span>
									</div>
									<div class="flex">
										<span class="font-normal text-lg">{{ user.email }}</span>
									</div>
									<div class="flex">
										<span class="font-normal text-lg">{{ user.created_at }}</span>
									</div>
									<div class="flex text-right">
										<button @click="deleteInvite(user.token)" class="hidden text-red-600 group-hover:block font-normal text-lg">delete</button>
									</div>
								</div>
							</transition-group>
						</div>
						<div v-if="localInvites.length == 0">
							Invite your team!
						</div>
					</div>
				</div>
			</div>
		</div>

		<!-- add user -->

		<TransitionRoot as="template" :show="['open', 'success'].includes(invite.state)">
			<Dialog class="relative z-50" @close="close()">
				<TransitionChild as="template" enter="ease-in-out duration-500" enter-from="opacity-0" enter-to="opacity-100" leave="ease-in-out duration-500" leave-from="opacity-100" leave-to="opacity-0">
					<div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity dark:bg-opacity-75" />
				</TransitionChild>

				<div class="fixed inset-0 overflow-hidden">
					<div class="absolute inset-0 overflow-hidden">
						<div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10">
							<TransitionChild as="template" enter="transform transition ease-in-out duration-500 sm:duration-700" enter-from="translate-x-full" enter-to="translate-x-0" leave="transform transition ease-in-out duration-500 sm:duration-700" leave-from="translate-x-0" leave-to="translate-x-full">
								<DialogPanel class="pointer-events-auto relative w-screen max-w-md">
									<TransitionChild as="template" enter="ease-in-out duration-500" enter-from="opacity-0" enter-to="opacity-100" leave="ease-in-out duration-500" leave-from="opacity-100" leave-to="opacity-0">
										<div class="absolute left-0 top-0 -ml-8 flex pr-2 pt-4 sm:-ml-10 sm:pr-4">
											<button type="button" class="relative rounded-md text-gray-300 hover:text-white focus:outline-none focus:ring-2 focus:ring-white dark:focus:ring-gray-600" @click="close()">
												<span class="absolute -inset-2.5" />
												<span class="sr-only">Close panel</span>
												<XMarkIcon class="h-6 w-6" aria-hidden="true" />
											</button>
										</div>
									</TransitionChild>
									<div class="flex h-full flex-col overflow-y-scroll bg-white dark:bg-gray-800 py-6 shadow-xl">
										<div class="px-4 sm:px-6">
											<DialogTitle class="text-xl font-semibold leading-6 text-gray-900 dark:text-gray-100">
												+ Add User
											</DialogTitle>
										</div>
										<div class="relative mt-6 flex-1 px-4 sm:px-6">
											<!-- Your content -->
											<form v-if="invite.state == 'open'" class="grid gap-5" @submit.prevent="inviteUser">
												<div>
													<label for="name" class="text-sm text-gray-700 dark:text-gray-300 mb-2">Name</label>
													<input v-model="invite.name" required id="name" name="name" type="text" tabindex="1" class="bg-gray-100 dark:bg-gray-700 w-full rounded-md border-2 dark:border-gray-600 focus:ring-0 focus:ring-black dark:focus:ring-white px-5 py-3" placeholder="Name">
													<span v-if="invite.errors.name" class="text-red-600">{{ invite.errors.name[0] }}</span>
												</div>
												<div>
													<label for="email" class="text-sm text-gray-700 dark:text-gray-300 mb-2">Email</label>
													<input v-model="invite.email" required id="email" name="email" type="email" tabindex="1" class="bg-gray-100 dark:bg-gray-700 w-full rounded-md border-2 dark:border-gray-600 focus:ring-0 focus:ring-black dark:focus:ring-white px-5 py-3" placeholder="Email">
													<span class="text-red-600" v-if="invite.errors.email">{{ invite.errors.email[0] }}</span>
												</div>
												<div><button tabindex="1" class="bg-black dark:bg-gray-700 px-3 py-3 w-full text-center text-sm font-semibold text-white shadow-sm rounded-full" type="submit">Invite User</button></div>
											</form>
											<div v-if="invite.state == 'success'" class="flex flex-col">
												<div class="bg-gray-100 dark:bg-gray-700 w-[350px] h-[350px] mx-auto flex flex-col items-center justify-center p-20 text-center rounded-full">
													<span class="text-xl font-medium dark:text-gray-100">New user added to workspace!</span>
													<p class="dark:text-gray-300">An invitation has been emailed with directions on how to complete their account creation.</p>
												</div>
												<button @click="close()" tabindex="1" class="mt-10 bg-black dark:bg-gray-700 px-5 py-3 mx-auto text-center text-sm font-semibold text-white shadow-sm rounded-full">Thanks!</button>
											</div>
										</div>
									</div>
								</DialogPanel>
							</TransitionChild>
						</div>
					</div>
				</div>
			</Dialog>
		</TransitionRoot>

	</AuthenticatedLayout>

</template>


<style scoped>
.flash-enter-active,
.flash-leave-active {
	transition: opacity 0.5s;
}

.flash-enter-from,
.flash-leave-to {
	opacity: 0;
}

.new-item {
	animation: flash-bg 2s;
}

@keyframes flash-bg {
	0% {
		background-color: yellow;
	}

	100% {
		background-color: white;
	}
}
</style>