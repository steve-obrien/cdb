<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import PasswordInput from '@/Components/PasswordInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
	invite: {type: Object},
});

const form = useForm({
	password: '',
	remember: true,
});

const submit = () => {
	form.post(route('team.invite.register', {token: props.invite.token}), {
		onFinish: () => form.reset('password'),
	});
};
</script>

<template>
	<GuestLayout>


		<Head title="Register" />

		<form @submit.prevent="submit">
			<div>
				<InputLabel for="email" value="Email" />

				{{invite.email}}
			</div>

			<div class="mt-4">
				<InputLabel for="password" value="Choose a password" />

				<PasswordInput
				id="password"
				class="mt-1 block w-full"
				v-model="form.password"
				required
				autocomplete="new-password" />

				<InputError class="mt-2" :message="form.errors.password" />
			</div>


			<div class="flex items-center justify-end mt-4">

				<PrimaryButton class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
					Register
				</PrimaryButton>
			</div>
		</form>

	</GuestLayout>
</template>
