<?php

return [

    'captchaType' => 'hcaptcha',

    'hCaptchaSettings' => [
        'theme' => 'light',
        'size' => 'normal',
    ],

    'strings' => [
        'recaptcha' => [
            'site_key_not_set' => '<code>RECAPTCHA_SITE_KEY</code> is not set.',
            'secret_key_not_set' => '<code>RECAPTCHA_SECRET_KEY</code> is not set.',
            'missing_configuration' => 'reCAPTCHA has been disabled due to missing configuration. See admin notices for more information.',
            'invalid_captcha' => 'Invalid reCAPTCHA response. Did you check the "I am human" box?',
        ],
        'hcaptcha' => [
            'site_key_not_set' => '<code>HCAPTCHA_SITE_KEY</code> is not set.',
            'secret_key_not_set' => '<code>HCAPTCHA_SECRET_KEY</code> is not set.',
            'missing_configuration' => 'hCaptcha has been disabled due to missing configuration. See admin notices for more information.',
            'invalid_captcha' => 'Invalid hCaptcha response. Did you check the "I am human" box?',
        ],
        'configuration_errors' => 'Sage HTML Forms Captcha configuration errors:',
        'could_not_validate_captcha' => 'Error: Could not validate captcha. Please contact us through other means.',
    ],

];
