<template>

	<div class="absolute inset-0 flex flex-col">
		<div class="font-semibold text-xl text-gray-800 leading-tight text-center p-2">
			<a href="https://ai.newicon.net">
				<svg class="inline-flex" width="22" height="25" viewBox="0 0 22 25" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path d="M5.45769 24.2342V9.5455L16.9231 24.2342H20.7335V20.4352L4.7756 0H0V24.2342H5.45769Z" fill="black"/>
					<path d="M16 5.42352L21.4235 5.42352V2.90871e-05L16 2.90871e-05V5.42352Z" fill="black"/>
				</svg>
			</a>
		</div>

		<div class="flex h-full">

			<div class="@container h-full grow flex flex-col bg-white dark:bg-black">
				<div class="grow flex relative">
					<div ref="chatWindow" class="absolute inset-0 overflow-y-scroll" @scroll="handleScroll">
						<div class="px-4 py-8 flex flex-col flex-1 text-base mx-auto gap-5 @md:max-w-3xl @lg:max-w-[40rem] @xl:max-w-[48rem] group final-completion">
							<div v-if="messages.length == 0">
								<div class="flex">
									<div class="h-8 w-8 min-w-8 mr-2 rounded-full flex items-center justify-center bg-black text-white">AI</div>
									<div>
										<div class="mt-1 font-semibold">ChatGPT</div>
										<div class="prose">How can I help you today?</div>
									</div>
								</div>
							</div>
							<div v-else v-for="(message, index) in messages">
								<ChatMessage :message="message"></ChatMessage>
							</div>
							<div class="text-center">
								<code class="text-xs">tokens: {{ totalTokens }} | cost: {{totalCost}}</code>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>

<script lang="ts">
import ChatLayout from '@/Pages/Chat/ChatLayout.vue';
import { Head } from '@inertiajs/vue3';
import { defineComponent, ref, nextTick } from 'vue';
// import 'highlight.js/styles/vs.css';
import { Link, router } from '@inertiajs/vue3';
import PromptForm from './Chat/PromptForm.vue';
import ChatMessage from './Chat/ChatMessage.vue';
import Echo from 'laravel-echo';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ChatList from './Chat/ChatList.vue';

export default defineComponent({
	props: {
		user: Object,
		chats: Array,
		sessions: Array,
		// The ID of the current selected chat session
		sessionId: { required: true, type: String }
	},
	components: { Head, Link, AuthenticatedLayout, PromptForm, ChatMessage, ChatList },
	data() {
		return {
			prompt: '',
			data: [],
			messages: [], // the conversation data
			charsPerToken: 4, // Constant to estimate characters per token
			tokenCostPerThousand: 0.005,
			isUserScrollBottom: true,
			channel: null,
			channelTeam: null,
			users: {}
		}
	},
	unmounted() {
		window.Echo.leave(`chat.${this.sessionId}`)
	},
	mounted() {
		window.chat = this;
		this.messages = this.chats;

		nextTick(() => {
			this.$refs.chatWindow.scrollTo(0, this.$refs.chatWindow.scrollHeight);
		})

		// set up sockets:

	},
	computed: {
		// Calculate the cost for each message and return an array of the costs
		messageCosts() {
			let cumulativeContent = '';
			return this.messages.map((message) => {
				// Add current message content to the cumulative content
				cumulativeContent += message.content;
				let length = cumulativeContent.length
				// Calculate tokens based on the cumulative content length so far
				//return cumulativeContent.length;
				if (message.role == 'assistant') {
					// dont include previous messages in cost
					length = message.content.length
				}
				const tokens = length / this.charsPerToken
				const cost = (tokens / 1000) * this.tokenCostPerThousand
				const mesageTokens = message.content.length / this.charsPerToken
				const messageLength = message.content.length
				return { role: message.role, length, messageLength, tokens, cost, mesageTokens, }
			});
		},
		// Calculate the total cost for the conversation
		total() {
			const totalChars = this.messageCosts.reduce((accumulator, cost) => accumulator + cost.length, 0);
			const tokens = totalChars / this.charsPerToken
			const cost = (tokens / 1000) * this.tokenCostPerThousand
			return { tokens, cost: cost.toFixed(4) }
		},
		totalTokens() {
			return this.messages.reduce((total, message) => {
				if (typeof message.total_tokens === 'number') {
					return total + message.total_tokens;
				}
				return total;
			}, 0);
		},
		totalCost() {
			const cost = (this.totalTokens / 1000) * this.tokenCostPerThousand
			return cost.toFixed(4)
		}
	},
	methods: {
		go(url) {
			router.visit(url)
		},
		addChatChunk(message, chunk) {
			this.addMessage(message, chunk);

			nextTick(() => {
				this.scrollToBottom()
			})
		},
		addMessage(message, chunk) {
			const chatIndex = this.messages.findIndex(chat => chat.id === message.id)
			if (chatIndex === -1) {
				// check id exists - if it does not add it:
				this.messages.push(message)
			} else {
				// console.log('ADD CHUNK',chunk,message )
				// if it does exist update the message
				this.messages[chatIndex].content = (this.messages[chatIndex].content ?? '') + (chunk.delta.content ?? '');
			}
		},
		handleScroll() {
			console.log('USER SCROLL')
			let chatWindow = this.$refs.chatWindow;
			this.isUserScrollBottom = chatWindow.scrollTop + chatWindow.clientHeight >= chatWindow.scrollHeight - 100;
		},
		scrollToBottom() {

			if (this.isUserScrollBottom) {
				// Scroll to bottom
				this.$refs.chatWindow.scrollTo(0, this.$refs.chatWindow.scrollHeight);
			}
		},
		send: async function (payload) {

			const prompt = payload.prompt

			const response = await axios.post(route('api.chatStart'), {
				sessionId: this.sessionId,
				prompt: prompt
			})
			if (response.status != 200) alert('error!');

			// could mis match?
			console.log(response.data, 'response.data');
			if (response.data.sessionId !== this.sessionId) {
				alert('session ids do not match!?')
			}

			const chat = response.data.chat

			nextTick(() => {
				this.$refs.chatWindow.scrollTo(0, this.$refs.chatWindow.scrollHeight);
			})
			this.prompt = ''

			this.streamResponse(this.sessionId)
		},
		streamResponse(sessionId) {
			try {

				const eventSource = new EventSource(route('api.chatStream', sessionId), { withCredentials: true });

				eventSource.addEventListener("stop", (event) => {
					eventSource.close();
				});

				eventSource.addEventListener("error", (err) => {
					console.error("EventSource failed:", err);
				});

			} catch (error) {
				console.error('There was a problem with the streaming operation:', error);
			}
		},

	}
})
</script>

<style>
.prose {
	--tw-prose-pre-bg: black
}
</style>