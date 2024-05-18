<template>
	<div class="h-full overflow-y-scroll">
		<TransitionRoot as="template" :show="sidebarOpen">
			<Dialog as="div" class="relative z-50 lg:hidden" @close="sidebarOpen = false">
				<TransitionChild as="template" enter="transition-opacity ease-linear duration-300" enter-from="opacity-0" enter-to="opacity-100" leave="transition-opacity ease-linear duration-300" leave-from="opacity-100" leave-to="opacity-0">
					<div class="fixed inset-0 bg-gray-900/80" />
				</TransitionChild>

				<div class="fixed inset-0 flex">
					<TransitionChild as="template" enter="transition ease-in-out duration-300 transform" enter-from="-translate-x-full" enter-to="translate-x-0" leave="transition ease-in-out duration-300 transform" leave-from="translate-x-0" leave-to="-translate-x-full">
						<DialogPanel class="relative mr-16 flex w-full max-w-xs flex-1">
							<TransitionChild as="template" enter="ease-in-out duration-300" enter-from="opacity-0" enter-to="opacity-100" leave="ease-in-out duration-300" leave-from="opacity-100" leave-to="opacity-0">
								<div class="absolute left-full top-0 flex w-16 justify-center pt-5">
									<button type="button" class="-m-2.5 p-2.5" @click="sidebarOpen = false">
										<span class="sr-only">Close sidebar</span>
										<XMarkIcon class="h-6 w-6 text-white" aria-hidden="true" />
									</button>
								</div>
							</TransitionChild>

							<div class="flex grow flex-col gap-y-5 overflow-y-auto bg-gray-900 px-6 pb-2 ring-1 ring-white/10">
								<a class="flex h-16 shrink-0 items-center text-white">
									<svg class="h-5" width="22" height="25" viewBox="0 0 22 25" fill="none" xmlns="http://www.w3.org/2000/svg">
										<path d="M5.45769 24.2342V9.5455L16.9231 24.2342H20.7335V20.4352L4.7756 0H0V24.2342H5.45769Z" fill="currentColor" />
										<path d="M16 5.42352L21.4235 5.42352V2.90871e-05L16 2.90871e-05V5.42352Z" fill="currentColor" />
									</svg>
								</a>
								<nav class="flex flex-1 flex-col">
									<ul role="list" class="-mx-2 flex-1 space-y-1">
										<li v-for="item in navigation" :key="item.name">
											<a :href="item.href" :class="[item.current ? 'bg-gray-800 text-white' : 'text-gray-400 hover:text-white hover:bg-gray-800', 'group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold']">
												<component :is="item.icon" class="h-6 w-6 shrink-0" aria-hidden="true" />
												{{ item.name }}
											</a>
										</li>
									</ul>
								</nav>
							</div>
						</DialogPanel>
					</TransitionChild>
				</div>
			</Dialog>
		</TransitionRoot>

		<!-- Static sidebar for desktop -->
		<div class="hidden lg:fixed lg:inset-y-0 lg:left-0 lg:z-50 lg:block lg:w-20 lg:overflow-y-auto lg:bg-gray-900 lg:pb-4">
			<Link :href="dashboardHref" class="flex h-16 shrink-0 items-center justify-center text-white">
				<svg class="h-5" width="22" height="25" viewBox="0 0 22 25" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path d="M5.45769 24.2342V9.5455L16.9231 24.2342H20.7335V20.4352L4.7756 0H0V24.2342H5.45769Z" fill="currentColor" />
					<path d="M16 5.42352L21.4235 5.42352V2.90871e-05L16 2.90871e-05V5.42352Z" fill="currentColor" />
				</svg>
			</Link>
			<nav class="mt-8">
				<ul role="list" class="flex flex-col items-center space-y-1">
					<li v-for="item in navigation" :key="item.name">
						<Link :href="item.href" :class="[item.current ? 'bg-gray-800 text-white' : 'text-gray-400 hover:text-white hover:bg-gray-800', 'group flex gap-x-3 rounded-md p-3 text-sm leading-6 font-semibold']">
							<component :is="item.icon" class="h-6 w-6 shrink-0" aria-hidden="true" />
							<span class="sr-only">{{ item.name }}</span>
						</Link>
					</li>
				</ul>
			</nav>
		</div>

		<div class="lg:pl-20 h-full flex flex-col">
			<div class="sticky top-0 z-40 flex h-16 shrink-0 items-center gap-x-4 border-b border-gray-200 dark:border-black bg-white dark:bg-gray-800 px-4 shadow-sm">
				<button type="button" class="-m-2.5 p-2.5 text-gray-700 lg:hidden" @click="sidebarOpen = true">
					<span class="sr-only">Open sidebar</span>
					<Bars3Icon class="h-6 w-6" aria-hidden="true" />
				</button>

				<!-- Separator -->
				<div class="h-6 w-px bg-gray-900/10 lg:hidden" aria-hidden="true" />

				<div class="flex flex-1 gap-x-4 self-stretch items-center lg:gap-x-6">
					<div class="relative flex flex-1">
					<!-- <form class="relative flex flex-1" action="#" method="GET">
						<label for="search-field" class="sr-only">Search</label>
						<MagnifyingGlassIcon class="pointer-events-none absolute inset-y-0 left-0 h-full w-5 text-gray-400" aria-hidden="true" />
						<input id="search-field" class="block h-full w-full border-0 py-0 pl-8 pr-0 text-gray-900 placeholder:text-gray-400 focus:ring-0 sm:text-sm" placeholder="Search..." type="search" name="search" />
					</form> -->
						<slot name="header"></slot>
					</div>

					<div class="flex items-center gap-x-4 lg:gap-x-6">

						<!-- Profile dropdown -->
						<Menu as="div" class="relative">
							<MenuButton class="-m-1.5 flex items-center p-1.5">
								<span class="sr-only">Open user menu</span>
								<img class="h-8 w-8 rounded-full bg-gray-50" :src="$page.props.auth.user.avatar_url" :alt="$page.props.auth.user.name || $page.props.auth.user.email" />
								<span class="hidden lg:flex lg:items-center">
									<span class="ml-4 text-sm font-semibold leading-6 text-gray-900 dark:text-gray-200" aria-hidden="true">
										{{ $page.props.auth.user.name || $page.props.auth.user.email }}
									</span>
									<ChevronDownIcon class="ml-2 h-5 w-5 text-gray-400" aria-hidden="true" />
								</span>
							</MenuButton>
							<transition enter-active-class="transition ease-out duration-100" enter-from-class="transform opacity-0 scale-95" enter-to-class="transform opacity-100 scale-100" leave-active-class="transition ease-in duration-75" leave-from-class="transform opacity-100 scale-100" leave-to-class="transform opacity-0 scale-95">
								<MenuItems class="absolute right-0 z-10 mt-2.5 w-32 origin-top-right rounded-md bg-white py-2 shadow-lg ring-1 ring-gray-900/5 focus:outline-none">
									<MenuItem v-for="item in userNavigation" :key="item.name" v-slot="{ active }">
										<Link class="w-full text-left" v-bind="item" :href="item.href" :class="[active ? 'bg-gray-50' : '', 'block px-3 py-1 text-sm leading-6 text-gray-900']">{{ item.name }}</Link>
									</MenuItem>
								</MenuItems>
							</transition>
						</Menu>
					</div>
				</div>
			</div>

			<main class="grow">
				<div class="h-full">
					<!-- Main area -->
					<slot></slot>
				</div>
			</main>
		</div>

	</div>
