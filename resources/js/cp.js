import ProductsFieldtype from "./components/fieldtypes/Products.vue"

Statamic.booting(() => {
    Statamic.component('products-fieldtype', ProductsFieldtype)
})