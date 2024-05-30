<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

const props = defineProps({
    'order': Object
})
</script>

<template>
    <Head title="Order Items" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Order Items</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">Order</div>
                    <div class="p-6 text-gray-900">
                        <form class="max-w-sm">
                            <div class="mb-5">
                                <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Order Number</label>
                                <input :value="props.order?.order_number" disabled class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="name@flowbite.com" required />
                            </div>
                            <div class="mb-5">
                                <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Total Amount</label>
                                <input :value="props.order?.total_amount" disabled class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required />
                            </div>
                            <div class="mb-5">
                                <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Status</label>
                                <input :value="props.order?.status" disabled class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required />
                            </div>
                            </form>
                    </div>
                </div>
            </div>
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-4">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3">
                                        Image
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Name
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Unit Price
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Quantity
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Total Amount
                                    </th>
                                    <!-- <th scope="col" class="px-6 py-3">
                                        Action
                                    </th> -->
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="item in props.order?.order_items" class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        <img
                                                class="p-8 rounded-t-lg w-36"
                                                :src="
                                                    item?.product?.image
                                                        ?.image_url
                                                "
                                                alt="product image"
                                            />
                                    </th>
                                    <td class="px-6 py-4">
                                        {{ item.product?.name }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ item.product?.price }} 
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ item.quantity }} 
                                    </td>
                                    <td class="px-6 py-4">
                                        ₱ {{ item.total_amount }} 
                                    </td>
                                    <!-- <td class="px-6 py-4">
                                        <button type="button" class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">Remove</button>
                                    </td> -->
                                </tr>
                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
