<template>
	<div class="bg-white">
		<!-- <button @click="debug = !debug">debug</button> -->
		<div class="flex flex-row h-full">
			<div ref="codeWindow" v-show="editor" class="w-full flex relative min-h-[200px]">
				<div ref="dragger" @mousedown="rowResizeStart" class="h-1 cursor-row-resize bg-black absolute bottom-0 w-full z-40"></div>
				<div ref="draggerCol" @mousedown="colResizeStart" class="w-2 cursor-col-resize bg-black absolute bottom-0 right-0 h-full z-40"></div>
				<MonacoEditor :width="width" ref="editor" :options="editorOptions" v-model:value="value" ></MonacoEditor>
				<!-- <textarea class="w-full h-full" :value="modelValue" @input="$emit('update:modelValue', $event.target.value)"></textarea> -->
			</div>
			<!-- <div class="w-[4px]"></div> -->
			<div class="w-full bg-white relative">
				<div v-if="dragging" class="absolute bg-black/10 inset-0"></div>
				<iframe style="border:none;background:white;height:100vh;width:100%;" ref="iframe" :srcdoc="iframeContent" class="w-full h-full border-none" sandbox="allow-popups-to-escape-sandbox allow-scripts allow-popups allow-forms allow-pointer-lock allow-top-navigation allow-modals"></iframe>
			</div>
		</div>
	</div>
</template>

<script>
import MonacoEditor from 'monaco-editor-vue3';
import { defineComponent, ref } from 'vue';

export default defineComponent({
	components: { MonacoEditor },
	emits: ['update:modelValue'],
	props: {
		editor: {
			type: Boolean,
			default:true
		},
		modelValue: {
			type: String,
			required: false
		},
		editorOptions: {
			type: Object,
			default() {
				return {
					lineHeight: 24,
					tabSize: 4,
					minimap: {
						enabled: false,
					},
					automaticLayout: true,
					wordWrap: "off"
				}
			}
		}
	},
	data() {
		return {
			test:'',
			debug: false,
			css: '',
			dragging: false,
			width: '100%'
		}
	},
	mounted() {
		this.$refs.iframe.onload = () => {
			this.updateIframeContent(); // Update initial content
		};
	},
	watch: {
		debug: function (newVal) {
			this.css = this.debug ? `
				div { outline: 1px dashed rgba(200, 170, 200, 0.7); outline-offset: -1px; }
				` : ''
		},
		modelValue: function (newVal) {
			this.updateIframeContent()
		},
		css: function (newVal) {
			this.updateIframeContent()
		}
	},
	methods: {
		rowResizeStart() {
			const bounds = this.$refs.codeWindow.getBoundingClientRect();
			const codeWindow = this.$refs.codeWindow;
			console.log(bounds);
			this.dragging = true
			const move =  (e) => {
				console.log(e)
				this.$refs.dragger.style.position = 'absolute';
				this.$refs.dragger.style.top = e.pageY - bounds.top  +'px'
				codeWindow.style.height = e.pageY - bounds.top + 'px'
			}
			window.document.addEventListener('mousemove', move)
			window.document.addEventListener('mouseup', () => {
				this.dragging = false;
				window.document.removeEventListener('mousemove', move)
			})
		},
		colResizeStart() {
			const bounds = this.$refs.codeWindow.getBoundingClientRect();
			console.log(bounds);
			this.dragging = true
			const move =  (e) => {
				console.log(e)
				//this.$refs.dragger.style.top = e.pageX - bounds.x  +'px'
				this.$refs.codeWindow.style.width = e.pageX - bounds.x + 'px'
				this.$refs.editor.style.width = e.pageX - bounds.x + 'px'
				this.width = e.pageX - bounds.x + 'px'
			}
			window.document.addEventListener('mousemove', move)
			window.document.addEventListener('mouseup', () => {
				this.dragging = false;
				window.document.removeEventListener('mousemove', move)
			})
		},
		updateIframeContent() {
			console.log('here');
			this.$refs.iframe.contentWindow.postMessage({
				type: 'updateContent',
				html: this.modelValue,
				css: this.css
			}, '*');
		},
		send: async function (payload) {

			const prompt = payload.prompt

			const response = await axios.post(route('ui.send'), {
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
					console.error("EventSource failed:", event.data);
					eventSource.close();
				});

			} catch (error) {
				console.error('There was a problem with the fetch operation:', error);
			}
		}
	},
	computed: {
		value: {
			get() {
				return this.modelValue
			},
			set(value) {
				this.$emit('update:modelValue', value)
			}
		},
		iframeContent() {
			const $script = '</scr' + 'ipt>';
			return `<!DOCTYPE html>
			<html>
			<head>
				<meta charset="utf-8">
				<meta name="viewport" content="width=device-width, initial-scale=1">
				<script src="https://cdn.tailwindcss.com">${$script}
				<style id="iframe-style"></style>
				<script>
					document.addEventListener('DOMContentLoaded', () => {
						let hasHtml = false;
						let hasCss = true;
						let visible = false;

						window.addEventListener('message', (e) => {
							console.log('message received', e);
							if (e.data.clear !== undefined) {
								clearContent();
								return;
							}
							setCss(e.data.css);
							e.data.html && setHtml(e.data.html);
							checkVisibility();
						});

						function clearContent() {
							setHtml(); setCss(); checkVisibility();
						}

						function setHtml(html = '') {
							document.body.innerHTML = html;
							hasHtml = html !== '';
						}

						function setCss(css = '') {
							const styleElement = document.getElementById('iframe-style');
							styleElement.innerHTML = css;
							hasCss = css !== '';
						}

						function checkVisibility() {
							document.body.style.display = (hasHtml) ? '' : 'none';
							visible = hasHtml && hasCss;
						}

						// Handle link clicks to open in a new tab
						document.body.addEventListener('click', (event) => {
							if (event.which !== 1 || event.metaKey || event.ctrlKey || event.shiftKey || event.defaultPrevented) return;
							let el = event.target;
							while (el && el.nodeName !== 'A') el = el.parentNode;
							if (!el || el.nodeName !== 'A' || el.hasAttribute('download') || el.getAttribute('rel') === 'external' || el.target) return;
							event.preventDefault();
							window.open(el.href, '_blank');
						});
					});
				${$script}
			</head>
			<body style="display:none;"></body>
			</html>
			`;
		},
	},
});


</script>