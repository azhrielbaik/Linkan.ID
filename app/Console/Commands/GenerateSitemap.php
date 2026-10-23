<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use App\Models\User;
use App\Models\DigitalProduct;
use Carbon\Carbon;

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
        $this->info('Generating sitemap...');

        $baseUrl = $this->option('domain') ?: config('app.url', 'https://linkan.id');
        $baseUrl = rtrim($baseUrl, '/');

        $sitemap = Sitemap::create();

        // 1. Halaman Utama & Lokalisasi
        $sitemap->add(Url::create("{$baseUrl}/")->setPriority(1.0)->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY));
        $sitemap->add(Url::create("{$baseUrl}/id")->setPriority(1.0)->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY));
        $sitemap->add(Url::create("{$baseUrl}/en")->setPriority(0.9)->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY));

        // 2. Halaman Statis Publik (ID & EN)
        $staticPages = ['pricing', 'faq', 'about', 'contact'];
        foreach ($staticPages as $page) {
            $priority = in_array($page, ['pricing', 'faq']) ? 0.8 : 0.7;
            $freq = in_array($page, ['pricing', 'faq']) ? Url::CHANGE_FREQUENCY_WEEKLY : Url::CHANGE_FREQUENCY_MONTHLY;

            $sitemap->add(Url::create("{$baseUrl}/{$page}")->setPriority($priority)->setChangeFrequency($freq));
            $sitemap->add(Url::create("{$baseUrl}/id/{$page}")->setPriority($priority)->setChangeFrequency($freq));
            $sitemap->add(Url::create("{$baseUrl}/en/{$page}")->setPriority($priority)->setChangeFrequency($freq));
        }

        // 3. Microsite Aktif (Appearances)
        $appearances = \App\Models\Appearance::where('is_active', true)
            ->whereNotNull('alias')
            ->where('alias', '!=', '')
            ->with(['user'])
            ->get();

        $indexedAppearances = 0;
        foreach ($appearances as $appearance) {
            $user = $appearance->user;
            // Lewati jika user/seller pemilik sedang ditangguhkan
            if (!$user || $user->isSuspended()) {
                continue;
            }

            $sitemap->add(
                Url::create("{$baseUrl}/{$appearance->alias}")
                    ->setPriority(0.9)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                    ->setLastModificationDate($appearance->updated_at ?? Carbon::now())
            );
            $indexedAppearances++;
        }
        $this->info("Indexed {$indexedAppearances} active microsite appearances.");

        // 4. Produk Digital Aktif
        $products = DigitalProduct::where('is_active', true)
            ->whereNull('takedown_at')
            ->with('user')
            ->get();

        $indexedProducts = 0;
        foreach ($products as $product) {
            $seller = $product->user;
            if (!$seller || $seller->isSuspended()) {
                continue;
            }

            $sitemap->add(
                Url::create("{$baseUrl}/product/{$product->id}")
                    ->setPriority(0.9)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                    ->setLastModificationDate($product->updated_at ?? Carbon::now())
            );
            $indexedProducts++;
        }
        $this->info("Indexed {$indexedProducts} active digital products.");

        $sitemapPath = public_path('sitemap.xml');
        $sitemap->writeToFile($sitemapPath);

        $this->info("Sitemap generated successfully at: {$sitemapPath}");
    }
}
