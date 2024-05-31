<template>

	<Head title="Chat" />

	<AuthenticatedLayout class="bg-gray-50 dark:bg-black">
		
		<template #header>
			<h2 class="font-semibold text-xl text-gray-800 leading-tight">Ui</h2>
		</template>

		<div class="flex flex-col h-full">
			<img class="absolute z-10 inset-0 object-cover pointer-events-none" src="img/bg.svg" />
			<div class="@container z-20 items-center justify-center grow flex flex-col">
				<div class="z-20 my-32">
					<PromptForm class="" @send="send" v-model:prompt="prompt" rows="2" :placeholder="placeholder"></PromptForm>
					<div class="flex space-x-2 mt-2">
						<button :class="(prompt==example.prompt)?'bg-gray-100':'bg-white'" v-for="example in promptButtons" @click="examplePrompt(example)" class="border hover:border-gray-800 rounded-full px-2 ">
							<div>{{example.label}}</div>
						</button>
					</div>
				</div>
			</div>
			<div class="z-10">
				<Editor class="mx-auto px-4 lg:px-10 border-2 mx-5 lg:mx-20" v-model="code"></Editor>
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
		chats: Array,
		sessions: Array,
	},
	components: { AuthenticatedLayout, Head, Link, PromptForm, ChatMessage, ChatList, Editor },
	data() {
		return {
			code:'',
			messages: [],
			placeholder: '',
			examples: [
				{"prompt": 'A kanban board', label: 'Kanban'},
				{"prompt": 'A landing page hero section with a heading, leading text and an opt-in form.', label: 'Landing Page'},
				{"prompt": 'A contact form with first name, last name, email, and message fields. Put the form in a card with a submit button.', label: 'Contact Form'},
				{"prompt": 'A dashboard with a sidebar nav and a table', label: 'Dashboard'},
				{"prompt": 'An ecommerce dashboard with a sidebar navigation and a table of recent orders.', label: 'Ecommerce'},
				{"prompt": "A user profile card with a profile picture, name, and bio.", "label": "Profile Card"},
				{"prompt": "A blog post layout with a title, featured image, and content area.", "label": "Blog Post"},
				{"prompt": "A pricing table with three columns for different plans, each with a title, features list, and a call-to-action button.", "label": "Pricing Table"},
				{"prompt": "A product card with an image, product name, price, and add-to-cart button.", "label": "Product Card"},
				{"prompt": "A sidebar navigation menu with icons and labels for each item.", "label": "Sidebar Menu"},
				{"prompt": "A hero section with a background image, headline, subheadline, and a call-to-action button.", "label": "Hero Section"},
				{"prompt": "A login form with email and password fields, a submit button, and a 'forgot password' link.", "label": "Login Form"},
				{"prompt": "A registration form with fields for username, email, password, and a submit button.", "label": "Registration Form"},
				{"prompt": "A modal dialog with a title, content area, and action buttons.", "label": "Modal Dialog"},
				{"prompt": "A footer with columns for links, contact information, and social media icons.", "label": "Footer"},
				{"prompt": "A search bar with an input field and a search button.", "label": "Search Bar"},
				{"prompt": "A notification banner with an icon, message text, and a close button.", "label": "Notification Banner"},
				{"prompt": "A task list with checkboxes, task descriptions, and a button to add new tasks.", "label": "Task List"},
				{"prompt": "A calendar view with days of the week and scheduled events.", "label": "Calendar View"},
				{"prompt": "A testimonial section with quotes, author names, and author photos.", "label": "Testimonial Section"},
				{"prompt": "A breadcrumb navigation with links to each section.", "label": "Breadcrumb Navigation"},
				{"prompt": "A card layout for a team section with member photos, names, and roles.", "label": "Team Section"},
				{"prompt": "A chat interface with a list of messages, an input field, and a send button.", "label": "Chat Interface"},
				{"prompt": "An FAQ section with questions and collapsible answers.", "label": "FAQ Section"},
				{"prompt": "A progress bar indicating completion percentage.", "label": "Progress Bar"}
			],
			prompt: '',
			promptButtons: [],
			isUserScrollBottom: true
		}
	},
	mounted() {
		window.ui = this;
		const randomIndex = Math.floor(Math.random() * this.examples.length)
		this.placeholder = this.examples[randomIndex].prompt
		this.promptButtons = this.getRandomPrompts(this.examples, 5);
	},
	methods: {
		getRandomPrompts(arr, num) {
			const shuffled = arr.sort(() => 0.5 - Math.random());
			return shuffled.slice(0, num);
		},
		examplePrompt(example) {
			this.prompt = example.prompt;
		},
		send: async function (payload) {

			let prompt = payload.prompt
			this.prompt = ''
			try {

				// if we have code assume the prompt is a continuation - so send it the previous context
				prompt = this.code + ' ' + prompt;
				const eventSource = new EventSource(route('ui.send', {prompt: prompt}), { withCredentials: true });
				// reset the code window
				this.code = '';

				eventSource.addEventListener('message', (event) => {

					const gpt = JSON.parse(event.data)
					if (gpt.delta.content) {
						this.code = this.code + gpt.delta.content
					}
				})

				eventSource.addEventListener("stop", (event) => {
					eventSource.close();
				});

				eventSource.addEventListener("error", (event) => {
					console.error("EventSource failed:", event.data);
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