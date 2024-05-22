<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import ActionSection from '@/Components/ActionSection.vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';

defineProps({
	mustVerifyEmail: {
		type: Boolean,
	},
	status: {
		type: String,
	},
});

const user = usePage().props.auth.user;
const photoInput = ref(null);
const photoPreview = ref(null);

const form = useForm({
	name: user.name,
	email: user.email,
	photo: null
});

const updateProfileInformation = () => {

	if (photoInput.value.files.length) {
		form.photo = photoInput.value.files[0];
	}

	form.post(route('profile.update'), {
		forceFormData: true,
		errorBag: 'updateProfileInformation',
		preserveScroll: true,
	});
};

const updatePhotoPreview = () => {
	const photo = photoInput.value.files[0];

	if (!photo) return;

	const reader = new FileReader();

	reader.onload = (e) => {
		photoPreview.value = e.target.result;
	};

	reader.readAsDataURL(photo);
};


const selectNewPhoto = () => {
	photoInput.value.click();
};


</script>

<template>
	<ActionSection>
		<template #title>Profile Information</template>

		<template #description>Update your account's profile information and email address.</template>

		<template #content>
			<form @submit.prevent="updateProfileInformation" class="mt-6 space-y-6">

				<!-- Profile Photo -->
				<div class="col-span-6 sm:col-span-4">
					<!-- Profile Photo File Input -->
					<input
					id="photo"
					name="photo"
					ref="photoInput"
					type="file"
					class="hidden"
					@change="updatePhotoPreview">

					<InputLabel for="photo" value="Photo" />

					<!-- Current Profile Photo -->
					<div v-show="!photoPreview" class="mt-2">
						<img :src="user.avatar_url" :alt="user.name" class="rounded-full h-20 w-20 object-cover">
					</div>

					<!-- New Profile Photo Preview -->
					<div v-show="photoPreview" class="mt-2">
						<span
						class="block rounded-full w-20 h-20 bg-cover bg-no-repeat bg-center"
						:style="'background-image: url(\'' + photoPreview + '\');'" />
					</div>

					<SecondaryButton class="mt-2 me-2" type="button" @click.prevent="selectNewPhoto">
						Select A New Photo
					</SecondaryButton>

					<SecondaryButton
					v-if="user.avatar_url"
					type="button"
					class="mt-2"
					@click.prevent="deletePhoto">
						Remove Photo
					</SecondaryButton>

					<progress v-if="form.progress" :value="form.progress.percentage" max="100">
						{{ form.progress.percentage }}%
					</progress>

					<InputError :message="form.errors.photo" class="mt-2" />
				</div>


				<div>
					<InputLabel for="name" value="Name" />

					<TextInput
					id="name"
					type="text"
					class="mt-1 block w-full"
					v-model="form.name"
					required
					autofocus
					autocomplete="name" />

					<InputError class="mt-2" :message="form.errors.name" />
				</div>

				<div>
					<InputLabel for="email" value="Email" />

					<TextInput
					id="email"
					type="email"
					class="mt-1 block w-full"
					v-model="form.email"
					required
					autocomplete="username" />

					<InputError class="mt-2" :message="form.errors.email" />
				</div>

				<div v-if="mustVerifyEmail && user.email_verified_at === null">
					<p class="text-sm mt-2 text-gray-800">
						Your email address is unverified.
						<Link
						:href="route('verification.send')"
						method="post"
						as="button"
						class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
						Click here to re-send the verification email.
						</Link>
					</p>

					<div
					v-show="status === 'verification-link-sent'"
					class="mt-2 font-medium text-sm text-green-600">
						A new verification link has been sent to your email address.
					</div>
				</div>

				<div class="flex items-center gap-4">
					<PrimaryButton :disabled="form.processing">Save</PrimaryButton>

					<Transition
					enter-active-class="transition ease-in-out"
					enter-from-class="opacity-0"
					leave-active-class="transition ease-in-out"
					leave-to-class="opacity-0">
						<p v-if="form.recentlySuccessful" class="text-sm text-gray-600">Saved.</p>
					</Transition>
				</div>
			</form>
		</template>
	</ActionSection>
</template>
