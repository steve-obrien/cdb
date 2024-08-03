<template>
	<div v-if="message.role == 'user'">
		<!-- me -->
		<div v-if="$page.props.auth.user.id == message.user_id" class="grid grid-cols-chat p-2">
			<div class="col-start-3 col-span-2 text-right">
				<div class="bg-gray-100 dark:bg-gray-900 rounded-l-xl p-2 inline-block text-left break-words max-w-full">
					<div class="font-semibold text-black dark:text-white text-right">Me</div>
					<div v-if="isArray" class=" text-left">
						<!-- We are an array of conent messages -->
						<div v-for="msg in messages" class="prose dark:prose-invert">
							<div v-if="msg.type == 'text'" v-html="formatMessage(msg.text)"></div>
							<div v-if="msg.type == 'image_url'">
								<img :src="msg.image_url.url" />
							</div>
						</div>
					</div>
					<div v-else v-html="formatMessage(messages)" class="prose dark:prose-invert"></div>
				</div>
			</div>
			<div class="w-full bg-gray-100 dark:bg-gray-900 rounded-r-xl p-2">
				<img class="w-full col-span-1 col-start-5  rounded-full" alt="User" loading="lazy" width="24" height="24"
				:src="message.user.avatar_url" style="color: transparent;">
			</div>
		</div>
		<!-- you -->
		<div class="flex p-2 rounded-md bg-gray-100 dark:bg-gray-800" v-else>
			<img class="h-8 w-8 min-w-8 mr-2 rounded-full" alt="User" loading="lazy" width="24" height="24"
			:src="message.user.avatar_url" style="color: transparent;">
			<div>
				<div class="mt-1 font-semibold text-black dark:text-white">{{ message.name }}</div>
				<div v-html="formatMessage(message.content)" class="prose dark:prose-invert"></div>
			</div>
		</div>
	</div>

	<div v-else-if="message.role == 'assistant'" class="flex p-2 rounded-md  ">
		<div class="h-8 w-8 min-w-8 mr-2 rounded-full flex items-center justify-center bg-black text-white ">AI</div>
		<div>
			<div class="mt-1 font-semibold text-black dark:text-white ">ChatGPT</div>
			<div v-if="message.state == 'loading'">
				<span class="relative flex h-2 w-2 mt-1">
					<span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-black dark:bg-white opacity-75"></span>
					<span class="relative inline-flex rounded-full h-2 w-2 bg-gray-800/50"></span>
				</span>
			</div>
			<div v-else-if="message.state == 'streaming'" class="prose dark:prose-invert" v-html="formatMessage(message.content)"></div>
			<div v-else class="prose dark:prose-invert" v-html="formatMessage(message.content)"></div>
			<div v-if="message.tool_calls !== null">{{ message.tool_calls }}</div>
		</div>
	</div>

	<div v-else class="flex">
		<div class="h-8 w-8 min-w-8 mr-2 rounded-full flex items-center justify-center bg-black text-white">AI</div>
		<div>
			<div class="mt-1 font-semibold text-black dark:text-white">ChatGPT - {{ message.role }}</div>
			<div class="prose dark:prose-invert">{{ message.content }}</div>
		</div>
	</div>
</template>

<script>
import { Marked } from 'marked';
import { markedHighlight } from "marked-highlight";
import hljs from 'highlight.js';
import javascript from 'highlight.js/lib/languages/javascript';
// import vue from 'highlight.js/lib/languages/vue';
import python from 'highlight.js/lib/languages/python';
import php from 'highlight.js/lib/languages/php';
import sql from 'highlight.js/lib/languages/sql';

/*
Language: Vue.js
Requires: xml.js, javascript.js, typescript.js, css.js, stylus.js, scss.js
Author: Sara Lissette Luis Ibáñez <lissette.ibnz@gmail.com>
Description: Single-File Components of Vue.js Framework
*/
function hljsDefineVue(hljs) {
	return {
		subLanguage: "xml",
		contains: [
			hljs.COMMENT("<!--", "-->", {
				relevance: 10,
			}),
			{
				begin: /^(\s*)(<script>)/gm,
				end: /^(\s*)(<\/script>)/gm,
				subLanguage: "javascript",
				excludeBegin: true,
				excludeEnd: true,
			},
			{
				begin: /^(?:\s*)(?:<script\s+lang=(["'])ts\1>)/gm,
				end: /^(\s*)(<\/script>)/gm,
				subLanguage: "typescript",
				excludeBegin: true,
				excludeEnd: true,
			},
			{
				begin: /^(\s*)(<style(\s+scoped)?>)/gm,
				end: /^(\s*)(<\/style>)/gm,
				subLanguage: "css",
				excludeBegin: true,
				excludeEnd: true,
			},
			{
				begin: /^(?:\s*)(?:<style(?:\s+scoped)?\s+lang=(["'])(?:s[ca]ss)\1(?:\s+scoped)?>)/gm,
				end: /^(\s*)(<\/style>)/gm,
				subLanguage: "scss",
				excludeBegin: true,
				excludeEnd: true,
			},
			{
				begin: /^(?:\s*)(?:<style(?:\s+scoped)?\s+lang=(["'])stylus\1(?:\s+scoped)?>)/gm,
				end: /^(\s*)(<\/style>)/gm,
				subLanguage: "stylus",
				excludeBegin: true,
				excludeEnd: true,
			},
		],
	};
}


hljs.registerLanguage("vue", hljsDefineVue);


hljs.registerLanguage('javascript', javascript);
// hljs.registerLanguage('vue', vue);
hljs.registerLanguage('python', python);
hljs.registerLanguage('php', php);
hljs.registerLanguage('sql', sql);

import 'highlight.js/styles/github-dark.css';
// import 'highlight.js/styles/github.css';

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
			if (message == undefined) return '';
			return marked.parse(message);
		},
	},
	computed: {
		isArray() {
			if (typeof this.message.content === 'string') {
				try {
					const parsed = JSON.parse(this.message.content);
					if (Array.isArray(parsed)) {
						return true;
					}
				} catch (e) {
					// If parsing throws an error, it's not a JSON array
					return false;
				}
			}
			return false;
		},

		messages() {
			return JSON.parse(this.message.content);
		}
	}

}

</script>

<style>
.grid-cols-chat {
	grid-template-columns: 48px 1fr 10fr 1fr 48px;
}
</style>