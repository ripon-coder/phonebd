<?php

namespace App\Support\ResponseCache\Replacers;

use Spatie\ResponseCache\Replacers\Replacer;
use Symfony\Component\HttpFoundation\Response;

class AdReplacer implements Replacer
{
    protected string $squareAdPlaceholder = '<!-- RESPONSE_CACHE_SQUARE_AD -->';
    protected string $verticalAdPlaceholder = '<!-- RESPONSE_CACHE_VERTICAL_AD -->';
    protected string $horizontalAdPlaceholder = '<!-- RESPONSE_CACHE_HORIZONTAL_AD -->';

    public function prepareResponseToCache(Response $response): void
    {
        if (! $response->getContent()) {
            return;
        }

        $content = $response->getContent();

        $content = $this->replaceMarkerWithPlaceholder($content, '<!-- SQUARE_AD_START -->', '<!-- SQUARE_AD_END -->', $this->squareAdPlaceholder);
        $content = $this->replaceMarkerWithPlaceholder($content, '<!-- VERTICAL_AD_START -->', '<!-- VERTICAL_AD_END -->', $this->verticalAdPlaceholder);
        $content = $this->replaceMarkerWithPlaceholder($content, '<!-- HORIZONTAL_AD_START -->', '<!-- HORIZONTAL_AD_END -->', $this->horizontalAdPlaceholder);

        $response->setContent($content);
    }

    protected function replaceMarkerWithPlaceholder(string $content, string $startMarker, string $endMarker, string $placeholder): string
    {
        $pattern = '/' . preg_quote($startMarker, '/') . '.*?' . preg_quote($endMarker, '/') . '/s';
        return preg_replace($pattern, $placeholder, $content);
    }

    public function replaceInCachedResponse(Response $response): void
    {
        if (! $response->getContent()) {
            return;
        }

        $content = $response->getContent();

        if (str_contains($content, $this->squareAdPlaceholder)) {
            $content = str_replace($this->squareAdPlaceholder, $this->getSquareAdContent(), $content);
        }

        if (str_contains($content, $this->verticalAdPlaceholder)) {
            $content = str_replace($this->verticalAdPlaceholder, $this->getVerticalAdContent(), $content);
        }

        if (str_contains($content, $this->horizontalAdPlaceholder)) {
            $content = str_replace($this->horizontalAdPlaceholder, $this->getHorizontalAdContent(), $content);
        }

        $response->setContent($content);
    }

    protected function getSquareAdContent(): string
    {
        return view('components.ad.square-ad')->render();
    }

    protected function getVerticalAdContent(): string
    {
        return view('components.ad.vertical-ad')->render();
    }

    protected function getHorizontalAdContent(): string
    {
        return view('components.ad.horizontal-ad')->render();
    }
}
