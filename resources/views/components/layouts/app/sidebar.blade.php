<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">
        <flux:sidebar sticky stashable class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

            <a href="{{ route('dashboard') }}" class="me-5 flex items-center space-x-2 rtl:space-x-reverse" wire:navigate>
                <x-app-logo />
            </a>

            <flux:navlist variant="outline">
                <flux:navlist.group :heading="__('Dashboard')" class="grid">
                    <flux:navlist.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>{{ __('Overview') }}</flux:navlist.item>
                </flux:navlist.group>

                <flux:navlist.group :heading="__('Content Management')" class="grid">
                    <flux:navlist.item icon="photo" :href="route('admin.gallery.index')" :current="request()->routeIs('admin.gallery.*')" wire:navigate>{{ __('Gallery') }}</flux:navlist.item>
                    <flux:navlist.item icon="video-camera" :href="route('admin.home-videos.index')" :current="request()->routeIs('admin.home-videos.*')" wire:navigate>{{ __('Homepage Videos') }}</flux:navlist.item>
                    <flux:navlist.item icon="newspaper" :href="route('admin.news.index')" :current="request()->routeIs('admin.news.*')" wire:navigate>{{ __('News') }}</flux:navlist.item>
                    <flux:navlist.item icon="star" :href="route('admin.initiatives.index')" :current="request()->routeIs('admin.initiatives.*')" wire:navigate>{{ __('Initiatives') }}</flux:navlist.item>
                    <flux:navlist.item icon="clock" :href="route('admin.timeline.index')" :current="request()->routeIs('admin.timeline.*')" wire:navigate>{{ __('Career Timeline') }}</flux:navlist.item>
                    <flux:navlist.item icon="document-text" :href="route('admin.pages.index')" :current="request()->routeIs('admin.pages.*')" wire:navigate>{{ __('Pages') }}</flux:navlist.item>
                    <flux:navlist.item icon="envelope" :href="route('admin.contacts.index')" :current="request()->routeIs('admin.contacts.*')" wire:navigate>
                        <span class="flex items-center justify-between w-full">
                            <span>{{ __('Contacts') }}</span>
                            @php
                                $unreadCount = \App\Models\ContactSubmission::unread()->count();
                            @endphp
                            @if($unreadCount > 0)
                                <span class="ml-auto px-2 py-0.5 text-xs font-semibold rounded-full bg-yellow-500 text-white">{{ $unreadCount }}</span>
                            @endif
                        </span>
                    </flux:navlist.item>
                </flux:navlist.group>
            </flux:navlist>

            <flux:spacer />

            <flux:navlist variant="outline">
                <flux:navlist.item icon="cog" :href="route('admin.settings.index')" :current="request()->routeIs('admin.settings.*')" wire:navigate>{{ __('Settings') }}</flux:navlist.item>
                <flux:navlist.item icon="share" :href="route('admin.social-media.index')" :current="request()->routeIs('admin.social-media.*')" wire:navigate>{{ __('Social Media') }}</flux:navlist.item>
                <flux:navlist.item icon="phone" :href="route('admin.contact-info.index')" :current="request()->routeIs('admin.contact-info.*')" wire:navigate>{{ __('Contact Info') }}</flux:navlist.item>
                <flux:navlist.item icon="squares-2x2" :href="route('admin.explore-cards.index')" :current="request()->routeIs('admin.explore-cards.*')" wire:navigate>{{ __('Explore Cards') }}</flux:navlist.item>
                <flux:navlist.item icon="photo" :href="route('admin.footer-image.index')" :current="request()->routeIs('admin.footer-image.*')" wire:navigate>{{ __('Footer Image') }}</flux:navlist.item>
                <flux:navlist.item icon="globe-alt" :href="route('home')" target="_blank">
                {{ __('View Public Site') }}
                </flux:navlist.item>
            </flux:navlist>

            <!-- Desktop User Menu -->
            <flux:dropdown class="hidden lg:block" position="bottom" align="start">
                <flux:profile
                    :name="auth()->user()->name"
                    :initials="auth()->user()->initials()"
                    icon:trailing="chevrons-up-down"
                    data-test="sidebar-menu-button"
                />

                <flux:menu class="w-[220px]">
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                    <span
                                        class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white"
                                    >
                                        {{ auth()->user()->initials() }}
                                    </span>
                                </span>

                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                    <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>{{ __('Settings') }}</flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full" data-test="logout-button">
                            {{ __('Log Out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:sidebar>

        <!-- Mobile User Menu -->
        <flux:header class="lg:hidden">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

            <flux:spacer />

            <flux:dropdown position="top" align="end">
                <flux:profile
                    :initials="auth()->user()->initials()"
                    icon-trailing="chevron-down"
                />

                <flux:menu>
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                    <span
                                        class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white"
                                    >
                                        {{ auth()->user()->initials() }}
                                    </span>
                                </span>

                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                    <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>{{ __('Settings') }}</flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full" data-test="logout-button">
                            {{ __('Log Out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:header>

        {{ $slot }}

        @fluxScripts
    </body>
</html>
