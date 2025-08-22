<template>
    <div class="flex flex-col *:py-1 divide-y">
        <div v-for="item in value" class="flex gap-5 items-center">
            <div>
                <img
                    v-if="item.product.thumbnail"
                    class="object-contain h-16 w-20 shrink-0"
                    :alt="item.product.name"
                    :src="`/storage/resizes/200/magento/catalog/product${item.product.thumbnail}.webp`"
                />
            </div>
            <div class="flex flex-col">
                <span>{{ item.qty }} x {{ item.product.name }}</span>
                <template v-for="option in item.options ?? {}">
                    <span class="text-muted">{{ option }}</span>
                </template>
            </div>
            <div class="ml-auto">
                {{ price(item.totalPrice) }}
            </div>
        </div>
    </div>
</template>

<script>
export default {
    mixins: [Fieldtype],

    methods: {
        price(value, extra = {}) {
            return new Intl.NumberFormat(config.locale.replace('_', '-'), {
                style: 'currency',
                currency: config.currency,
                ...extra,
            }).format(value)
        }
    },  
}
</script>