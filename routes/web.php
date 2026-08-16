<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\SitemapController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');

Route::view('/about', 'about')->name('about');
Route::view('/contact', 'contact')->name('contact');

Route::view('/alt-checker', 'tools.alt-checker')->name('tools.alt-checker');
Route::view('/anchor-text-analyzer', 'tools.anchor-text-analyzer')->name('tools.anchor-text-analyzer');
Route::view('/broken-image-finder', 'tools.broken-image-finder')->name('tools.broken-image-finder');
Route::view('/color-palette', 'tools.color-palette')->name('tools.color-palette');
Route::view('/css-to-tailwind', 'tools.css-to-tailwind')->name('tools.css-to-tailwind');
Route::view('/css-variable-scanner', 'tools.css-variable-scanner')->name('tools.css-variable-scanner');
Route::view('/dns-lookup', 'tools.dns-lookup')->name('tools.dns-lookup');
Route::view('/faq-extractor', 'tools.faq-extractor')->name('tools.faq-extractor');
Route::view('/font-detector', 'tools.font-detector')->name('tools.font-detector');
Route::view('/heading-checker', 'tools.heading-checker')->name('tools.heading-checker');
Route::view('/link-checker', 'tools.link-checker')->name('tools.link-checker');
Route::view('/og-preview', 'tools.og-preview')->name('tools.og-preview');
Route::view('/page-weight', 'tools.page-weight')->name('tools.page-weight');
Route::view('/redirect-chain', 'tools.redirect-chain')->name('tools.redirect-chain');
Route::view('/schema-extractor', 'tools.schema-extractor')->name('tools.schema-extractor');
Route::view('/script-audit', 'tools.script-audit')->name('tools.script-audit');
Route::view('/security-headers', 'tools.security-headers')->name('tools.security-headers');
Route::view('/seo-analyzer', 'tools.seo-analyzer')->name('tools.seo-analyzer');
Route::view('/sitemap-diff', 'tools.sitemap-diff')->name('tools.sitemap-diff');
Route::view('/tailwind-extractor', 'tools.tailwind-extractor')->name('tools.tailwind-extractor');
Route::view('/tech-stack-detector', 'tools.tech-stack-detector')->name('tools.tech-stack-detector');
Route::view('/tracker-inventory', 'tools.tracker-inventory')->name('tools.tracker-inventory');
Route::view('/typography-seo-checker', 'tools.typography-seo-checker')->name('tools.typography-seo-checker');
