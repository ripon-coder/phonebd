<?php

namespace App\Support\ResponseCache\Replacers;

use Spatie\ResponseCache\Replacers\Replacer;
use Symfony\Component\HttpFoundation\Response;

class CsrfTokenReplacer implements Replacer
{
    protected string $replacementString = '<csrf-token-placeholder>';

    public function prepareResponseToCache(Response $response): void
    {
        if (! $response->getContent()) {
            return;
        }

        $token = csrf_token();

        if (! $token) {
            return;
        }

        $content = $response->getContent();
        
        // Check if the token actually exists in the content before trying to replace
        if (strpos($content, $token) !== false) {
            $response->setContent(str_replace(
                $token,
                $this->replacementString,
                $content
            ));
        }
    }

    public function replaceInCachedResponse(Response $response): void
    {
        // Force browser to not cache the page so that it always hits the server
        // and gets a fresh CSRF token from this replacer.
        $response->headers->set('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
        $response->headers->set('Pragma', 'no-cache');
        $response->headers->set('Expires', 'Fri, 01 Jan 1990 00:00:00 GMT');

        if (! $response->getContent()) {
            return;
        }

        $token = csrf_token() ?? '';
        
        $content = $response->getContent();
        
        if (strpos($content, $this->replacementString) !== false) {
            $response->setContent(str_replace(
                $this->replacementString,
                $token,
                $content
            ));
        }
    }
}
