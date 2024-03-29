<?php
namespace Otomaties\SageHtmlFormsCaptcha;

use Otomaties\SageHtmlFormsCaptcha\Abstracts\Captcha;
use Otomaties\SageHtmlFormsCaptcha\Contracts\CaptchaContract;

class HtmlFormsHcaptcha extends Captcha implements CaptchaContract {

    private $app;
    private $config;

    public function __construct($app, $config)
    {
        $this->app = $app;
        $this->config = $config;
    }

    public function validate($error, $form, $data)
    {
        try {
            $response = wp_remote_post('https://hcaptcha.com/siteverify', [
                'body' => [
                    'secret' => $this->config['secretKey'],
                    'response' => $data['h-captcha-response'] ?? '',
                    'remoteip' => $_SERVER['REMOTE_ADDR']
                ]
            ]);
            $body = json_decode(wp_remote_retrieve_body($response));
            if (! $body->success) {
                return 'invalid_captcha';
            }
        } catch (\Exception $e) {
            return 'could_not_validate_captcha';
        }
        return $error;
    }

    public function invalidCaptchaNotice()
    {
        return config('html-forms-captcha.strings.hcaptcha.invalid_captcha');
    }

    public function insertCaptcha($html, $form)
    {
        $recaptchaCode = sprintf(
            '<div class="h-captcha" data-theme="%s" data-size="%s" data-sitekey="%s"></div>',
            config('html-forms-captcha.hCaptchaSettings.theme'),
            config('html-forms-captcha.hCaptchaSettings.size'),
            $this->config['siteKey']
        );
        $html = $this->insertBeforeSubmitButton($html, $recaptchaCode);
        if (function_exists('wp_enqueue_script')) {
            $queryArgs = [
                'hl' => substr(config('app.locale'), 0, 2),
            ];
			wp_enqueue_script('hcaptcha', add_query_arg($queryArgs, '//js.hcaptcha.com/1/api.js'));
		}
        return $html;
    }

    public function validateConfig()
    {
        $errors = [];
        if (! $this->config['siteKey']) {
            $errors[] = config('html-forms-captcha.strings.hcaptcha.site_key_not_set');
        }
        if (! $this->config['secretKey']) {
            $errors[] = config('html-forms-captcha.strings.hcaptcha.secret_key_not_set');
        }
        return $errors;
    }

    public function configurationErrorsNotice($html, $form) {
        if (! current_user_can('manage_options')) {
            return $html;
        }
        $notice = sprintf('<p>%s</p>', config('html-forms-captcha.strings.hcaptcha.missing_configuration'));
        $html = $this->insertBeforeSubmitButton($html, $notice);
        return $html;
    }
}
