<template>

	<Head title="Chat" />

	<AuthenticatedLayout>

		<template #header>
			<h2 class="font-semibold text-xl text-gray-800 leading-tight">Chat</h2>
			<select class="ml-3 block lg:hidden" @change="$router.visit($route('chat.session', $event.target.value))">
				<option v-for="session in sessions" :value="session.id" :key="session.id">{{ session.prompt.slice(0, 25) }}</option>
			</select>
		</template>

		<div class="flex h-full">

			<ChatList :sessions="sessions"></ChatList>

			<div class="@container h-full grow flex flex-col bg-white dark:bg-black">
				<div class="grow flex relative">
					<div ref="chatWindow" class="absolute inset-0 overflow-y-scroll" @scroll="handleScroll">
						<div class="px-4 py-8 flex flex-col flex-1 text-base mx-auto gap-5 @md:max-w-3xl @lg:max-w-[40rem] @xl:max-w-[48rem] group final-completion">
							<div v-if="messages.length == 0">
								<div class="flex">
									<div class="h-8 w-8 min-w-8 mr-2 rounded-full flex items-center justify-center bg-black text-white">AI</div>
									<div>
										<div class="mt-1 font-semibold text-black dark:text-white">ChatGPT</div>
										<div class="prose dark:prose-invert">How can I help you today?</div>
									</div>
								</div>
							</div>
							<div v-else v-for="message in messages">
								<ChatMessage :message="message"></ChatMessage>
							</div>
						</div>
					</div>
				</div>
				<div class="px-4">
					<PromptForm @send="send" v-model:prompt="prompt"></PromptForm>
				</div>
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

export default defineComponent({
	props: {
		user: Object,
		chats: Array,
		sessions: Array,
	},
	components: { AuthenticatedLayout, Head, Link, PromptForm, ChatMessage, ChatList },
	data() {
		return {
			prompt: '',
			sessionId: undefined,
			data: [],
			// object containing from: 'me'|'ai', "content":"..."
			messages: [
			],
			charsPerToken: 4, // Constant to estimate characters per token
			tokenCostPerThousand: 0.01,
			isUserScrollBottom: true
		}
	},
	mounted() {
		window.chat = this;
		this.chats.forEach((chat) => {
			this.messages.push({
				role: chat.role,
				content: chat.content,
				state: 'finished'
			})
		})
		// fetch sessions.
		window.Echo.join('team')
			.listen('.ChatSessionCreated', (e) => {
				console.log('EVENT', e)
			})
			.listen('.ChatSessionUpdated', (e) => {
				console.log('EVENT', e)
			})
			.listen('ChatSessionCreated', (e) => {
				console.log('ChatSession created:', e.chatSession);
			});


		// set up sockets:

	},
	methods: {
		handleScroll() {
			let chatWindow = this.$refs.chatWindow;
			this.isUserScrollBottom = chatWindow.scrollTop + chatWindow.clientHeight >= chatWindow.scrollHeight - 40;
		},
		scrollToBottom() {
			let chatWindow = this.$refs.chatWindow;

			if (isUserAtBottom) {
				// Scroll to bottom
				chatWindow.scrollTo(0, chatWindow.scrollHeight);
			}
		},
		send: async function (payload) {

			const prompt = payload.prompt

			const response = await axios.post(route('api.chatStart'), {
				sessionId: this.sessionId,
				prompt: prompt
			})
			if (response.status != 200) alert('error!');

			const sessionId = response.data.sessionId

			const chat = response.data.chat
			this.messages.push(chat);

			this.prompt = ''

			this.streamResponse(sessionId, () => {
				// redirect to the new conversation stream
				window.location.href = route('chat.session', sessionId)
			})
		},
		streamResponse(sessionId, onFinish) {
			try {

				let message = { role: 'assistant', content: '', state: 'loading' }
				this.messages.push(message)
				let index = this.messages.indexOf(message);

				const eventSource = new EventSource(route('api.chatStream', sessionId), { withCredentials: true });

				eventSource.addEventListener('message', (event) => {
					this.messages[index].state = 'streaming'
					const gpt = JSON.parse(event.data)
					if (gpt.delta.content) {
						this.messages[index].content = message.content + gpt.delta.content
						this.scrollToBottom();
					}
				})

				eventSource.addEventListener("stop", (event) => {
					eventSource.close();
					this.messages[index].state = 'finished'
					onFinish();
				});

				eventSource.addEventListener("error", (event) => {
					// alert('error - check console!')
					console.error("EventSource failed:", event);
					eventSource.close();
				});

			} catch (error) {
				console.error('There was a problem with the fetch operation:', error);
			}
		}
	}
})
</script>

<style>
.prose {
	--tw-prose-pre-bg: black
}
</style>