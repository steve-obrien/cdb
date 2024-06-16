<template>

	<Head title="Chat" />

	<AuthenticatedLayout class="bg-gray-50 dark:bg-black">

		<template #header>
			<h2 class="font-semibold text-xl text-gray-800 leading-tight">Ui</h2>
		</template>

		<div class="flex flex-col h-full">

			<img class="absolute z-10 inset-0 object-cover pointer-events-none" src="/img/bg.svg" />
			<div class="@container z-20 items-center justify-center grow flex flex-col">
				<div class="z-20 my-10 w-full">
					<PromptForm class="w-full"  @send="send" @changeImages="changeImages" :images="promptImages" v-model:prompt="prompt" rows="2" placeholder="tweaks"></PromptForm>
				</div>
			</div>
			<div class="z-10">
				<Editor class="border-2 mx-5 lg:mx-20" v-model="ui.html"></Editor>
			</div>
		</div>

		<div class="grid grid-cols-3">
			<div v-for="component in components">
				<!-- <Editor :editor="false" class="mx-auto px-4 lg:px-10 border-2 mx-5 lg:mx-20" v-model="component.html"></Editor> -->
			</div>
		</div>
	</AuthenticatedLayout>
</template>
<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { defineComponent, ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import PromptForm from './Chat/PromptForm.vue';
import ChatMessage from './Chat/ChatMessage.vue';
import ChatList from './Chat/ChatList.vue';
import Editor from './Ui/Editor.vue';

export default defineComponent({
	props: {
		user: Object,
		ui: Object,
	},
	components: { AuthenticatedLayout, Head, Link, PromptForm, ChatMessage, ChatList, Editor },
	data() {
		return {
			messages: [],
			placeholder: '',
			components: [],
			prompt: '',
			promptImages:[],
			isUserScrollBottom: true,
			sendPrompt: []
		}
	},
	mounted() {
		window.ui = this;
	},
	methods: {
		changeImages(images) {
			this.images = images
		},
		examplePrompt(example) {
			this.prompt = example.prompt;
		},
		send: async function () {
			let prompt = this.getPrompt

			console.log(prompt, 'PROMPT');
			this.sendPrompt = prompt;
			try {

				const response = await axios.post(route('ui.send', {}), { prompt: prompt, id: this.ui.id });
				this.ui.html = response.data.html
				this.ui.id = response.data.id

				const eventSource = new EventSource(route('ui.stream', { uiId: this.ui.id }), { withCredentials: true });
				// reset the code window
				// this.code = '';

				eventSource.addEventListener('message', (event) => {
					const gpt = JSON.parse(event.data)
					if (gpt.delta.content) {
						this.ui.html = (this.ui.html ?? '') + gpt.delta.content
					}
				})

				eventSource.addEventListener("stop", (event) => {
					eventSource.close();
				});

				eventSource.addEventListener("error", (event) => {
					console.error("EventSource failed:", event);
					eventSource.close();
				});
			} catch (error) {
				console.error('Error with POST request:', error);
			}

		},
	},
	computed: {
		getPrompt() {
			return [
				{
					type: 'text',
					text: this.prompt ?? ""
				},
				...this.promptImages
			]
		}
	}
})
</script>

<style>
.prose {
	--tw-prose-pre-bg: black
}
</style>