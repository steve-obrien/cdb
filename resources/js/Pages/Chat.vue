<template>
	<Head title="Dashboard" />

	<AuthenticatedLayout>
		<template #header>
			<h2 class="font-semibold text-xl text-gray-800 leading-tight">Chat</h2>
		</template>

		<!-- <div class="absolute z-10 left-[4rem] w-[300px] bg-gray-200 text-xs overflow-scroll">
			<pre class="overflow-scroll">{{ messages }}</pre>
		</div> -->

		<div class="flex h-full">
			<div>
				<button>Create new</button>
			</div>
			<div class="h-full grow flex flex-col px-4 py-10 sm:px-6 lg:px-8 lg:py-6 bg-gray-100">
				<div class="grow flex relative">
					<div class="absolute inset-0 overflow-y-scroll">
						<div class="pb-10 flex flex-col flex-1 text-base mx-auto gap-5 md:px-5 lg:px-1 xl:px-5 md:max-w-3xl lg:max-w-[40rem] xl:max-w-[48rem] group final-completion">
							<div v-if="messages.length == 0">
								<div class="flex">
									<div class="h-8 w-8 min-w-8 mr-2 rounded-full flex items-center justify-center bg-black text-white">AI</div>
									<div>
										<div class="mt-1 font-semibold">ChatGPT</div>
										<div class="prose">How can I help you today?</div>
									</div>
								</div>
							</div>
							<div v-else v-for="message in messages">
								<div class="flex" v-if="message.role == 'user'">
									<img class="h-8 w-8 min-w-8 mr-2 rounded-full" alt="User" loading="lazy" width="24" height="24" src="https://lh3.googleusercontent.com/a/ALm5wu3lSHCzoDzK3aqjxSzMOB6O_gJTDDJTX7zKrJx02BE=s96-c" style="color: transparent;">
									<div>
										<div class="mt-1 font-semibold">You</div>
										<!-- <div class="prose">{{ message.content }}</div> -->
										<div v-html="formatMessage(message.content)" class="prose"></div>
									</div>
								</div>

								<div class="flex" v-else-if="message.role == 'assistant'">
									<div class="h-8 w-8 min-w-8 mr-2 rounded-full flex items-center justify-center bg-black text-white">AI</div>
									<div>
										<div class="mt-1 font-semibold">ChatGPT</div>
										<div v-if="message.state == 'loading'">...</div>
										<div v-else-if="message.state == 'streaming'" class="prose" v-html="formatMessage(message.content)"></div>
										<div v-else v-html="formatMessage(message.content)" class="prose"></div>
									</div>
								</div>

								<div class="flex" v-else>
									<div class="h-8 w-8 min-w-8 mr-2 rounded-full flex items-center justify-center bg-black text-white">AI</div>
									<div>
										<div class="mt-1 font-semibold">ChatGPT - {{ message.role }}</div>
										<div>{{ message.content }}</div>
									</div>
								</div>

							</div>

						</div>
					</div>
				</div>
				<div class="">
					<form @submit.prevent class="stretch mx-2 flex flex-row gap-3 last:mb-2 md:mx-4 md:last:mb-6 lg:mx-auto lg:max-w-2xl xl:max-w-3xl">
						<div class="relative flex h-full flex-1 items-stretch md:flex-col">
							<div class="flex w-full items-center">
								<div class="overflow-hidden [&:has(textarea:focus)]:border-token-border-xheavy [&:has(textarea:focus)]:shadow-[0_2px_6px_rgba(0,0,0,.05)] flex flex-col w-full dark:border-token-border-heavy flex-grow relative border border-token-border-heavy dark:text-white rounded-2xl bg-white dark:bg-gray-800 shadow-[0_0_0_2px_rgba(255,255,255,0.95)] dark:shadow-[0_0_0_2px_rgba(52,53,65,0.95)]">
									<textarea v-model="prompt" id="prompt-textarea" tabindex="0" rows="1" placeholder="Message ChatGPT…" class="m-0 w-full resize-none border-0 bg-transparent py-[10px] pr-10 focus:ring-0 focus-visible:ring-0 dark:bg-transparent md:py-3.5 md:pr-12 placeholder-black/50 dark:placeholder-white/50 pl-10 md:pl-[55px]" style="max-height: 200px; height: 52px; ">
									</textarea>
									<div class="absolute bottom-2 md:bottom-3 left-2 md:left-4">
										<div class="flex">
											<button class="btn relative p-0 text-black dark:text-white" aria-label="Attach files">
												<div class="flex w-full gap-2 items-center justify-center">
													<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
														<path fill-rule="evenodd" clip-rule="evenodd" d="M9 7C9 4.23858 11.2386 2 14 2C16.7614 2 19 4.23858 19 7V15C19 18.866 15.866 22 12 22C8.13401 22 5 18.866 5 15V9C5 8.44772 5.44772 8 6 8C6.55228 8 7 8.44772 7 9V15C7 17.7614 9.23858 20 12 20C14.7614 20 17 17.7614 17 15V7C17 5.34315 15.6569 4 14 4C12.3431 4 11 5.34315 11 7V15C11 15.5523 11.4477 16 12 16C12.5523 16 13 15.5523 13 15V9C13 8.44772 13.4477 8 14 8C14.5523 8 15 8.44772 15 9V15C15 16.6569 13.6569 18 12 18C10.3431 18 9 16.6569 9 15V7Z" fill="currentColor"></path>
													</svg>
												</div>
											</button>
											<input multiple="" type="file" tabindex="-1" class="hidden" style="display: none;">
										</div>
									</div>
									<button @click="send" class="absolute bg-black hover:bg-gray-600 dark:bg-white md:bottom-3 md:right-3 dark:hover:bg-gray-900 dark:disabled:hover:bg-transparent right-2 dark:disabled:bg-white disabled:opacity-10 disabled:text-gray-400 text-white p-0.5 border border-black rounded-lg dark:border-white  bottom-1.5 transition-colors" data-testid="send-button">
										<span class="" data-state="closed">
											<svg width="24" height="24" viewBox="0 0 24 24" fill="none" class="text-white dark:text-black">
												<path d="M7 11L12 6L17 11M12 18V7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
											</svg>
										</span>
									</button>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>

	</AuthenticatedLayout>
