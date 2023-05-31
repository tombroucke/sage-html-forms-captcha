<?php
namespace Otomaties\SageHtmlFormsCaptcha\Contracts;

interface Captcha {
    public function validate($error, $form, $data);
    public function invalidCaptchaNotice();
    public function insertCaptcha($html, $form);
    public function validateConfig();
    public function configurationErrorsNotice($html, $form);
}
