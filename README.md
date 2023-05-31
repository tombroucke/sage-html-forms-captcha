# Sage HTML Forms captcha

Easily add captcha's to [HTML Forms](https://wordpress.org/plugins/html-forms/).

## Installation

You can install this package with Composer:

```bash
composer require tombroucke/sage-html-forms-captcha
```

You can publish the config file with:

```shell
$ wp acorn vendor:publish --provider="Otomaties\SageHtmlFormsCaptcha\Providers\SageHtmlFormsCaptchaServiceProvider"
```

## Usage

### hCaptcha

Define your site key and site secret in your .env file:

```bash
HCAPTCHA_SITE_KEY='########-####-####-####-############'
HCAPTCHA_SECRET_KEY='#x########################################'
```

### reCAPTCHA (v2)

Change `'captchaType'` to `'recaptcha'` in your published config file.

Define your site key and site secret in your .env file:

```bash
RECAPTCHA_SITE_KEY='########################################'
RECAPTCHA_SECRET_KEY='########################################'
```
