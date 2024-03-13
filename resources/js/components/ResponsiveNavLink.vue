<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    href: {
        type: String,
        required: true,
    },
    active: {
        type: Boolean,
    },
});

const classes = computed(() =>
    props.active
        ? 'block w-full rounded-lg ps-3 pe-4 py-2 border-l-4 border-blue-500 font-medium text-black-700 bg-blue-100 focus:outline-none focus:text-black-800 focus:bg-blue-200 transition duration-150 ease-in-out'
        : 'block w-full rounded-lg ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-black hover:text-blue-300 hover:bg-blue-600 focus:outline-none focus:text-blue-300 focus:bg-blue-600 transition duration-150 ease-in-out'
);
</script>

<template>
    <Link :href="href" :class="classes">
        <slot />
    </Link>
</template>
