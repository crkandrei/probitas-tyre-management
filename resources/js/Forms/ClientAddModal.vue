<template>

    <div v-if="isVisible" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity">
        <!-- Modal content -->
        <div class="fixed inset-0 z-10 overflow-y-auto">
            <!-- Modal inner content -->
            <div class="flex items-end sm:items-center justify-center min-h-full p-4 text-center sm:p-0">
                <div class="relative bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all w-1/4">
                    <!-- History Table -->
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4 w-100">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 w-100">Add Client</h3>
                        <div class="mt-2">
                            <div class="mb-4">
                                <label for="clientName" class="block text-sm font-medium text-gray-700">Client Name*</label>
                                <input type="text" id="clientName" v-model="newClient.name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                            </div>
                            <div class="mb-4">
                                <label for="telephone" class="block text-sm font-medium text-gray-700">Telefon*</label>
                                <input type="text" id="telephone" v-model="newClient.telephone" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                            </div>

                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button @click="addClient" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm" :disabled="isLoading">
                            <span v-if="isLoading">Loading...</span>
                            <span v-else>Adauga</span>
                        </button>
                        <button @click="closeModal" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-gray-500 text-base font-medium text-white hover:bg-gray-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import axios from 'axios';
import toastr from 'toastr';

// Initialize newClient as a reactive object
const newClient = ref({
    name: '',
    telephone: ''
});

// New reactive property to track loading state
const isLoading = ref(false);

// Define props
const props = defineProps({
    isVisible: Boolean
});

// Emit function to notify parent component about the modal close action
const emit = defineEmits(['update:isVisible']);
const closeModal = () => {
    emit('close-modal');
};

const addClient = async () => {
    isLoading.value = true; // Start loading

    try {
        const response = await axios.post('/client/add', newClient.value);
        if (response.status === 200) {
            toastr.success(response.data.message);
            let addedClientData = response.data.client;
            newClient.value = { name: '', telephone: '' }; // Reset client info
            emit('close-modal');
            emit('client-added',addedClientData);
        }
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
        isLoading.value = false; // End loading regardless of request outcome
    }
};
</script>

