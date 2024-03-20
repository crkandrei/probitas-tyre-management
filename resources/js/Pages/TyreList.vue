<style>
/* Add custom styles here to fine-tune the table's appearance. For example: */
.table-sm th, .table-sm td {
    padding: 0.25rem;
    font-size: 0.9rem; /* Adjust font size as needed */
}
</style>
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref, onMounted, reactive } from 'vue';
import axios from 'axios';
import toastr from "toastr";
import { Inertia } from '@inertiajs/inertia';

const tyres = ref({ data: [], next_page_url:[], prev_page_url:[]});
const search = ref('');
const showModal = ref(false);
const showEditModal = ref(false);
const isLoading = ref(false);
const storageForm = reactive({
    row: '',
    column: '',
    shelf: '',
    observations: ''
});

const editForm = reactive({
    row: '',
    column: '',
    shelf: '',
    observations: ''
});

const editTyreForm = reactive({
    id: 1,
    car_number: '',
    model: '',
    size: '',
    observations: '',
    quantity: '',
    status: '',
    hasRim: false
});

const openEditModal = (tyre) => {
    editTyreForm.id = tyre.id;
    editTyreForm.car_number = tyre.car_number;
    editTyreForm.model = tyre.model;
    editTyreForm.size = tyre.size;
    editTyreForm.observations = tyre.observations;
    editTyreForm.quantity = tyre.quantity;
    editTyreForm.status = tyre.status;
    editTyreForm.hasRim = Boolean(tyre.hasRim);
    showEditTyreModal.value = true;
};

const selectedStorageForEdit = ref(null);

const getTyres = async (pageOrUrl) => {
    try {
        let url = '';

        if (typeof pageOrUrl === 'string') {
            url = pageOrUrl;
        } else {
            url = `/tyres?page=${pageOrUrl}&search=${search.value}`;
        }

        const response = await axios.get(url);
        tyres.value = response.data;
    } catch (error) {
        console.error(error);
    }
};

onMounted(() => getTyres());

const addToStorage = (tyreId) => {
    showModal.value = true;
    resetStorageForm();
    storageForm.tyreId = tyreId; // Set the tyre ID
};

const resetStorageForm = () => {
    storageForm.row = '';
    storageForm.column = '';
    storageForm.shelf = '';
    storageForm.observations = '';
};

const generateCheckinDocument = async (tyreId) => {
    try {
        const response = await axios.get(`/tyre/generate-checkin-document/${tyreId}`, {
            responseType: 'blob'
        });

        if (response.status === 200) {
            toastr.success(response.data.message);
            // Create a Blob from the PDF Stream
            const file = new Blob([response.data], { type: 'application/pdf' });
            // Build a URL from the file
            const fileURL = URL.createObjectURL(file);
            setTimeout(() => {
                window.open(fileURL);
            }, 4000);
        }
    }  catch (error) {
        if (error.response) {
            if (error.response.status === 422 && error.response.data.errors) {
                const errors = error.response.data.errors;
                toastr.error(Object.values(errors).join('\n'));
            }
            else if (error.response.data && error.response.data.message) {
                toastr.error(error.response.data.message);
            }
            else {
                toastr.error("Eroare necunoscuta.");
            }
        } else {
            toastr.error("Eroare Internet.");
        }
    } finally {
        isLoading.value = false;
    }
}
const generateCheckoutDocument = async (tyreId) => {
    try {
        const response = await axios.get(`/tyre/generate-checkout-document/${tyreId}`, {
            responseType: 'blob'
        });

        if (response.status === 200) {
            toastr.success(response.data.message);
            // Create a Blob from the PDF Stream
            const file = new Blob([response.data], { type: 'application/pdf' });
            // Build a URL from the file
            const fileURL = URL.createObjectURL(file);
            setTimeout(() => {
                window.open(fileURL);
            }, 4000);
        }
    }  catch (error) {
        if (error.response) {
            if (error.response.status === 422 && error.response.data.errors) {
                const errors = error.response.data.errors;
                toastr.error(Object.values(errors).join('\n'));
            }
            else if (error.response.data && error.response.data.message) {
                toastr.error(error.response.data.message);
            }
            else {
                toastr.error("Eroare necunoscuta.");
            }
        } else {
            toastr.error("Eroare Internet.");
        }
    } finally {
        isLoading.value = false;
    }
}

