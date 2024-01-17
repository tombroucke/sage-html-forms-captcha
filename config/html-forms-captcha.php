<?php

return [

    'captchaType' => 'hcaptcha',

    'hCaptchaSettings' => [
        'theme' => 'light',
        'size' => 'normal',
    ],

    'strings' => [
        'recaptcha' => [
            'site_key_not_set' => __('<code>RECAPTCHA_SITE_KEY</code> is not set.', 'sage'),
            'secret_key_not_set' => __('<code>RECAPTCHA_SECRET_KEY</code> is not set.', 'sage'),
            'missing_configuration' => __('reCAPTCHA has been disabled due to missing configuration. See admin notices for more information.', 'sage'),
            'invalid_captcha' => __('Invalid reCAPTCHA response. Did you check the "I am human" box?', 'sage'),
        ],
        'hcaptcha' => [
            'site_key_not_set' => __('<code>HCAPTCHA_SITE_KEY</code> is not set.', 'sage'),
            'secret_key_not_set' => __('<code>HCAPTCHA_SECRET_KEY</code> is not set.', 'sage'),
            'missing_configuration' => __('hCaptcha has been disabled due to missing configuration. See admin notices for more information.', 'sage'),
            'invalid_captcha' => __('Invalid hCaptcha response. Did you check the "I am human" box?', 'sage'),
        ],
        'configuration_errors' => __('Sage HTML Forms Captcha configuration errors:', 'sage'),
        'could_not_validate_captcha' => __('Error: Could not validate captcha. Please contact us through other means.', 'sage'),
    ],

];
