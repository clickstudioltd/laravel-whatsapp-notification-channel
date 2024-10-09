<?php

namespace NotificationChannels\WhatsApp;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class WhatsAppProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/whatsapp-notification-channel.php', 'whatsapp-notification-channel');

        $this->publishes([
            __DIR__.'/../config/whatsapp-notification-channel.php' => config_path('whatsapp-notification-channel.php'),
        ]);

        $this->app->bind(WhatsAppConfig::class, function () {
            return new WhatsAppConfig($this->app['config']['whatsapp-notification-channel']);
        });

        $this->app->singleton(WhatsAppClient::class, function (Application $app) {
            /** @var WhatsAppConfig $config */
            $config = $app->make(WhatsAppConfig::class);

            $options = [];

            if ($config->getAPIKey()) {
                $options['headers'] = [
                    'X-API-Key' => $config->getAPIKey()
                ];
            }

            if ($config->getProxy()) {
                $options['proxy'] = $config->getProxy();

                if ($config->getProxyCAInfo()) {
                    $options['curl'] = [CURLOPT_PROXY_CAINFO => $config->getProxyCAInfo()];
                }
            }

            return new WhatsAppClient($options);
        });

        $this->app->singleton(WhatsApp::class, function (Application $app) {
            return new WhatsApp(
                $app->make(WhatsAppClient::class),
                $app->make(WhatsAppConfig::class)
            );
        });

        $this->app->singleton(WhatsAppChannel::class, function (Application $app) {
            return new WhatsAppChannel(
                $app->make(WhatsApp::class),
                $app->make(Dispatcher::class)
            );
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides(): array
    {
        return [
            WhatsAppConfig::class,
            WhatsAppClient::class,
            WhatsAppChannel::class,
        ];
    }
}
