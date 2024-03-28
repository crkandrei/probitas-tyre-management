<script setup>
import { ref } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link } from '@inertiajs/vue3';

const showingNavigationDropdown = ref(false);
</script>
<template>
    <div class="flex flex-col md:flex-row h-screen bg-gray-100">
        <!-- Sidebar (top bar in mobile) -->
        <div class="bg-blue-500 md:w-64 w-full space-y-6 py-7 px-2 transform md:transform-none transition duration-200 ease-in-out">
            <!-- Logo/Brand -->
            <Link :href="route('dashboard')" class="text-white flex items-center space-x-2 px-4">
                <ApplicationLogo class="h-8 w-8" />
                <span class="text-2xl font-extrabold">Probitas</span>
            </Link>

            <!-- Navigation Links -->
            <nav>
                <ResponsiveNavLink :href="route('dashboard')" :active="route().current('dashboard')" class="rounded-lg">Dashboard</ResponsiveNavLink>
                <ResponsiveNavLink :href="route('checkin')" :active="route().current('checkin')" class="mt-3 rounded-lg">Check In</ResponsiveNavLink>
                <ResponsiveNavLink :href="route('tyre-list')" :active="route().current('tyre-list')" class="mt-3 rounded-lg">Lista Anvelope</ResponsiveNavLink>
                <ResponsiveNavLink :href="route('client-list')" :active="route().current('client-list')" class="mt-3 rounded-lg">Lista Clienti</ResponsiveNavLink>
                <!-- Add more links as needed -->
            </nav>
        </div>

        <!-- Content Area -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Bar -->
            <nav class="bg-white border-b border-gray-100">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-end h-16">
                        <!-- User Dropdown -->
                        <div class="flex items-center">
                            <Dropdown align="right" width="48">
                                <template #content>
                                    <DropdownLink :href="route('profile.edit')"> Profile </DropdownLink>
                                    <DropdownLink :href="route('logout')" method="post" as="button">
                                        Log Out
                                    </DropdownLink>
                                </template>
                                <template #trigger>
                                    <span class="inline-flex rounded-md">
                                        <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                            {{ $page.props.auth.user.name }}
                                            <!-- Dropdown Icon -->
                                        </button>
                                    </span>
                                </template>
                            </Dropdown>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-200">
                <slot />
            </main>
        </div>
    </div>
</template>

