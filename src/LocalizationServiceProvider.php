<?php

namespace Services\Localization;

use Illuminate\Support\ServiceProvider;
use Services\Localization\Middleware;
use Services\Localization\LanguageRepository;
use Services\Localization\NegotiatorInterface;
use Services\Localization\LanguageNegotiator;

class LocalizationServiceProvider extends ServiceProvider
{



    /**
     * Register service.
     * @return void
     */
    public function register()
    {        
        $default_language = $this->app->config->get('default_language'); 
        $available_languages = $this->app->config->get('available_languages');
        $supported_languages = $repository->filter($this->available);

        $repository = new LanguageRepository;

        $this->app->bind(NegotiatorInterface::class, function() use ($default_language,$supported_languages) {
        	return new LanguageNegotiator($default_language, $supported_languages);
        });

        $this->app->middleware(Middleware::class);
        
    }

    /**
     * Perform post-registration booting of services.
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            dirname(__DIR__).'/config/lang.php' => config_path('lang.php'),
        ]);
    }
}