const submitStorageForm = async (tyreId) => {
    isLoading.value = true;
    try {
        const response = await axios.post('/tyre-storage-link', { ...storageForm });
        showModal.value = false;
        if (response.status === 200) {
            await getTyres();
            toastr.success(response.data.message);
        }
    }  catch (error) {
        if (error.response) {
            if (error.response.status === 422 && error.response.data.errors) {
                const errors = error.response.data.errors;
                toastr.error(Object.values(errors).join('\n'));
            }
            else if (error.response.data && error.response.data.message) {
                toastr.error(error.response.data.message);
            }
            else {
                toastr.error("Eroare necunoscuta.");
            }
        } else {
            toastr.error("Eroare Internet.");
        }
    } finally {
        isLoading.value = false;
    }
};

const editStorage = (tyreId, storage) => {
    editForm.row = storage.row || '';
    editForm.column = storage.column || '';
    editForm.shelf = storage.shelf || '';
    editForm.observations = storage.observations || '';

    selectedStorageForEdit.value = storage;

    showEditModal.value = true;
};

const submitEditForm = async () => {
    isLoading.value = true;
    try {
        const response = await axios.put(`/tyre-storage-link/${selectedStorageForEdit.value.id}`, { ...editForm });
        if (response.status === 200) {
            await getTyres();
            selectedStorageForEdit.value = null;
            showEditModal.value = false;
            toastr.success(response.data.message);
        }
    }  catch (error) {
        if (error.response) {
            if (error.response.status === 422 && error.response.data.errors) {
                const errors = error.response.data.errors;
                toastr.error(Object.values(errors).join('\n'));
            }
            else if (error.response.data && error.response.data.message) {
                toastr.error(error.response.data.message);
            }
            else {
                toastr.error("Eroare necunoscuta.");
            }
        } else {
            toastr.error("Eroare Internet.");
        }
    } finally {
        isLoading.value = false;
    }
};
const submitEditTyreForm = async (tyreId) => {
    isLoading.value = true;
    try {
        const response = await axios.put(`/tyres/${tyreId}`, { ...editTyreForm });
        if (response.status === 200) {
            await getTyres();
            showEditTyreModal.value = false;
            toastr.success(response.data.message);
        }
    } catch (error) {
        if (error.response) {
            if (error.response.status === 422 && error.response.data.errors) {
                const errors = error.response.data.errors;
                toastr.error(Object.values(errors).join('\n'));
            }
            else if (error.response.data && error.response.data.message) {
                toastr.error(error.response.data.message);
            }
            else {
                toastr.error("Eroare necunoscuta.");
            }
        } else {
            toastr.error("Eroare Internet.");
        }
    } finally {
        isLoading.value = false;
    }
};
const showCheckoutModal = ref(false);
const selectedTyreForCheckout = ref(null);
const showEditTyreModal = ref(false);

const openCheckoutModal = (tyre) => {
    selectedTyreForCheckout.value = tyre;
    showCheckoutModal.value = true;

};

const checkoutTyre = async () => {
    isLoading.value = true;
    try {
        const response = await axios.post(`/tyres/checkout/${selectedTyreForCheckout.value.id}`);
        if (response.status === 200) {
            await getTyres();
            showCheckoutModal.value = false;
            toastr.success(response.data.message);
        }
    }  catch (error) {
        if (error.response) {
            if (error.response.status === 422 && error.response.data.errors) {
                const errors = error.response.data.errors;
                toastr.error(Object.values(errors).join('\n'));
            }
            else if (error.response.data && error.response.data.message) {
                toastr.error(error.response.data.message);
            }
            else {
                toastr.error("Eroare necunoscuta.");
            }
        } else {
            toastr.error("Eroare Internet.");
        }
    } finally {
        isLoading.value = false;
    }
};

const handlePageChange = (url) => {
    getTyres(url);
};

</script>

