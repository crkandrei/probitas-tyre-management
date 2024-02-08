<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

const totalTyres = ref(0);
const totalClients = ref(0);

onMounted(async () => {
    try {
        const response = await axios.get('/dashboard-stats');
        totalTyres.value = response.data.totalTyres;
        totalClients.value = response.data.totalClients;

    } catch (error) {
        console.error('Error fetching dashboard stats:', error);
    }
});
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
        </template>

        <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Total Tyres Card -->
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <!-- Icon or image can go here -->
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Total Clienti :</dt>
                                    <dd>
                                        <div class="text-lg font-medium text-gray-900">{{ totalClients }}</div>
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <!-- Icon or image can go here -->
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Total Anvelope :</dt>
                                    <dd>
                                        <div class="text-lg font-medium text-gray-900">{{ totalTyres }}</div>
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- More cards can be added here -->
            </div>
        </div>
    </AuthenticatedLayout>
</template>