</template>
<script >
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { defineComponent, ref } from 'vue';
import { Marked } from 'marked';
import { markedHighlight } from "marked-highlight";
import hljs from 'highlight.js';
import javascript from 'highlight.js/lib/languages/javascript';
hljs.registerLanguage('javascript', javascript);
// import 'highlight.js/styles/default.css';
// import 'highlight.js/styles/ir-black.css';
import 'highlight.js/styles/github-dark.css';
// import 'highlight.js/styles/vs.css';

const marked = new Marked(
	markedHighlight({
		langPrefix: 'hljs !whitespace-pre language-',
		highlight(code, lang, info) {
			const language = hljs.getLanguage(lang) ? lang : 'plaintext';
			return hljs.highlight(code, { language }).value;
		}
	})
);


export default defineComponent({
	props: {
		user: Object,
		chats: Array
	},
	components: { AuthenticatedLayout, Head },
	data() {
		return {
			prompt: '',
			data: [],
			// object containing from: 'me'|'ai', "content":"..."
			messages: [
			],
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
	},
	methods: {
		formatMessage(message) {
			// Set options for marked
			return marked.parse(message);
		},
		send: async function () {

			const prompt = this.prompt;
			this.prompt = ''

			// perhaps expand to have from type user / app?
			// then AI responses.
			// or label the response as all responses will be from the AI

			this.messages.push({
				role: 'user', // should be the user id
				content: prompt
			});
			try {

				let message = { role: 'assistant', content: '', state: 'loading' }
				this.messages.push(message)
				let index = this.messages.indexOf(message);

				const eventSource = new EventSource(route('api.chat') + '?prompt=' + prompt, { withCredentials: true });

				eventSource.addEventListener('message', (event) => {
					this.messages[index].state = 'streaming'
					const gpt = JSON.parse(event.data)
					if (gpt.delta.content)
						this.messages[index].content = message.content + gpt.delta.content
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