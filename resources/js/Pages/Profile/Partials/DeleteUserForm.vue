<script setup>
import DangerButton from '@/Components/DangerButton.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import Modal from '@/Components/Modal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import ActionSection from '@/Components/ActionSection.vue';
import { useForm } from '@inertiajs/vue3';
import { nextTick, ref } from 'vue';
import DialogModal from '@/Components/DialogModal.vue';

const confirmingUserDeletion = ref(false);
const passwordInput = ref(null);

const form = useForm({
	password: '',
});

const confirmUserDeletion = () => {
	confirmingUserDeletion.value = true;

	nextTick(() => passwordInput.value.focus());
};

const deleteUser = () => {
	form.delete(route('profile.destroy'), {
		preserveScroll: true,
		onSuccess: () => closeModal(),
		onError: () => passwordInput.value.focus(),
		onFinish: () => form.reset(),
	});
};

const closeModal = () => {
	confirmingUserDeletion.value = false;

	form.reset();
};
</script>

<template>
	<ActionSection>
		<template #title>Delete Account</template>
		<template #description>
			Permanently delete account
		</template>

		<template #content>
			<p class="mt-1 text-sm text-gray-600">
				Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting
				your account, please download any data or information that you wish to retain.
			</p>

			<DangerButton class="mt-4" @click="confirmUserDeletion">Delete Account</DangerButton>

			<DialogModal :show="confirmingUserDeletion" @close="closeModal">
				<template #title>
					Delete Account
				</template>
				<template #content>
					Are you sure you want to delete your account?

					<p class="mt-1 text-sm text-gray-600">
						Once your account is deleted, all of its resources and data will be permanently deleted. Please
						enter your password to confirm you would like to permanently delete your account.
					</p>

					<div class="mt-6">
						<InputLabel for="password" value="Password" class="sr-only" />

						<TextInput
						id="password"
						ref="passwordInput"
						v-model="form.password"
						type="password"
						class="mt-1 block w-3/4"
						placeholder="Password"
						@keyup.enter="deleteUser" />

						<InputError :message="form.errors.password" class="mt-2" />
					</div>
				</template>

				<template #footer>
					<SecondaryButton @click="closeModal"> Cancel </SecondaryButton>

					<DangerButton
					class="ml-3"
					:class="{ 'opacity-25': form.processing }"
					:disabled="form.processing"
					@click="deleteUser">
						Delete Account
					</DangerButton>
				</template>
			</DialogModal>
		</template>
	</ActionSection>
</template>
