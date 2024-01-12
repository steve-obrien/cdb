<template>
	<div class="flex" v-if="message.role == 'user'">
		<img class="h-8 w-8 min-w-8 mr-2 rounded-full" alt="User" loading="lazy" width="24" height="24" src="https://lh3.googleusercontent.com/a/ALm5wu3lSHCzoDzK3aqjxSzMOB6O_gJTDDJTX7zKrJx02BE=s96-c" style="color: transparent;">
		<div>
			<div class="mt-1 font-semibold">{{ message.name ?? 'You' }}</div>
			<div v-html="formatMessage(message.content)" class="prose"></div>

			{{ message }}
		</div>
	</div>

	<div class="flex" v-else-if="message.role == 'assistant'">
		<div class="h-8 w-8 min-w-8 mr-2 rounded-full flex items-center justify-center bg-black text-white">AI</div>
		<div>
			<div class="mt-1 font-semibold">ChatGPT</div>
			<div v-if="message.state == 'loading'">
				<span class="relative flex h-2 w-2 mt-1">
					<span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-black opacity-75"></span>
					<span class="relative inline-flex rounded-full h-2 w-2 bg-gray-800/50"></span>
				</span>
			</div>
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
</template>

<script>
import { Marked } from 'marked';
import { markedHighlight } from "marked-highlight";
import hljs from 'highlight.js';
import javascript from 'highlight.js/lib/languages/javascript';
hljs.registerLanguage('javascript', javascript);
import 'highlight.js/styles/github-dark.css';

const marked = new Marked(
	markedHighlight({
		langPrefix: 'hljs !whitespace-pre language-',
		highlight(code, lang, info) {
			const language = hljs.getLanguage(lang) ? lang : 'plaintext';
			return hljs.highlight(code, { language }).value;
		}
	})
);

export default {
	props: { message: Object },
	methods: {
		formatMessage(message) {
			// Set options for marked
			return marked.parse(message);
		},
	}
	
}

</script>