<template>
    <Head title="Lista Anvelope" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Lista Anvelope</h2>
        </template>
        <div class="py-6">
            <div class="max-w-8xl mx-auto sm:px-6 lg:px-2">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 ">
                    <input type="text" v-model="search" @input="getTyres" placeholder="Search..." class="mb-4 p-2 border border-gray-300 rounded-md">

                    <div class="relative overflow-y-auto" style="height: 80vh;">
                        <table class="min-w-full divide-y divide-gray-200 table-sm">
                            <thead class="bg-gray-50 sticky top-0">
                                <tr>
                                    <th class="px-3 py-2">Nr. Mas.</th>
                                    <th class="px-3 py-2">Model</th>
                                    <th class="px-3 py-2">Dim.</th>
                                    <th class="px-3 py-2">Obs.</th>
                                    <th class="px-3 py-2">Cant.</th>
                                    <th class="px-3 py-2">Status.</th>
                                    <th class="px-3 py-2">Jante</th>
                                    <th class="px-3 py-2">Depozit</th>
                                    <th class="px-3 py-2">Actiune</th>
                                </tr>
                            </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                        <tr v-for="tyre in tyres.data" :key="tyre.id">
                            <td class="px-6 py-4 whitespace-nowrap">{{ tyre.car_number }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ tyre.model }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ tyre.size }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ tyre.observations }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ tyre.quantity }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ tyre.status }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ tyre.hasRim === 1 ? 'Da' : 'Nu' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span v-if="tyre.status === 'Depozitate'" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-xl bg-green-300 text-green-800">
                                    rand : {{ tyre.storage.row }} | coloana : {{ tyre.storage.column }} | raft : {{ tyre.storage.shelf }}
                                    <br />
                                    {{ tyre.storage.observations }}
                                </span>
                                <span v-if="tyre.status === 'Preluate de la Client'" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    Neasignat
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <button
                                    v-if="!tyre.storage_id && tyre.status === 'Preluate de la Client'"
                                    @click="addToStorage(tyre.id)"
                                    class="text-white bg-green-500 hover:bg-green-600 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 focus:outline-none dark:focus:ring-green-800"
                                >
                                    Depozitare
                                </button>

                                <div v-if="tyre.storage_id">
                                    <button
                                        @click="editStorage(tyre.id, tyre.storage)"
                                        class="text-white bg-yellow-500 hover:bg-yellow-600 focus:ring-4 focus:ring-yellow-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-yellow-600 dark:hover:bg-yellow-700 focus:outline-none dark:focus:ring-yellow-800"
                                    >
                                        Schimba locatie
                                    </button>
                                    <button
                                        @click="openCheckoutModal(tyre)"
                                        class="text-white bg-gray-500 hover:bg-gray-600 focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-gray-600 dark:hover:bg-gray-700 focus:outline-none dark:focus:ring-gray-800"
                                    >
                                        Check Out
                                    </button>
                                </div>

                                <button
                                    v-if="tyre.status !== 'Predate la Client'"
                                    @click="openEditModal(tyre)"
                                    class="text-white bg-yellow-500 hover:bg-yellow-600 focus:ring-4 focus:ring-yellow-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-yellow-600 dark:hover:bg-yellow-700 focus:outline-none dark:focus:ring-yellow-800"
                                >
                                    Modifica
                                </button>

                                <a
                                    v-if="tyre.status === 'Predate la Client'"
                                    :href="route('checkin', { car_number: tyre.car_number, client_id: tyre.client_id })"
                                    class="text-white bg-green-500 hover:bg-green-600 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 focus:outline-none dark:focus:ring-green-800"
                                >
                                    Check In
                                </a>

                                <button
                                    @click="generateCheckinDocument(tyre.id)"
                                    class=" mt-2 text-white bg-blue-500 hover:bg-blue-600 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800"
                                >
                                    PV Primire
                                </button>

                                <button
                                    v-if="tyre.status === 'Predate la Client'"
                                    @click="generateCheckoutDocument(tyre.id)"
                                    class="text-white bg-blue-500 hover:bg-blue-600 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800"
                                >
                                    PV Predare
                                </button>
                            </td>
                        </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="10" class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-between">
                                        <button v-if="tyres.prev_page_url" @click="handlePageChange(tyres.prev_page_url)" class="px-4 py-2 bg-gray-300 rounded-md hover:bg-gray-400">Inapoi</button>
                                        <button v-if="tyres.next_page_url" @click="handlePageChange(tyres.next_page_url)" class="px-4 py-2 bg-gray-300 rounded-md hover:bg-gray-400">Urmatoarea pagina</button>
                                    </div>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                    </div>
                </div>
            </div>
        </div>

        <div v-if="showModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity">
            <!-- Modal content -->
            <div class="fixed inset-0 z-10 overflow-y-auto">
                <!-- Modal inner content -->
                <div class="flex items-end sm:items-center justify-center min-h-full p-4 text-center sm:p-0">
                    <div class="relative bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:max-w-lg sm:w-full">
                        <!-- Form fields with provided styling -->
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                    <h3 class="text-lg leading-6 font-medium text-gray-900">Completati datele locului unde sunt depozitate anvelopele:</h3>
                                    <div class="mt-2">
                                        <div class="mb-4">
                                            <label for="row" class="block text-sm font-medium text-gray-700">Rand*</label>
                                            <input type="number" id="row" v-model="storageForm.row" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                                        </div>
                                        <div class="mb-4">
                                            <label for="column" class="block text-sm font-medium text-gray-700">Coloana</label>
                                            <input type="number" id="column" v-model="storageForm.column" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                        </div>
                                        <div class="mb-4">
                                            <label for="shelf" class="block text-sm font-medium text-gray-700">Raft</label>
                                            <input type="number" id="shelf" v-model="storageForm.shelf" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                        </div>
                                        <div class="mb-4">
                                            <label for="observations" class="block text-sm font-medium text-gray-700">Observatii</label>
                                            <textarea id="observations" v-model="storageForm.observations" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button @click="submitStorageForm" :disabled="isLoading" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                                Adauga
                            </button>
                            <button @click="showModal = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                Inchide
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div v-if="showEditModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity">
            <!-- Modal content -->
            <div class="fixed inset-0 z-10 overflow-y-auto">
                <!-- Modal inner content -->
                <div class="flex items-end sm:items-center justify-center min-h-full p-4 text-center sm:p-0">
                    <div class="relative bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:max-w-lg sm:w-full">
                        <!-- Form fields with provided styling -->
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">Editare date de depozitare</h3>
                            <div class="mt-2">
                                <div class="mb-4">
                                    <label for="editRow" class="block text-sm font-medium text-gray-700">Rand</label>
                                    <input type="number" id="editRow" v-model="editForm.row" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                </div>
                                <div class="mb-4">
                                    <label for="editColumn" class="block text-sm font-medium text-gray-700">Coloana</label>
                                    <input type="number" id="editColumn" v-model="editForm.column" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                </div>
                                <div class="mb-4">
                                    <label for="editShelf" class="block text-sm font-medium text-gray-700">Raft</label>
                                    <input type="number" id="editShelf" v-model="editForm.shelf" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                </div>
                                <div class="mb-4">
                                    <label for="editObservations" class="block text-sm font-medium text-gray-700">Observatii</label>
                                    <textarea id="editObservations" v-model="editForm.observations" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button @click="submitEditForm" :disabled="isLoading" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                                Editeaza
                            </button>
                            <button @click="showEditModal = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                Inchide
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div v-if="showCheckoutModal" class="fixed inset-0 bg-gray-500 bg-opacity-75">
            <div class="flex items-center justify-center min-h-screen">
                <div class="bg-white rounded p-4 max-w-sm m-auto">
                    <div class="text-center">
                        <p class="mb-4">Doriti sa faceti checkout pentru clientul {{ selectedTyreForCheckout.client.name }}?</p>
                        <button
                            @click="checkoutTyre" :disabled="isLoading"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Checkout
                        </button>
                        <button
                            @click="showCheckoutModal = false"
                            class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded ml-2">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div v-if="showEditTyreModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity">
            <!-- Modal content -->
            <div class="fixed inset-0 z-10 overflow-y-auto">
                <!-- Modal inner content -->
                <div class="flex items-end sm:items-center justify-center min-h-full p-4 text-center sm:p-0">
                    <div class="relative bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:max-w-lg sm:w-full">
                        <!-- Form fields with provided styling -->
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="sm:items-start">
                                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                    <h3 class="text-lg leading-6 font-medium text-gray-900">Modifica date Anvelope</h3>
                                    <div class="mt-2">
                                        <!-- Add your form fields here -->
                                        <div class="mb-4">
                                            <label for="editRow" class="block text-sm font-medium text-gray-700">Numar Masina</label>
                                            <input type="text" id="editRow" v-model="editTyreForm.car_number" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                        </div>
                                        <div class="mb-4">
                                            <label for="editRow" class="block text-sm font-medium text-gray-700">Model</label>
                                            <input type="text" id="editRow" v-model="editTyreForm.model" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                        </div>
                                        <div class="mb-4">
                                            <label for="editColumn" class="block text-sm font-medium text-gray-700">Dimensiuni</label>
                                            <input type="text" id="editColumn" v-model="editTyreForm.size" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                        </div>
                                        <div class="mb-4">
                                            <label for="editShelf" class="block text-sm font-medium text-gray-700">Observatii</label>
                                            <textarea id="editShelf" v-model="editTyreForm.observations" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"></textarea>
                                        </div>
                                        <div class="mb-4">
                                            <label for="editShelf" class="block text-sm font-medium text-gray-700">Cantitate</label>
                                            <input type="number" id="editShelf" v-model="editTyreForm.quantity" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                        </div>
                                        <div class="mb-4">
                                            <label for="has_rim" class="flex items-center">
                                                <input type="checkbox" id="has_rim" v-model="editTyreForm.hasRim" class="mr-2">
                                                <span class="text-sm font-medium text-gray-700">Cu Janta?</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button @click="submitEditTyreForm(editTyreForm.id)" :disabled="isLoading" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                                Save
                            </button>
                            <button @click="showEditTyreModal = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                Cancel
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </AuthenticatedLayout>
</template>
