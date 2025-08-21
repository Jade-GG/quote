<?php

namespace Rapidez\Quote\Listeners;

use Illuminate\Support\Arr;
use Statamic\Events\FormSubmitted;

class QuoteFormListener
{
    public function handle(FormSubmitted $event): void
    {
        if (($event->submission?->form()?->handle() ?? null) !== 'quote_form') {
            return;
        }

        $products = $event->submission->get('products');

        if (!$products || !json_validate($products)) {
            return;
        }

        $products = collect(json_decode($products, true));
        
        $productModel = config('rapidez.models.product');
        /** @var \Rapidez\Core\Models\Product $productInstance */
        $productInstance = new $productModel;
        $dbProducts = $productModel::selectForProductPage()
            ->whereIn($productInstance->qualifyColumn('sku'), $products->map(fn($product) => $product['sku']))
            ->get()
            ->keyBy('sku');

        $products = $products->map(function($product) use ($dbProducts) {
            $dbProduct = $dbProducts[$product['sku']] ?? null;
            $productOptions = collect($product['options'] ?? [])->mapWithKeys(function (string $optionValue, string $option) use ($dbProduct): array {
                $option = Arr::firstOrFail($dbProduct->options, fn ($productOption) => $productOption->option_id == $option);
                $value = Arr::firstOrFail($option->values, fn ($value) => $value->option_type_id == $optionValue);

                return [
                    'title' => $option->title,
                    'price' => ($value?->price ?? 0) + ($option->price ?? 0),
                    'value' => $value,
                ];
            });

            return [
                ...$product,
                'product' => $dbProduct,
                'options' => $productOptions,
            ];
        });
        
        dd($products);
    }
}