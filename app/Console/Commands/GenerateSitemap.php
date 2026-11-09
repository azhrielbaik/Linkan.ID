<?php

namespace App\Console\Commands;

use App\Models\Appearance;
use App\Models\DigitalProduct;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class GenerateSitemap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sitemap:generate {--domain= : Base URL to use for sitemap links (e.g. https://linkan.id)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate the XML sitemap for Linkan.ID';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Generating clean 200-only sitemap with hreflang alternates...');

        $baseUrl = $this->option('domain') ?: config('app.url', 'https://linkan.id');
        $baseUrl = rtrim($baseUrl, '/');

        $sitemap = Sitemap::create();
        $locales = ['id', 'en'];

        // 1. Halaman Utama (Welcome) ber-prefix locale
        foreach ($locales as $locale) {
            $url = Url::create("{$baseUrl}/{$locale}")
                ->setPriority(1.0)
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                ->addAlternate("{$baseUrl}/id", 'id')
                ->addAlternate("{$baseUrl}/en", 'en')
                ->addAlternate("{$baseUrl}/id", 'x-default');

            $sitemap->add($url);
        }

        // 2. Halaman Statis Publik (FAQ, About, Contact)
        // Catatan: 'pricing' dihilangkan karena me-redirect 302 ke #pricing
        $staticPages = [
            'faq' => ['priority' => 0.8, 'freq' => Url::CHANGE_FREQUENCY_WEEKLY],
            'about' => ['priority' => 0.7, 'freq' => Url::CHANGE_FREQUENCY_MONTHLY],
            'contact' => ['priority' => 0.7, 'freq' => Url::CHANGE_FREQUENCY_MONTHLY],
        ];

        foreach ($staticPages as $page => $meta) {
            foreach ($locales as $locale) {
                $url = Url::create("{$baseUrl}/{$locale}/{$page}")
                    ->setPriority($meta['priority'])
                    ->setChangeFrequency($meta['freq'])
                    ->addAlternate("{$baseUrl}/id/{$page}", 'id')
                    ->addAlternate("{$baseUrl}/en/{$page}", 'en')
                    ->addAlternate("{$baseUrl}/id/{$page}", 'x-default');

                $sitemap->add($url);
            }
        }

        // 3. Microsite Aktif Penjual (Appearances)
        $appearances = Appearance::where('is_active', true)
            ->whereNotNull('alias')
            ->where('alias', '!=', '')
            ->with(['user'])
            ->get();

        $indexedAppearances = 0;
        foreach ($appearances as $appearance) {
            $user = $appearance->user;
            if (! $user || $user->isSuspended()) {
                continue;
            }

            $alias = $appearance->alias;
            $lastmod = $appearance->updated_at ?? Carbon::now();

            foreach ($locales as $locale) {
                $url = Url::create("{$baseUrl}/{$locale}/linkan.id/{$alias}")
                    ->setPriority(0.9)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                    ->setLastModificationDate($lastmod)
                    ->addAlternate("{$baseUrl}/id/linkan.id/{$alias}", 'id')
                    ->addAlternate("{$baseUrl}/en/linkan.id/{$alias}", 'en')
                    ->addAlternate("{$baseUrl}/id/linkan.id/{$alias}", 'x-default');

                $sitemap->add($url);
            }
            $indexedAppearances++;
        }
        $this->info("Indexed {$indexedAppearances} active microsite appearances across ".count($locales).' locales.');

        // 4. Produk Digital Aktif & Terverifikasi
        $products = DigitalProduct::where('is_active', true)
            ->whereNull('takedown_at')
            ->where('verification_status', '!=', 'rejected')
            ->with('user')
            ->get();

        $indexedProducts = 0;
        foreach ($products as $product) {
            $seller = $product->user;
            if (! $seller || $seller->isSuspended()) {
                continue;
            }

            $productId = $product->id;
            $lastmod = $product->updated_at ?? Carbon::now();

            foreach ($locales as $locale) {
                $url = Url::create("{$baseUrl}/{$locale}/product/{$productId}")
                    ->setPriority(0.8)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                    ->setLastModificationDate($lastmod)
                    ->addAlternate("{$baseUrl}/id/product/{$productId}", 'id')
                    ->addAlternate("{$baseUrl}/en/product/{$productId}", 'en')
                    ->addAlternate("{$baseUrl}/id/product/{$productId}", 'x-default');

                $sitemap->add($url);
            }
            $indexedProducts++;
        }
        $this->info("Indexed {$indexedProducts} active digital products across ".count($locales).' locales.');

        $sitemapPath = public_path('sitemap.xml');
        $sitemap->writeToFile($sitemapPath);

        $this->info("Sitemap generated successfully at: {$sitemapPath}");

        return Command::SUCCESS;
    }
}
