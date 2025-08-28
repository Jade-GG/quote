<?php

namespace Rapidez\Quote;

use Rapidez\Quote\Fieldtypes\Products;
use Rapidez\Quote\Listeners\QuoteRequestListener;
use Statamic\Events\FormSubmitted;
use Statamic\Providers\AddonServiceProvider;
use Statamic\Statamic;

class QuoteServiceProvider extends AddonServiceProvider
{
    protected $vite = [
        'input' => [
            'resources/js/cp.js',
            'resources/css/cp.css',
        ],
        'buildDirectory' => 'build',
    ];

    protected $fieldtypes = [
        Products::class,
    ];

    protected $listen = [
        FormSubmitted::class => [
            QuoteRequestListener::class,
        ]
    ];

    protected $viewNamespace = 'rapidez-quote';

    public function register()
    {
        $this->registerConfigs();
    }

    protected function registerConfigs(): self
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/rapidez/quote.php', 'rapidez.quote');

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

        $this->publishes([
            __DIR__ . '/../resources/dist' => public_path('vendor/statamic-quote'),
        ], 'quote-dist');

        return $this;
    }

    protected function bootPublishAfterInstall(): static
    {
        Statamic::afterInstalled(function ($command) {
            $command->call('vendor:publish', [
                '--tag' => 'quote-dist',
                '--force' => true,
            ]);
        });

        return $this;
    }

    protected function bootTranslations(): static
    {
        $this->loadJsonTranslationsFrom(__DIR__ . '/../resources/lang');

        return $this;
    }

    protected function bootRoutes(): static
    {
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');

        return $this;
    }
}