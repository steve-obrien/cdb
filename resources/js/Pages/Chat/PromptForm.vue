<template>
	<form @submit.prevent class="stretch mx-2 flex flex-row gap-3 last:mb-2 md:mx-4 md:last:mb-6 lg:mx-auto lg:max-w-2xl xl:max-w-3xl">
		<div class="relative flex h-full flex-1 items-stretch md:flex-col">
			<div class="flex w-full items-center">
				<div class="overflow-hidden [&:has(textarea:focus)]:border-token-border-xheavy [&:has(textarea:focus)]:shadow-[0_2px_6px_rgba(0,0,0,.05)] flex flex-row w-full dark:border-token-border-heavy flex-grow relative border border-token-border-heavy dark:text-white rounded-2xl bg-white dark:bg-gray-800 shadow-[0_0_0_2px_rgba(255,255,255,0.95)] dark:shadow-[0_0_0_2px_rgba(52,53,65,0.95)]">
					<div class="relative flex">
						<button @click="triggerFileInput" class="self-end mb-3 pl-3 btn  p-0 text-black dark:text-white" aria-label="Attach files">
							<div class="flex w-full gap-2 items-center justify-center">
								<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
									<path fill-rule="evenodd" clip-rule="evenodd" d="M9 7C9 4.23858 11.2386 2 14 2C16.7614 2 19 4.23858 19 7V15C19 18.866 15.866 22 12 22C8.13401 22 5 18.866 5 15V9C5 8.44772 5.44772 8 6 8C6.55228 8 7 8.44772 7 9V15C7 17.7614 9.23858 20 12 20C14.7614 20 17 17.7614 17 15V7C17 5.34315 15.6569 4 14 4C12.3431 4 11 5.34315 11 7V15C11 15.5523 11.4477 16 12 16C12.5523 16 13 15.5523 13 15V9C13 8.44772 13.4477 8 14 8C14.5523 8 15 8.44772 15 9V15C15 16.6569 13.6569 18 12 18C10.3431 18 9 16.6569 9 15V7Z" fill="currentColor"></path>
								</svg>
							</div>
						</button>
						<input ref="fileInput" @change="handleFileChange" multiple="" type="file" tabindex="-1" class="hidden" style="display: none;">
					</div>
					<div class="w-full items-center flex-col">
						<div v-if="images.length" class="flex gap-2">
							<div v-for="(image, key) in images" class="relative w-20 group">
								<img class="w-full m-2 rounded-md" :src="image.image_url.url" />
								<button @click="removeImage(key)" class="z-10 absolute -top-1 -right-3 hidden group-hover:block bg-white/60 hover:bg-white rounded-full px-2">x</button>
							</div>
						</div>
						<div class="flex items-center">
							<textarea @keyup="autoExpand" @keydown="handleKeyPress" @blur="onBlur" :value="prompt" @input="$emit('update:prompt', $event.target.value)" ref="prompt" id="prompt-textarea" tabindex="0" :rows="rows" :placeholder="placeholder" class="m-0 w-full resize-none border-0 bg-transparent py-3 pr-10 focus:ring-0 focus-visible:ring-0 dark:bg-transparent md:pr-12 placeholder-black/50 dark:placeholder-white/50 pl-4" style="max-height: 250px; height: 52px; "></textarea>
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
</template>

<script>
import { defineComponent } from 'vue'

export default defineComponent({
	emits: ['send', 'update:prompt'],
	props: {
		prompt: String,
		rows: { type: String, default: 1 },
		placeholder: { type: String, default: 'Message ChatGPT…' },
		images: { type: Array, default: [] }
	},
	mounted() {
		this.$refs.prompt.focus()
	},
	watch: {
		placeholder() {
			this.autoExpand()
		},
		prompt() {
			this.autoExpand()
		}
	},
	methods: {
		getPrompt(text) {
			prompt = this.prompt;
			prompt[0] = {type: 'text', text: text};
			return prompt;
		},
		triggerFileInput() {
			this.$refs.fileInput.click();
		},
		handleFileChange(event) {
			const files = event.target.files;
			// Handle the selected files
			console.log(files);
			const file = files[0];
			for (const file of files) {
				this.addFileToUpload(file)
			}

		},
		addFileToUpload(file) {
			if (file && file.type.startsWith('image/')) {
				const reader = new FileReader();
				reader.onload = (e) => {
					this.images.push(
						{ type: "image_url", image_url: { "url": e.target.result } }
					)
				};
				reader.readAsDataURL(file);
			}
		},
		removeImage(key) {
			this.images.splice(key, 1)
		},
		/**
		 * A content message can contain an array:
		 * {
			"type": "image_url",
			"image_url": {
			  "url": "https://upload.wikimedia.org/wikipedia/commons/thumb/d/dd/Gfp-wisconsin-madison-the-nature-boardwalk.jpg/2560px-Gfp-wisconsin-madison-the-nature-boardwalk.jpg"
			}
		  }
		 */
		image() {

			let images = [
				'https://upload.wikimedia.org/wikipedia/commons/thumb/d/dd/Gfp-wisconsin-madison-the-nature-boardwalk.jpg/2560px-Gfp-wisconsin-madison-the-nature-boardwalk.jpg',
				'https://newicon.net/firefly/file/get?id=sGmFa7BbddCfNfaizkapBg',
				'https://newicon.net/firefly/file/get?id=H5enoiNCdEG6bQb4WpRdtg',
				'https://newicon.net/firefly/file/get?id=kX5VGefDe__afQ18FJxXF0',
			];

			let imageUrl = images[Math.floor(Math.random() * images.length)];

			this.convertImageToBase64(imageUrl, (base64) => {

				this.images.push(
					{
						type: "image_url",
						image_url: {
							"url": base64
						}
					}
				)
			})

		},
		convertImageToBase64(imageUrl, callback) {
			fetch(imageUrl)
				.then(response => response.blob())
				.then(blob => {
					const reader = new FileReader();
					reader.onloadend = () => {
						callback(reader.result);
					};
					reader.readAsDataURL(blob);
				})
				.catch(error => console.error('Error converting image to Base64:', error));
		},
		send() {

			if (this.images.length) {
				this.$emit('send', {
					prompt: [
						{
							type: 'text',
							text: this.prompt,
						},
						...this.images
					]
				})
			} else {
				this.$emit('send', {
					prompt: this.prompt
				})
			}

			setTimeout(() => {
				this.autoExpand();
			}, 10)
		},
		autoExpand() {
			// Reset field height to allow shrinking if necessary
			this.$refs.prompt.style.height = 'auto';
			// Calculate the new height and set it (subtracting padding/border)
			let newHeight = this.$refs.prompt.scrollHeight + 'px'; // For scrollbar size
			this.$refs.prompt.style.height = newHeight;
		},
		handleKeyPress(event) {
			if ((event.ctrlKey || event.metaKey) && event.key === 'Enter') {
				this.send();
			}
		},
		onBlur() {
			// if only contains whitespace then shrink the box
			if (/^\s*$/.test(this.prompt)) {
				this.$refs.prompt.style.height = 'auto';
			}
		}
	}
});
</script>