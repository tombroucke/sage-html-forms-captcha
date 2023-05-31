<?php
namespace Otomaties\SageHtmlFormsCaptcha\Abstracts;

abstract class Captcha {
    protected function insertBeforeSubmitButton($html, $insert) {
        $dom = new \DOMDocument('1.0', 'UTF-8');
        $dom->loadHTML($html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        $xpath = new \DOMXPath($dom);
        $buttons = $xpath->query('//button[@type="submit"]');
        if ($buttons->length === 0) {
            $buttons = $xpath->query('//input[@type="submit"]');
        }
        if ($buttons->length === 0) {
            return $html;
        }
        $button = $buttons->item(0);
        $insertDom = new \DOMDocument('1.0', 'UTF-8');
        $insertDom->loadHTML($insert, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        $insertNode = $dom->importNode($insertDom->documentElement, true);
        $button->parentNode->insertBefore($insertNode, $button);

        return $dom->saveHTML();
    }
}
