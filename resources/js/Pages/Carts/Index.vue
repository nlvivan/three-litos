<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, useForm } from "@inertiajs/vue3";
import { computed } from "vue";

const props = defineProps({
    carts: Array,
});

const form = useForm({
    carts: props.carts,
    time_to_pick_up: "",
});

const totalAmount = computed(() => {
    let total = 0;

    form.carts.forEach((cart) => {
        total += cart?.quantity * cart?.product?.price;
    });

    return total;
});

const submitOrder = () => {
    form.post("orders", {
        preserveScroll: false,
        preserveState: false,
    });
};

console.log(props.carts);
</script>

<template>
    <Head title="Carts" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Carts
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="relative overflow-x-auto">
                        <table
                            class="w-full text-sm text-left rtl:text-right text-gray-500"
                        >
                            <thead
                                class="text-xs text-gray-700 uppercase bg-gray-50"
                            >
                                <tr>
                                    <th scope="col" class="px-6 py-3">
                                        Product
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
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    class="bg-white border-b"
                                    v-for="cart in form.carts"
                                    :key="cart.id"
                                >
                                    <th
                                        scope="row"
                                        class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap"
                                    >
                                        <div class="flex items-center">
                                            <img
                                                class="p-8 rounded-t-lg w-36"
                                                :src="
                                                    cart?.product?.image
                                                        ?.image_url
                                                "
                                                alt="product image"
                                            />
                                            <p class="font-semibold">
                                                {{ cart?.product?.name }}
                                            </p>
                                        </div>
                                    </th>
                                    <td class="px-6 py-4">
                                        ₱ {{ cart?.product?.price }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <input
                                            v-model="cart.quantity"
                                            type="number"
                                            id="number-input"
                                            aria-describedby="helper-text-explanation"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-amber-500 focus:border-amber-500 block w-1/2 p-2.5 dark:focus:ring-amber-500 dark:focus:border-amber-500"
                                        />
                                    </td>
                                    <td class="px-6 py-4">
                                        ₱
                                        {{
                                            cart?.quantity *
                                            cart?.product?.price
                                        }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="w-full mt-4">
                    <div class="flex justify-end">
                        <div
                            class="w-full max-w-sm p-4 bg-white border border-gray-200 rounded-lg shadow sm:p-8"
                        >
                            <h5 class="mb-4 text-xl font-medium text-gray-500">
                                Cart Total
                            </h5>
                            <div
                                class="flex flex-col items-baseline text-gray-900"
                            >
                                <span>Total Amount: ₱ {{ totalAmount }}</span>
                                <div class="mb-4">
                                    <span class="flex flex-col"
                                        >Pick up Time:
                                        <input
                                            v-model="form.time_to_pick_up"
                                            type="time"
                                            id="number-input"
                                            aria-describedby="helper-text-explanation"
                                            class="mt-2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-amber-500 focus:border-amber-500 block w-full p-2.5 dark:focus:ring-amber-500 dark:focus:border-amber-500"
                                    /></span>
                                </div>
                            </div>

                            <button
                                @click="submitOrder"
                                type="button"
                                class="text-white bg-amber-500 hover:bg-amber-400 focus:ring-4 focus:outline-none focus:ring-amber-200 font-medium rounded-lg text-sm px-5 py-2.5 inline-flex justify-center w-full text-center"
                            >
                                Submit Order
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
