<script setup>
import { reactive, ref, onMounted, computed, onUnmounted } from 'vue';
import axios from 'axios';
import toastr from 'toastr';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import ClientAddModal from '../Forms/ClientAddModal.vue';

const loading = reactive({ status: false });
const inputFocused = ref(false);
const isAddClientModalVisible = ref(false);
const clients = ref([]);
const searchQuery = ref(''); // Add this line

const clientId = ref('');
const carNumber = ref('');
function getQueryParams() {
    const queryParams = new URLSearchParams(window.location.search);
    clientId.value = queryParams.get('client_id');
    carNumber.value = queryParams.get('car_number');
    console.log(clientId.value, carNumber.value);
}

const form = reactive({
    client_id: '',
    car_number: '',
    tyre_model: '',
    tyre_size: '',
    quantity: 1,
    has_rim: false,
    observations: '',
    checkin_date: new Date().toISOString().substring(0, 10),
});

const dropdownVisible = ref(false);

// Compute filteredClients based on searchQuery
const filteredClients = computed(() => {
    if (!searchQuery.value) {
        return clients.value;
    }
    return clients.value.filter(client =>
        client.name.toLowerCase().includes(searchQuery.value.toLowerCase())
    );
});

const onInputFocus = () => {
    dropdownVisible.value = true;
    inputFocused.value = true; // Show all clients when input is focused
};

onMounted(() => {
    getQueryParams();
    form.client_id = clientId.value;
    form.car_number = carNumber.value;
    fetchClients();
    document.addEventListener('click', handleClickOutside);
    document.addEventListener('keydown', handleEscape);
});

onUnmounted(() => {
    document.removeEventListener('click', handleClickOutside);
    document.removeEventListener('keydown', handleEscape);
});

const showAddClientModal = () => {
    isAddClientModalVisible.value = true;
};


const fetchClients = async () => {
    try {
        const response = await axios.get('/clients/list');
        clients.value = response.data;

        // After clients are fetched, auto-select the client based on client_id
        const selectedClient = clients.value.find(c => c.id.toString() === clientId.value);
        if (selectedClient) {
            searchQuery.value = selectedClient.name;
            // Directly select the client in the form as well
            form.client_id = selectedClient.id;
        }
    } catch (error) {
        toastr.error("Clientii nu au putut fi adusi din baza de date.");
    }
};
// Function to toggle the visibility based on external clicks
function handleClickOutside(event) {
    const inputElement = document.getElementById('client'); // Reference to the input element
    const dropdownElement = document.getElementById('dropdown'); // Reference to the dropdown element

    if (inputElement && !inputElement.contains(event.target) &&
        dropdownElement && !dropdownElement.contains(event.target)) {
        dropdownVisible.value = false;
    }
}
// Function to handle the ESC key
function handleEscape(event) {
    if (event.key === 'Escape') {
        dropdownVisible.value = false;
        searchQuery.value = ''; // Optionally clear search query
    }
}


const selectClient = (client) => {
    form.client_id = client.id;
    searchQuery.value = client.name; // Update the search field with the client's name
    dropdownVisible.value = false; // Directly hide the dropdown
    inputFocused.value = false; // Adjust based on your implementation
};

const handleClientAdded = (newClient) => {
    clients.value.push(newClient);
};

async function submit() {
    loading.status = true;
    try {
        const response = await axios.post('/tyres/check-in', form);
        toastr.success(response.data.message);
        form.client_id = '';
        form.car_number = '';
        form.tyre_model = '';
        form.tyre_size = '';
        form.quantity = 1;
        form.has_rim = false;
        form.observations = '';
        searchQuery.value = ''; // Clear the search query to reset the select
        dropdownVisible.value = false; // Hide the dropdown

    } catch (error) {
        if (error.response) {
            if (error.response.status === 422 && error.response.data.errors) {
                const errors = error.response.data.errors;
                toastr.error(Object.values(errors).join('\n'));
            } else if (error.response.data && error.response.data.message) {
                toastr.error(error.response.data.message);
            } else {
                toastr.error("Eroare necunoscuta.");
            }
        } else {
            toastr.error("Eroare Internet.");
        }
    } finally {
        loading.status = false;
    }
}
</script>



<template>
    <Head title="Checkin Anvelope" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Checkin Anvelope</h2>
        </template>
        <div class="py-12">
            <div class="max-w-xl mx-auto py-6">
                <!-- Add Client Modal Trigger Button -->

                <form @submit.prevent="submit">
                    <div class="mb-4 relative">
                        <label for="client" class="block text-sm font-medium text-gray-700">Client*</label>
                        <input type="text" id="client"
                            v-model="searchQuery"
                            @focus="onInputFocus"
                            placeholder="Search..."
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" />
                        <ul v-if="dropdownVisible && (searchQuery || inputFocused)" id="dropdown"
                            class="absolute z-10 w-full bg-white mt-1 border border-gray-300 rounded-md shadow-lg max-h-60 overflow-auto">
                            <li v-for="client in filteredClients"
                                :key="client.id"
                                @click="selectClient(client)"
                                class="cursor-pointer p-2 hover:bg-gray-100">
                                {{ client.name }}
                            </li>
                        </ul>
                    </div>

                    <div class="mb-4">
                        <button type="button" @click="showAddClientModal" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">Adauga Client Nou</button>
                    </div>
                <div class="mb-4">
                    <label for="car_number" class="block text-sm font-medium text-gray-700">Numar Masina*</label>
                    <input type="text" id="car_number" v-model="form.car_number" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                </div>

                <div class="mb-4">
                    <label for="tyre_model" class="block text-sm font-medium text-gray-700">Model Anvelope*</label>
                    <input type="text" id="tyre_model" v-model="form.tyre_model" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                </div>

                <div class="mb-4">
                    <label for="tyre_size" class="block text-sm font-medium text-gray-700">Dimensiuni Anvelope*</label>
                    <input type="text" id="tyre_size" v-model="form.tyre_size" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                </div>

                <div class="mb-4">
                    <label for="quantity" class="block text-sm font-medium text-gray-700">Numar anvelope*</label>
                    <input type="number" id="quantity" v-model.number="form.quantity" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required min="1">
                </div>

                <div class="mb-4">
                    <label for="has_rim" class="flex items-center">
                        <input type="checkbox" id="has_rim" v-model="form.has_rim" class="mr-2">
                        <span class="text-sm font-medium text-gray-700">Cu Janta?</span>
                    </label>
                </div>

                <div class="mb-4">
                    <label for="observations" class="block text-sm font-medium text-gray-700">Observatii</label>
                    <textarea id="observations" v-model="form.observations" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"></textarea>
                </div>
                <div class="mb-4">
                    <label for="checkin_date" class="block text-sm font-medium text-gray-700">Data CheckIn</label>
                    <input type="date" id="checkin_date" v-model="form.checkin_date" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                </div>
                <div class="flex justify-end">
                    <button
                        type="submit"
                        :disabled="loading.status"
                        class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600"
                    >
                        <span v-if="loading.status">Loading...</span>
                        <span v-else>Check In</span>
                    </button>
                </div>
            </form>
            </div>
        </div>
        <ClientAddModal :isVisible="isAddClientModalVisible" @client-added="handleClientAdded" @close-modal="isAddClientModalVisible = false"></ClientAddModal>
    </AuthenticatedLayout>
</template>
