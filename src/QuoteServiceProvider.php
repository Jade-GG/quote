<?php

namespace Rapidez\Quote;

use Illuminate\Support\ServiceProvider;
use Rapidez\Quote\Fieldtypes\Products;
use Statamic\Statamic;

class QuoteServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this
            ->bootFieldtypes()
            ->bootPublishables()
            ->bootRoutes()
            ->bootTranslations()
            ->bootViews()
            ->bootVite();
    }

    protected function bootFieldtypes(): static
    {
        Products::register();

        return $this;
    }

    protected function bootPublishables(): static
    {
        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('vendor/rapidez-quote'),
        ], 'quote-views');

        $this->publishes([
            __DIR__.'/../resources/blueprints/forms' => resource_path('blueprints/forms'),
            __DIR__.'/../resources/views/form_fields' => resource_path('views/form_fields'),
        ], 'quote-content');

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

    protected function bootVite(): static
    {
        Statamic::vite('rapidez-quote', [
            'buildDirectory' => 'vendor/rapidez-quote/build',
            'input' => 'resources/js/cp.js',
        ]);

        return $this;
    }
}