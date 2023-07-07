<template>
    <LoadingCard
        :loading="loading"
        :id="card.uriKey"
        class="text-gray-500 py-4 px-6 bg-white relative"
        :class="[card.classes]">
        <h3 class="h-6 flex mb-3 text-sm font-bold"
            v-if="card.heading?.length !== 0"
        >
            {{ card.heading?.left }}
            <span
                v-if="card.heading?.right"
                class="ml-auto font-semibold text-gray-400 text-xs"
            >
                 {{ card.heading?.right }}
            </span>
        </h3>

        <div class="relative">
            <div
                v-if="items.length === 0"
                class="text-base flex flex-col items-center"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="65"
                    height="51"
                    viewBox="0 0 65 51"
                    class="mb-3"
                >
                    <g id="Page-1" fill="none" fill-rule="evenodd">
                        <g
                            id="05-blank-state"
                            fill="#A8B9C5"
                            fill-rule="nonzero"
                            transform="translate(-779 -695)"
                        >
                            <path
                                id="Combined-Shape"
                                d="M835 735h2c.552285 0 1 .447715 1 1s-.447715 1-1 1h-2v2c0 .552285-.447715 1-1 1s-1-.447715-1-1v-2h-2c-.552285 0-1-.447715-1-1s.447715-1 1-1h2v-2c0-.552285.447715-1 1-1s1 .447715 1 1v2zm-5.364125-8H817v8h7.049375c.350333-3.528515 2.534789-6.517471 5.5865-8zm-5.5865 10H785c-3.313708 0-6-2.686292-6-6v-30c0-3.313708 2.686292-6 6-6h44c3.313708 0 6 2.686292 6 6v25.049375c5.053323.501725 9 4.765277 9 9.950625 0 5.522847-4.477153 10-10 10-5.185348 0-9.4489-3.946677-9.950625-9zM799 725h16v-8h-16v8zm0 2v8h16v-8h-16zm34-2v-8h-16v8h16zm-52 0h16v-8h-16v8zm0 2v4c0 2.209139 1.790861 4 4 4h12v-8h-16zm18-12h16v-8h-16v8zm34 0v-8h-16v8h16zm-52 0h16v-8h-16v8zm52-10v-4c0-2.209139-1.790861-4-4-4h-44c-2.209139 0-4 1.790861-4 4v4h52zm1 39c4.418278 0 8-3.581722 8-8s-3.581722-8-8-8-8 3.581722-8 8 3.581722 8 8 8z"
                            ></path>
                        </g>
                    </g>
                </svg>
                <p>{{ __('No Results') }}</p>
            </div>
            <div
                v-else
                class="overflow-x-auto"
                :class="[(card.noMaxHeight?'':'max-h-[90px]')]"
            >
                <Link
                    :href="item.url"
                    v-for="item in items"
                    :key="item.id"
                    class="cursor-pointer block no-underline hover:bg-gray-100"
                >
                    <div class="flex py-1">
                        <div
                            :class="[(item.value !== undefined)?'grow pr-4':'w-full']"
                        >
                            <p class="truncate no-underline text-sm">
                                {{ item.title }}
                            </p>
                            <p
                                class="text-xs"
                                v-if="item.timestamp"
                            >
                                {{ item.timestamp }}
                            </p>
                        </div>
                        <div
                            v-if="item.value !== undefined"
                            class="truncate text-xs pr-2"
                        >
                            {{ item.value }}
                        </div>
                    </div>
                </Link>
            </div>
        </div>
    </LoadingCard>
</template>

<script>
export default {
    props: ["card"],
    data() {
        return {
            items: [],
            loading: true
        };
    },
    mounted() {
        Nova.request()
            .get(this.card.url)
            .then(data => {
                this.items = data.data;
                this.loading = false;
            })
            .catch(() => {
                Nova.error(__('List request failed!'))
            });
    },
};
</script>
