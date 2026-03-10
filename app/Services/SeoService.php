<?php

namespace App\Services;

use App\Models\Page;
use App\Models\Setting;

class SeoService
{
    public function forHome(): array
    {
        return [
            'seo_title' => setting('site_name', 'NovaRadio') . ' - ' . setting('site_tagline', 'Internet Radio'),
            'seo_description' => setting('site_description', 'Listen to the best internet radio. Live streaming music 24/7.'),
            'seo_keywords' => 'internet radio, streaming, music, live radio, online radio',
            'seo_og_image' => setting('site_og_image'),
            'siteName' => setting('site_name', 'NovaRadio'),
            'siteTagline' => setting('site_tagline', 'Internet Radio'),
        ];
    }

    public function forPage(Page $page): array
    {
        return [
            'seo_title' => $page->seo_title . ' - ' . setting('site_name', 'NovaRadio'),
            'seo_description' => $page->seo_description,
            'seo_keywords' => $page->meta_keywords,
            'seo_og_image' => $page->featured_image,
            'siteName' => setting('site_name', 'NovaRadio'),
            'siteTagline' => setting('site_tagline', 'Internet Radio'),
        ];
    }

    public function forTeam(): array
    {
        return [
            'seo_title' => 'Our Team - ' . setting('site_name', 'NovaRadio'),
            'seo_description' => 'Meet the talented team behind ' . setting('site_name', 'NovaRadio'),
            'seo_keywords' => 'radio team, djs, hosts, staff',
            'siteName' => setting('site_name', 'NovaRadio'),
            'siteTagline' => setting('site_tagline', 'Internet Radio'),
        ];
    }

    public function forSchedule(): array
    {
        return [
            'seo_title' => 'Program Schedule - ' . setting('site_name', 'NovaRadio'),
            'seo_description' => 'View our weekly program schedule. Discover when your favorite shows air on ' . setting('site_name', 'NovaRadio'),
            'seo_keywords' => 'radio schedule, program guide, shows, timetable',
            'siteName' => setting('site_name', 'NovaRadio'),
            'siteTagline' => setting('site_tagline', 'Internet Radio'),
        ];
    }

    public function forContact(): array
    {
        return [
            'seo_title' => 'Contact Us - ' . setting('site_name', 'NovaRadio'),
            'seo_description' => 'Get in touch with ' . setting('site_name', 'NovaRadio') . '. Send us your song requests and feedback.',
            'seo_keywords' => 'contact, feedback, song request',
            'siteName' => setting('site_name', 'NovaRadio'),
            'siteTagline' => setting('site_tagline', 'Internet Radio'),
        ];
    }
}
