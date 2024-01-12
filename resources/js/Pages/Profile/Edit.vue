<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import LogoutOtherBrowserSessionsForm from '@/Pages/Profile/Partials/LogoutOtherBrowserSessionsForm.vue';

import { Head } from '@inertiajs/vue3';

defineProps({
	mustVerifyEmail: {
		type: Boolean,
	},
	status: {
		type: String,
	},
	sessions: Array,
});
</script>

<template>
	<Head title="Profile" />

	<AuthenticatedLayout class="bg-gray-100">
		<template #header>
			<h2 class="font-semibold text-xl text-gray-800 leading-tight">Profile</h2>
		</template>

		<div class="py-12">
			<div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
				<UpdateProfileInformationForm
					:must-verify-email="mustVerifyEmail"
					:status="status"/>

				<UpdatePasswordForm />

				<LogoutOtherBrowserSessionsForm :sessions="sessions" class="mt-10 sm:mt-0" />

				<DeleteUserForm />
			</div>
		</div>
	</AuthenticatedLayout>
</template>
