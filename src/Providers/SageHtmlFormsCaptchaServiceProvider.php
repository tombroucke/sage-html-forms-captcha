<?php

namespace Otomaties\SageHtmlFormsCaptcha\Providers;

use Illuminate\Support\ServiceProvider;
use Otomaties\SageHtmlFormsCaptcha\HtmlFormsCaptcha;
use Otomaties\SageHtmlFormsCaptcha\HtmlFormsHcaptcha;
use Otomaties\SageHtmlFormsCaptcha\HtmlFormsGoogleRecaptcha;

class SageHtmlFormsCaptchaServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../../config/html-forms-captcha.php',
            'html-forms-captcha'
        );

        $this->app->bind(HtmlFormsCaptcha::class, function ($app) {
            switch (config('html-forms-captcha.captchaType')) {
                case 'recaptcha':
                    return new HtmlFormsGoogleRecaptcha($app, [
                        'siteKey' => $this->findVariable('RECAPTCHA_SITE_KEY'),
                        'secretKey' => $this->findVariable('RECAPTCHA_SECRET_KEY'),
                    ]);
                case 'hcaptcha':
                default:
                    return new HtmlFormsHcaptcha($app, [
                        'siteKey' => $this->findVariable('HCAPTCHA_SITE_KEY'),
                        'secretKey' => $this->findVariable('HCAPTCHA_SECRET_KEY'),
                    ]);
            }
        });
    }

    /**
     * Find a variable in the $_SERVER superglobal or in the constants.
     *
     * @param string $name
     * @return string|null
     */
    private function findVariable(string $name)
    {
        if (isset($_SERVER[$name])) {
            return $_SERVER[$name];
        }
        if (defined($name)) {
            return constant($name);
        }
        return null;
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../../config/html-forms-captcha.php' => $this->app->configPath('html-forms-captcha.php'),
        ], 'config');

        $configErrors = $this->app->make(HtmlFormsCaptcha::class)->validateConfig();
        if (empty($configErrors)) {
            add_filter('hf_validate_form_request_size', '__return_false' );
            add_filter('hf_validate_form', [$this->app->make(HtmlFormsCaptcha::class), 'validate'], 10, 3);
            add_filter('hf_form_message_invalid_captcha', [$this->app->make(HtmlFormsCaptcha::class), 'invalidCaptchaNotice']);
            add_filter('hf_form_html', [$this->app->make(HtmlFormsCaptcha::class), 'insertCaptcha'], 10, 2);

            add_filter('hf_form_message_could_not_validate_captcha', function() {
                return config('html-forms-captcha.strings.could_not_validate_captcha');
            });
        } else {
            add_filter('hf_form_html', [$this->app->make(HtmlFormsCaptcha::class), 'configurationErrorsNotice'], 10, 2);
            add_action('admin_notices', function() use ($configErrors) {
                if (empty($configErrors)) {
                    return;
                }
                $notice = '<div class="notice notice-error">';
                $notice .= sprintf('<p>%s</p>', config('html-forms-captcha.strings.configuration_errors'));
                $notice .= '<ol>';
                foreach ($configErrors as $error) {
                    $notice .= '<li>' . $error . '</li>';
                }
                $notice .= '</ol>';
                $notice .= '</div>';
                echo $notice;
            });
        }

    }
}
