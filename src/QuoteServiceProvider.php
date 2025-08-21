<?php

namespace Rapidez\Quote;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Rapidez\Quote\Listeners\QuoteFormListener;
use Statamic\Events\FormSubmitted;
use Statamic\Facades\Blueprint;

class QuoteServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this
            ->bootBlueprints()
            ->bootListeners()
            ->bootPublishables()
            ->bootRoutes()
            ->bootTranslations()
            ->bootViews();
    }

    protected function bootBlueprints(): static
    {
        Blueprint::addNamespace('rapidez-quote', __DIR__ . '/../resources/blueprints');

        return $this;
    }

    protected function bootListeners(): static
    {
        Event::listen(FormSubmitted::class, QuoteFormListener::class);

        return $this;
    }

    protected function bootPublishables(): static
    {
        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('vendor/rapidez-quote'),
        ], 'quote-views');

        $this->publishes([
            __DIR__.'/../resources/blueprints/forms' => resource_path('blueprints/forms'),
        ], 'quote-blueprints');

        return $this;
    }

    protected function bootRoutes(): static
    {
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');

        return $this;
    }

    protected function bootTranslations(): static
    {
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'rapidez-quote');

        return $this;
    }

    protected function bootViews(): static
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'rapidez-quote');

        return $this;
    }
}