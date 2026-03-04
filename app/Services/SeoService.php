<?php

namespace App\Services;

use App\Models\Page;
use App\Models\Setting;

class SeoService
{
    public function forHome(): array
    {
        return [
            'seo_title' => Setting::get('site_name', 'NovaRadio') . ' - ' . Setting::get('site_tagline', 'Internet Radio'),
            'seo_description' => Setting::get('site_description', 'Listen to the best internet radio. Live streaming music 24/7.'),
            'seo_keywords' => 'internet radio, streaming, music, live radio, online radio',
            'seo_og_image' => Setting::get('site_og_image'),
        ];
    }

    public function forPage(Page $page): array
    {
        return [
            'seo_title' => $page->seo_title . ' - ' . Setting::get('site_name', 'NovaRadio'),
            'seo_description' => $page->seo_description,
            'seo_keywords' => $page->meta_keywords,
            'seo_og_image' => $page->featured_image,
        ];
    }

    public function forTeam(): array
    {
        return [
            'seo_title' => 'Our Team - ' . Setting::get('site_name', 'NovaRadio'),
            'seo_description' => 'Meet the talented team behind ' . Setting::get('site_name', 'NovaRadio'),
            'seo_keywords' => 'radio team, djs, hosts, staff',
        ];
    }

    public function forSchedule(): array
    {
        return [
            'seo_title' => 'Program Schedule - ' . Setting::get('site_name', 'NovaRadio'),
            'seo_description' => 'View our weekly program schedule. Discover when your favorite shows air on ' . Setting::get('site_name', 'NovaRadio'),
            'seo_keywords' => 'radio schedule, program guide, shows, timetable',
        ];
    }

    public function forContact(): array
    {
        return [
            'seo_title' => 'Contact Us - ' . Setting::get('site_name', 'NovaRadio'),
            'seo_description' => 'Get in touch with ' . Setting::get('site_name', 'NovaRadio') . '. Send us your song requests and feedback.',
            'seo_keywords' => 'contact, feedback, song request',
        ];
    }
}
