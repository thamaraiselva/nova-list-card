<template>
    <LoadingCard
        :loading="loading"
        :id="card.uri_key"
        class="text-gray-500 py-4 px-6 bg-white relative"
        :class="[card.classes]">
        <h3 class="h-6 flex mb-3 text-sm font-bold"
            v-if="card.heading.length !== 0"
        >
            {{ card.heading.left }}
            <span class="ml-auto font-semibold text-gray-400 text-xs"
                  v-if="card.heading.right">
                 {{ card.heading.right }}
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
                :class="[maxHeight]"
            >
                <Link
                    :href="$url(`/resources/${item.resourceName}/${item.resourceId}`)"
                    v-for="(item, index) in items"
                    :key="item.id"
                    class="cursor-pointer block no-underline hover:bg-gray-100"
                >
                    <div class="flex py-1">
                        <div
                            :class="{'w-full': card.value_column == null, 'grow pr-4': card.value_column != null}"
                        >

                            <p class="truncate no-underline text-sm">
                                {{ item.title }}
                            </p>

                            <p
                                class="text-xs"
                                v-if="card.timestamp_enabled"
                            >
                                {{ timestampValue(item.resource[card.timestamp_column], card.timestamp_format) }}
                            </p>

                            <p
                                class="text-80"
                                v-if="item.aggregate && card.relationship + '_' + card.aggregate !== card.value_column"
                            >
                                {{ item.aggregate }}&nbsp;{{ card.relationship }}
                            </p>

                        </div>
                        <div
                            v-if="card.value_column != null"
                            class="truncate text-xs pr-2"
                        >
                            {{ formatValue(item, card.value_format) }}
                        </div>

                    </div>
                </Link>
            </div>
        </div>
    </LoadingCard>
</template>

<script>
import numerial from "numeral";
import moment from 'moment'

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
            .get(this.endpoint)
            .then(data => {
                this.items = data.data;
                this.loading = false;
            })
            .catch(() => {
                this.$toasted.show('List request failed!', {type: 'error'})
            });
    },
    methods: {
        formatValue(item, format) {
            if (this.card.value_format == null) {
                return this.value(item, format);
            }
            if (this.card.value_formatter === "numerial") {
                return this.numerialValue(item, format);
            }
            if (this.card.value_formatter === "timestamp") {
                return this.timestampValue(
                    item.resource[this.card.value_column],
                    format
                );
            }
        },
        timestampValue(value, format) {
            let timestamp = moment(value);

            if (format !== "relative") {
                return timestamp.format(format);
            } else {
                return timestamp.fromNow();
            }
        },
        numerialValue(item) {
            return numerial(item.resource[this.card.value_column]).format(
                this.card.value_format
            );
        },
        value(item) {
            return item.resource[this.card.value_column];
        }
    },
    computed: {
        maxHeight() {
            return this.card.no_max_height?'':'max-h-[90px]';
        },
        endpoint() {
            let endpoint = "/nova-vendor/nova-list-card/" + this.card.uri_key + "/";

            if (this.card.relationship) {
                endpoint += this.card.aggregate + "/" + this.card.relationship + "/";
            }

            if (this.card.aggregate_column) {
                endpoint += this.card.aggregate_column + "/";
            }

            return (
                (endpoint +=
                    "?order_by=" +
                    this.card.order_column +
                    "&direction=" +
                    this.card.order_direction +
                    "&limit=" +
                    this.card.limit) +
                "&nova-list-card=" +
                this.card.uri_key
            );
        }
    }
};
</script>