</template>

<script setup>
import { ref } from 'vue'
import { Link } from '@inertiajs/vue3';
import { Dialog, DialogPanel, Menu, MenuButton, MenuItem, MenuItems, TransitionChild, TransitionRoot, } from '@headlessui/vue'
import {
	Bars3Icon,
	BellIcon,
	CalendarIcon,
	ChartPieIcon,
	DocumentDuplicateIcon,
	FolderIcon,
	HomeIcon,
	UsersIcon,
	XMarkIcon,
	CircleStackIcon
} from '@heroicons/vue/24/outline'
import { ChevronDownIcon, MagnifyingGlassIcon } from '@heroicons/vue/20/solid'

const navigation = [
	{ name: 'Dashboard', href: route('dashboard'), icon: HomeIcon, current: route().current('dashboard')  },
	{ name: 'Projects', href: route('chat'), icon: FolderIcon, current: route().current('chat*') },
	{ name: 'Team', href: route('team'), icon: UsersIcon, current: route().current('team*') },
	// { name: 'Database', href: '#', icon: CircleStackIcon, current: false },
	// { name: 'Calendar', href: '#', icon: CalendarIcon, current: false },
	// { name: 'Documents', href: '#', icon: DocumentDuplicateIcon, current: false },
]
const userNavigation = [
	{ name: 'Your profile', href: route('profile.edit') },
	{ name: 'Sign out', href: route('logout'), method: 'post', as: 'button' },
]
const dashboardHref = route('dashboard')

const sidebarOpen = ref(false)
</script>