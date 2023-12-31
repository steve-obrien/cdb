<template>
	<Head title="Dashboard" />

	<ChatLayout :sessions="sessions">
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
				</div>
			</div>
		</div>
		<div class="px-4">
			<PromptForm @send="send" v-model:prompt="prompt"></PromptForm>
			<div class="text-center">
				<code class="text-xs">tokens: {{ total.tokens }} / cost: {{ total.cost }} - scroll: {{isScrollBottom}}</code>
			</div>
		</div>

	</ChatLayout>
</template>

<script lang="ts">
import ChatLayout from '@/Layouts/ChatLayout.vue';
import { Head } from '@inertiajs/vue3';
import { defineComponent, ref, nextTick } from 'vue';
// import 'highlight.js/styles/vs.css';
import { Link } from '@inertiajs/vue3';
import PromptForm from './Chat/PromptForm.vue';
import ChatMessage from './Chat/ChatMessage.vue';
import { walkTokens } from 'marked';


export default defineComponent({
	props: {
		user: Object,
		chats: Array,
		sessions: Array,
		// The ID of the current selected chat session
		sessionId: { required: true, type: String }
	},
	components: { Head, Link, ChatLayout, PromptForm, ChatMessage },
	data() {
		return {
			prompt: '',
			data: [],
			messages: [], // the conversation data
			charsPerToken: 4, // Constant to estimate characters per token
			tokenCostPerThousand: 0.01,
			isScrollBottom: true
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
		nextTick(() => {
			this.$refs.chatWindow.scrollTo(0, this.$refs.chatWindow.scrollHeight);
		})
		window.chat = this

		// auto response
	},
	computed: {
		tokens() {
			let tokens = 0
			this.messages.forEach(message => {
				tokens += message.content.length / this.charsPerToken
			})
			return Math.round(tokens);
		},
		cost() {
			let cost = (this.tokens / 1000) * 0.01;
			return cost.toFixed(4)
		},
		linkChat() {
			return route('chat')
		},
		// Calculate the cost for each message and return an array of the costs
		messageCosts() {
			let cumulativeContent = '';
			return this.messages.map((message) => {
				// Add current message content to the cumulative content
				cumulativeContent += message.content;
				let length =  cumulativeContent.length
				// Calculate tokens based on the cumulative content length so far
				//return cumulativeContent.length;
				if (message.role == 'assistant') {
					// dont include previous messages in cost
					length = message.content.length
				}
				const tokens = length  / this.charsPerToken
				const cost = (tokens / 1000) * this.tokenCostPerThousand
				const mesageTokens = message.content.length / this.charsPerToken
				const messageLength = message.content.length
				return { role: message.role, length,messageLength, tokens, cost, mesageTokens,  }
			});
		},
		// Calculate the total cost for the conversation

		total() {
			const totalChars = this.messageCosts.reduce((accumulator, cost) => accumulator + cost.length, 0);
			const tokens = totalChars / this.charsPerToken
			const cost =  (tokens / 1000) * this.tokenCostPerThousand
			return { tokens, cost: cost.toFixed(4) }
		}
	},
	methods: {
		linkSession(id) {
			return route('chat.session', id)
		},

		handleScroll() {
			console.log('USER SCROLL')
			let chatWindow = this.$refs.chatWindow;
			this.isScrollBottom = chatWindow.scrollTop + chatWindow.clientHeight >= chatWindow.scrollHeight - 40;
		},

		scrollToBottom() {
			let chatWindow = this.$refs.chatWindow;
			const isUserAtBottom = chatWindow.scrollTop + chatWindow.clientHeight >= chatWindow.scrollHeight - 40;

			if (isUserAtBottom) {
				// Scroll to bottom
				chatWindow.scrollTo(0, chatWindow.scrollHeight);
			} else {
				// Otherwise, do nothing or handle the situation when the user is not at the bottom
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
			this.messages.push(chat)
			nextTick(() => {
				this.$refs.chatWindow.scrollTo(0, this.$refs.chatWindow.scrollHeight);
			})
			this.prompt = ''

			this.streamResponse(this.sessionId)
		},
		streamResponse(sessionId) {
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
				});

				eventSource.addEventListener("error", (err) => {
					alert('error - check console!')
					console.error("EventSource failed:", err);
				});

			} catch (error) {
				console.error('There was a problem with the streaming operation:', error);
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