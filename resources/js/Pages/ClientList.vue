<style>
.table-fixed {
    table-layout: fixed;
    width: 100%;
}

.table-fixed th, .table-fixed td {
    width: 25%; /* Adjust the width as needed */
}
</style>

<template>
    <AuthenticatedLayout>
        <Head title="Lista Clienți" />
        <div class="py-6">
            <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <input
                        type="text"
                        v-model="search"
                        @input="fetchClients"
                        placeholder="Search..."
                        class="mb-4 p-2 border border-gray-300 rounded-md"
                    />

                    <div class="relative overflow-y-auto" style="height: 70vh;">
                        <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50  sticky top-0">
                        <tr>
                            <!-- Define table headers -->
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nume Client</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Telefon</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Masini</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                        <tr v-for="client in clients.data" :key="client.id">
                            <!-- Define data cells -->
                            <td class="px-6 py-4 whitespace-nowrap">{{ client.name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ client.telephone }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ Array.from(new Set(client.tyres.map(tyre => tyre.car_number))).join(', ') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <button
                                    @click="openEditClientModal(client)"
                                    class="text-white bg-blue-500 hover:bg-blue-600 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800"
                                >
                                    Editare
                                </button>
                                <button
                                    @click="openDeleteClientModal(client)"
                                    class="text-white bg-red-500 hover:bg-red-600 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 focus:outline-none dark:focus:ring-red-800"
                                >
                                    Stergere
                                </button>

                                <button
                                    @click="viewHistory(client)"
                                    class="text-white bg-green-500 hover:bg-green-600 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 focus:outline-none dark:focus:ring-green-800"
                                >
                                    Istoric
                                </button>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    </div>
                </div>
            </div>
        </div>

        <div v-if="showDeleteClientModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity">
            <!-- Modal content -->
            <div class="fixed inset-0 z-10 overflow-y-auto">
                <!-- Modal inner content -->
                <div class="flex items-end sm:items-center justify-center min-h-full p-4 text-center sm:p-0">
                    <div class="relative bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:max-w-lg sm:w-full">
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">Sterge client</h3>
                            <p class="text-sm text-gray-500">Esti sigur ca vrei sa stergi clientul?</p>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button @click="submitDeleteClientForm" :disabled="isLoadingDelete" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                                Sterge
                            </button>
                            <button @click="showDeleteClientModal = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                Anuleaza
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div v-if="showEditClientModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity">
            <!-- Modal content -->
            <div class="fixed inset-0 z-10 overflow-y-auto">
                <!-- Modal inner content -->
                <div class="flex items-end sm:items-center justify-center min-h-full p-4 text-center sm:p-0">
                    <div class="relative bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:max-w-lg sm:w-full">
                        <!-- Form fields with provided styling -->
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">Editeaza informatii clienti </h3>
                            <div class="mt-2">
                                <div class="mb-4">
                                    <label for="editName" class="block text-sm font-medium text-gray-700">Nume</label>
                                    <input type="text" id="editName" v-model="editClientForm.name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                </div>
                                <div class="mb-4">
                                    <label for="editTelephone" class="block text-sm font-medium text-gray-700">Telefon</label>
                                    <input type="text" id="editTelephone" v-model="editClientForm.telephone" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button @click="submitEditClientForm" :disabled="isLoading" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                                Update
                            </button>
                            <button @click="showEditClientModal = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                Cancel
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div v-if="showHistoryModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity">
            <!-- Modal content -->
            <div class="fixed inset-0 z-10 overflow-y-auto">
                <!-- Modal inner content -->
                <div class="flex items-end sm:items-center justify-center min-h-full p-4 text-center sm:p-0">
                    <div class="relative bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all w-full">
                        <!-- History Table -->
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4 w-100">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 w-100">Client History</h3>
                            <div class="mt-2" v-for="(group, tyreId) in clientHistory" :key="tyreId">
                                <table class="min-w-full divide-y divide-gray-200 mt-2 table-fixed">
                                    <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Numar Masina</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Anvelope</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actiune</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">In data de</th>
                                    </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="historyItem in group" :key="historyItem.id">
                                        <td class="px-6 py-4 whitespace-nowrap">{{ historyItem.tyre.car_number }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ historyItem.tyre.model+'('+historyItem.tyre.size+')' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ historyItem.action }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ historyItem.action_date }}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>

                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button @click="showHistoryModal = false" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-gray-500 text-base font-medium text-white hover:bg-gray-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                                Close
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </AuthenticatedLayout>
</template>

<script setup>
import { ref, onMounted, reactive } from 'vue';
import axios from 'axios';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

const editClientForm = reactive({
    id: null,
    name: '',
    telephone: '',
    car_number: '',
});

const deleteClientForm = reactive({
    id: null,
});

const isLoadingEdit = ref(false);
const isLoadingDelete = ref(false);

const clients = ref({ data: [] });
const search = ref('');
const showHistoryModal = ref(false);
const clientHistory = ref([]);
const isLoadingHistory = ref(false);


const fetchClients = async () => {
    try {
        const response = await axios.get(`/clients?search=${search.value}`);
        clients.value = response.data;
    } catch (error) {
        console.error('Error fetching clients:', error);
    }
};

const showEditClientModal = ref(false);
const showDeleteClientModal = ref(false);

const openDeleteClientModal = (client) => {
    deleteClientForm.id = client.id;
    showDeleteClientModal.value = true;
}
const openEditClientModal = (client) => {
    editClientForm.id = client.id;
    editClientForm.name = client.name;
    editClientForm.telephone = client.telephone;
    editClientForm.car_number = client.car_number;
    showEditClientModal.value = true;
};

const submitDeleteClientForm = async () => {
    isLoadingDelete.value = true;
    try {
        await axios.delete(`/clients/${deleteClientForm.id}`);
        await fetchClients();
        showDeleteClientModal.value = false;
        toastr.success('Client sters cu succes!');
    } catch (error) {
        toastr.error('Eroare la stergere client!');
    } finally {
        isLoadingDelete.value = false;
    }
};

const submitEditClientForm = async () => {
    isLoadingEdit.value = true;
    try {
        await axios.put(`/clients/${editClientForm.id}`, editClientForm);
        await fetchClients(); // Assuming fetchClients is your method to refresh the list
        showEditClientModal.value = false;
        toastr.success('Client updatat cu succes!');
    } catch (error) {
        toastr.error('Eroare la actualizare client!');
    } finally {
        isLoadingEdit.value = false;
    }
};

const viewHistory = async (client) => {
    isLoadingHistory.value = true;
    showHistoryModal.value = true;
    try {
        const response = await axios.get(`/clients/${client.id}/history`);
        clientHistory.value = response.data;
    } catch (error) {
        toastr.error('Eroare date istoric client');
    } finally {
        isLoadingHistory.value = false;
    }
};


onMounted(fetchClients);
</script>
