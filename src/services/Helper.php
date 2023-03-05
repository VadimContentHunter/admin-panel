<?php

declare(strict_types=1);

namespace vadimcontenthunter\AdminPanel\services;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
class Helper
{
    /**
     * Url: https://example.com/category/page?sort=asc
     * Result: https://example.com/
     */
    public static function getCurrentHostUrl(): string
    {
        return ((!empty($_SERVER['HTTPS'])) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'];
    }

    /**
     * Url: https://example.com/category/page?sort=asc
     * Result: https://example.com/category/page
     */
    public static function getCurrentUrlWithoutParameters(): string
    {
        $url = ((!empty($_SERVER['HTTPS'])) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $url = explode('?', $url);

        return $url[0] ?? '';
    }

    /**
     * Url: https://example.com/category/page?sort=asc
     * Result: https://example.com/category/page?sort=asc
     */
    public static function getCurrentFullUrl(): string
    {
        return ((!empty($_SERVER['HTTPS'])) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    }

    /**
     * Url: https://example.com/category/page?sort=asc
     * Result: /category/page?sort=asc
     */
    public static function getCurrentMainUrlWithParameters(): string
    {
        return $_SERVER['REQUEST_URI'];
    }

    /**
     * Url: https://example.com/category/page?sort=asc
     * Result: /category/page
     */
    public static function getCurrentMainUrl(): string
    {
        $url = $_SERVER['REQUEST_URI'];
        $url = explode('?', $url);

        return $url[0] ?? '';
    }

    /**
     * Url: https://example.com/category/page?sort=asc
     * Result: sort=asc
     */
    public static function getParametersCurrentUrl(): string
    {
        return $_SERVER['QUERY_STRING'];
    }
}
