<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import Pagination from "@/Components/Pagination.vue";
import { Head, useForm } from "@inertiajs/vue3";

const props = defineProps({
    products: Object,
    filters: Array,
});

const cartsForm = useForm({
    product_id: "",
    quantity: "",
});

const addToCart = (record) => {
    cartsForm.product_id = record.id;

    cartsForm.post(route("carts.store"), {
        preserveScroll: false,
        preserveState: false,
        onSuccess: () => {
            console.log("success");
        },
    });
};
</script>

<template>
    <Head title="Products" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Products
            </h2>
        </template>
        <!-- <div class="absolute top-0 right-0 mt-12 mr-4">
            <div
                id="toast-success"
                class="flex items-center w-full max-w-sm p-4 mb-4 text-gray-500 bg-white rounded-lg shadow"
                role="alert"
            >
                <div
                    class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-green-500 bg-green-100 rounded-lg"
                >
                    <svg
                        class="w-5 h-5"
                        aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg"
                        fill="currentColor"
                        viewBox="0 0 20 20"
                    >
                        <path
                            d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z"
                        ></path>
                    </svg>
                    <span class="sr-only">Check icon</span>
                </div>
                <div class="ms-3 text-sm font-normal">
                    Item moved successfully.
                </div>
                <button
                    type="button"
                    class="-mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex items-center justify-center h-8 w-8"
                    data-dismiss-target="#toast-success"
                    aria-label="Close"
                >
                    <span class="sr-only">Close</span>
                    <svg
                        class="w-3 h-3"
                        aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 14 14"
                    >
                        <path
                            stroke="currentColor"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"
                        ></path>
                    </svg>
                </button>
            </div>
        </div> -->
        <!-- <div
            id="toast-danger"
            class="flex items-center w-full max-w-xs p-4 mb-4 text-gray-500 bg-white rounded-lg shadow dark:text-gray-400 dark:bg-gray-800"
            role="alert"
        >
            <div
                class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-red-500 bg-red-100 rounded-lg dark:bg-red-800 dark:text-red-200"
            >
                <svg
                    class="w-5 h-5"
                    aria-hidden="true"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="currentColor"
                    viewBox="0 0 20 20"
                >
                    <path
                        d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 11.793a1 1 0 1 1-1.414 1.414L10 11.414l-2.293 2.293a1 1 0 0 1-1.414-1.414L8.586 10 6.293 7.707a1 1 0 0 1 1.414-1.414L10 8.586l2.293-2.293a1 1 0 0 1 1.414 1.414L11.414 10l2.293 2.293Z"
                    />
                </svg>
                <span class="sr-only">Error icon</span>
            </div>
            <div class="ms-3 text-sm font-normal">Item has been deleted.</div>
            <button
                type="button"
                class="ms-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex items-center justify-center h-8 w-8 dark:text-gray-500 dark:hover:text-white dark:bg-gray-800 dark:hover:bg-gray-700"
                data-dismiss-target="#toast-danger"
                aria-label="Close"
            >
                <span class="sr-only">Close</span>
                <svg
                    class="w-3 h-3"
                    aria-hidden="true"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 14 14"
                >
                    <path
                        stroke="currentColor"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"
                    />
                </svg>
            </button>
        </div>
        <div
            id="toast-warning"
            class="flex items-center w-full max-w-xs p-4 text-gray-500 bg-white rounded-lg shadow dark:text-gray-400 dark:bg-gray-800"
            role="alert"
        >
            <div
                class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-orange-500 bg-orange-100 rounded-lg dark:bg-orange-700 dark:text-orange-200"
            >
                <svg
                    class="w-5 h-5"
                    aria-hidden="true"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="currentColor"
                    viewBox="0 0 20 20"
                >
                    <path
                        d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM10 15a1 1 0 1 1 0-2 1 1 0 0 1 0 2Zm1-4a1 1 0 0 1-2 0V6a1 1 0 0 1 2 0v5Z"
                    />
                </svg>
                <span class="sr-only">Warning icon</span>
            </div>
            <div class="ms-3 text-sm font-normal">
                Improve password difficulty.
            </div>
            <button
                type="button"
                class="ms-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex items-center justify-center h-8 w-8 dark:text-gray-500 dark:hover:text-white dark:bg-gray-800 dark:hover:bg-gray-700"
                data-dismiss-target="#toast-warning"
                aria-label="Close"
            >
                <span class="sr-only">Close</span>
                <svg
                    class="w-3 h-3"
                    aria-hidden="true"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 14 14"
                >
                    <path
                        stroke="currentColor"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"
                    />
                </svg>
            </button>
        </div> -->
        <div class="py-10">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="grid grid-cols-4 gap-4">
                    <div
                        v-for="product in props.products.data"
                        :key="product.id"
                    >
                        <div
                            class="w-full max-w-sm bg-white border border-gray-200 rounded-lg shadow"
                        >
                            <a href="#">
                                <img
                                    class="p-8 rounded-t-lg"
                                    :src="product.image.image_url"
                                    alt="product image"
                                />
                            </a>
                            <div class="px-5 pb-5">
                                <a href="#">
                                    <h5
                                        class="text-xl font-semibold tracking-tight text-gray-900"
                                    >
                                        {{ product.name }}
                                    </h5>
                                </a>

                                <div class="flex items-center justify-between">
                                    <span
                                        class="text-3xl font-bold text-gray-900"
                                    >
                                        ₱ {{ product.price }}</span
                                    >
                                    <button
                                        @click="addToCart(product)"
                                        class="text-white bg-amber-500 hover:bg-amber-400 focus:ring-4 focus:outline-none focus:ring-amber-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center"
                                    >
                                        Add to cart
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <Pagination />
            </div>
        </div>
    </AuthenticatedLayout>
</template>
