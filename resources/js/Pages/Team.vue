<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue'
import { Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot } from '@headlessui/vue'
import { XMarkIcon } from '@heroicons/vue/24/outline'

const open = ref(false)

defineProps({
	auth: {
		type: Object
	},
	users: {
		type: Object,
	},
});
</script>

<template>

	<Head title="Dashboard" />

	<AuthenticatedLayout class="bg-gray-100">
		<template #header>
			<h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
		</template>

		<div class="p-4 md:p-8">
			<div class="sm:flex sm:items-center">
				<div class="sm:flex-auto">
					<h1 class="text-base font-semibold leading-6 text-gray-900">Users</h1>
					<p class="mt-2 text-sm text-gray-700">A list of all the users in your account including their name, title, email and role.</p>
				</div>
				<div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
					<button @click="open = true" type="button" class="block rounded-full bg-black px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Add user</button>
				</div>
			</div>

			<div class="mt-8 flow-root">
				<div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
					<div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
						<div class="grid grid-cols-3 bg-white py-4 px-7">
							<div class="">Name</div>
							<div>Email</div>
						</div>
						<div v-for="user in users" class="bg-white py-4 px-7 grid grid-cols-3 my-2 rounded-sm">
							<div class="flex items-center">
								<img class="w-8 rounded-full mr-2" :src="user.avatar_url" />
								<span class="font-bold text-lg">{{ user.name }}</span>
								<span v-if="auth.user.id == user.id" class="bg-gray-200 rounded-md px-2 ml-2">You</span>
							</div>
							<div class="flex">
								<span class="font-normal text-lg">{{ user.email }}</span>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<!-- add user -->


	</AuthenticatedLayout>

		<TransitionRoot as="template" :show="open">
			<Dialog class="relative z-50" @close="open = false">
				<TransitionChild as="template" enter="ease-in-out duration-500" enter-from="opacity-0" enter-to="opacity-100" leave="ease-in-out duration-500" leave-from="opacity-100" leave-to="opacity-0">
					<div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" />
				</TransitionChild>

				<div class="fixed inset-0 overflow-hidden">
					<div class="absolute inset-0 overflow-hidden">
						<div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10">
							<TransitionChild as="template" enter="transform transition ease-in-out duration-500 sm:duration-700" enter-from="translate-x-full" enter-to="translate-x-0" leave="transform transition ease-in-out duration-500 sm:duration-700" leave-from="translate-x-0" leave-to="translate-x-full">
								<DialogPanel class="pointer-events-auto relative w-screen max-w-md">
									<TransitionChild as="template" enter="ease-in-out duration-500" enter-from="opacity-0" enter-to="opacity-100" leave="ease-in-out duration-500" leave-from="opacity-100" leave-to="opacity-0">
										<div class="absolute left-0 top-0 -ml-8 flex pr-2 pt-4 sm:-ml-10 sm:pr-4">
											<button type="button" class="relative rounded-md text-gray-300 hover:text-white focus:outline-none focus:ring-2 focus:ring-white" @click="open = false">
												<span class="absolute -inset-2.5" />
												<span class="sr-only">Close panel</span>
												<XMarkIcon class="h-6 w-6" aria-hidden="true" />
											</button>
										</div>
									</TransitionChild>
									<div class="flex h-full flex-col overflow-y-scroll bg-white py-6 shadow-xl">
										<div class="px-4 sm:px-6">
											<DialogTitle class="text-xl font-semibold leading-6 text-gray-900">
												+ Add User
											</DialogTitle>
										</div>
										<div class="relative mt-6 flex-1 px-4 sm:px-6">
											<!-- Your content -->
											<div class="grid gap-5">
												<div>
													<label for="name" class="text-sm text-gray-700 mb-2">Name:</label>
													<input id="name" name="name" type="text" tabindex="1" class="bg-gray-100 w-full rounded-md border-2 focus:ring-0 focus:ring-black px-5 py-3" placeholder="Name">
												</div>
												<div>
													<label for="email" class="text-sm text-gray-700 mb-2">Email:</label>
													<input id="email" name="email" type="email" tabindex="1" class="bg-gray-100 w-full rounded-md border-2 focus:ring-0 focus:ring-black px-5 py-3" placeholder="Email">
												</div>
												<div><button>Add User</button></div>
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
</template>
