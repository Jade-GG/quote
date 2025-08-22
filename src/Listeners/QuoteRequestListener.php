<?php

namespace Rapidez\Quote\Listeners;

use Barryvdh\DomPDF\Facade\Pdf;
use Statamic\Events\FormSubmitted;

class QuoteRequestListener
{
    public function handle(FormSubmitted $event)
    {
        if ($event->submission->form()->handle() !== 'quote_form') {
            return;
        }

        $products = $event->submission->augmentedValue('products')->value();

        return Pdf::loadView('exports.quote', [
            'products' => $products,
            'formData' => $event->submission->toArray(),
        ]);
    }
}