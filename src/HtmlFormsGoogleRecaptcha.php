<?php
namespace Otomaties\SageHtmlFormsCaptcha;

use ReCaptcha\ReCaptcha;
use Otomaties\SageHtmlFormsCaptcha\Abstracts\Captcha;
use Otomaties\SageHtmlFormsCaptcha\Contracts\CaptchaContract;

class HtmlFormsGoogleRecaptcha extends Captcha implements CaptchaContract {

    private $app;
    private $config;

    public function __construct($app, $config)
    {
        $this->app = $app;
        $this->config = $config;
    }

    public function validate($error, $form, $data)
    {
        $recaptcha = new ReCaptcha(
            $this->config['secretKey']
        );

        $response = $recaptcha->setExpectedHostname(
            wp_parse_url(home_url(), PHP_URL_HOST)
        )->verify(
            $data['g-recaptcha-response'] ?? '',
            $_SERVER['REMOTE_ADDR']
        );

        if (! $response->isSuccess()) {
            return 'invalid_captcha';
        }

        return $error;
    }

    public function invalidCaptchaNotice()
    {
        return config('html-forms-captcha.strings.recaptcha.invalid_captcha');
    }

    public function insertCaptcha($html, $form)
    {
        $recaptchaCode = sprintf('<div class="g-recaptcha" data-sitekey="%s"></div>', $this->config['siteKey']);
        $html = $this->insertBeforeSubmitButton($html, $recaptchaCode);
        if (function_exists('wp_enqueue_script')) {
            $queryArgs = [
                'hl' => substr(config('app.locale'), 0, 2),
            ];
			wp_enqueue_script('google-recaptcha', add_query_arg($queryArgs, '//www.google.com/recaptcha/api.js'));
		}
        return $html;
    }

    public function validateConfig()
    {
        $errors = [];
        if (! $this->config['siteKey']) {
            $errors[] = config('html-forms-captcha.strings.recaptcha.site_key_not_set');
        }
        if (! $this->config['secretKey']) {
            $errors[] = config('html-forms-captcha.strings.recaptcha.secret_key_not_set');
        }
        return $errors;
    }

    public function configurationErrorsNotice($html, $form) {
        if (! current_user_can('manage_options')) {
            return $html;
        }
        $notice = sprintf('<p>%s</p>', config('html-forms-captcha.strings.recaptcha.missing_configuration'));
        $html = $this->insertBeforeSubmitButton($html, $notice);
        return $html;
    }
}
