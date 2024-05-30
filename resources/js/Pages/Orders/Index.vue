<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    'orders': Array
})
</script>

<template>
    <Head title="Orders" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Orders
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3">
                                        Order Number
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Total Amount
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Pick Up Time
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Status
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Action
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="order in props.orders" class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ order.order_number }}
                                    </th>
                                    <td class="px-6 py-4">
                                        ₱ {{ order.total_amount }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ order.time_to_pick_up }} 
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ order.status }} 
                                    </td>
                                    <td class="px-6 py-4">
                                        <Link :href="route('orders.show', order.id)" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">
                                            View Order
                                        </Link>
                                    </td>
                                </tr>
                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
