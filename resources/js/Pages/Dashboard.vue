<script setup>
import {ref, onMounted, nextTick} from 'vue';
import axios from 'axios';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import Chart from 'chart.js/auto';

const totalTyres = ref(0);
const totalClients = ref(0);

const tyreAgeData = ref({ ages: [], counts: [] });
const chartRef = ref(null);

onMounted(async () => {
    try {
        const response = await axios.get('/dashboard-stats');
        totalTyres.value = response.data.totalTyres;
        totalClients.value = response.data.totalClients;

        const tyreAgeResponse = await axios.get('/tyre-age-stats');
        tyreAgeData.value = tyreAgeResponse.data.original;
        // Wait for next tick to ensure the canvas element is rendered
        await nextTick();
        setupChart();

    } catch (error) {
        console.error('Error fetching dashboard stats:', error);
    }
});
const setupChart = () => {
    console.table(tyreAgeData.value.counts);
    const ctx = chartRef.value.getContext('2d');
    const chart = new Chart(ctx, {
        type: 'bar', // You can choose another type
        data: {
            labels: tyreAgeData.value.ages,
            datasets: [{
                label: 'Vechimea Anvelopelor in Depozit',
                data: tyreAgeData.value.counts,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    ticks : {
                        stepSize: 1
                    }
                }
            }
        }
    });
};

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
                                    <dt class="text-sm font-medium text-gray-500 truncate mb-6">Total Clienti :</dt>
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
                                    <dt class="text-sm font-medium text-gray-500 truncate mb-5">Total Anvelope In Depozit :</dt>
                                    <dd>
                                        <div class="text-lg font-medium text-gray-900">{{ totalTyres }}</div>
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
                                    <dt class="text-sm font-medium text-gray-500 truncate mb-5">Buton Generare Raport Excel :</dt>
                                    <dd>
                                        <a href="/excel/export" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                                            Generare Raport Excel
                                        </a>
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- More cards can be added here -->
            </div>

            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-1 mt-4">
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <canvas ref="chartRef"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
