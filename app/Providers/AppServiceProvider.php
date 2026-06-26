<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Register @vite Blade directive for Laravel 8 (built-in from Laravel 9.19+).
        // Reads the Vite manifest at public/build/manifest.json for production builds,
        // or connects to the Vite dev server (public/hot file) during development.
        Blade::directive('vite', function ($expression) {
            return "<?php echo \\App\\Providers\\AppServiceProvider::viteAssets($expression); ?>";
        });
    }

    /**
     * Generate HTML tags for Vite-managed assets.
     *
     * @param  string|string[]  $assets
     * @return string
     */
    public static function viteAssets($assets): string
    {
        if (!is_array($assets)) {
            $assets = [$assets];
        }

        // Development: proxy through the Vite dev server
        $hotFile = public_path('hot');
        if (file_exists($hotFile)) {
            $url = rtrim(file_get_contents($hotFile));
            $output = sprintf('<script type="module" src="%s/@vite/client"></script>', $url);
            foreach ($assets as $asset) {
                $output .= "\n" . sprintf('<script type="module" src="%s/%s"></script>', $url, $asset);
            }
            return $output;
        }

        // Production: read hashed filenames from the manifest
        $manifestPath = public_path('build/manifest.json');
        if (!file_exists($manifestPath)) {
            return '';
        }

        $manifest = json_decode(file_get_contents($manifestPath), true);
        $output = '';

        foreach ($assets as $asset) {
            $chunk = $manifest[$asset] ?? null;
            if (!$chunk) {
                continue;
            }

            // CSS files that are bundled as a side-effect of a JS entry point
            if (isset($chunk['css'])) {
                foreach ($chunk['css'] as $css) {
                    $output .= sprintf('<link rel="stylesheet" href="/build/%s">', $css) . "\n";
                }
            }

            $file = $chunk['file'] ?? null;
            if ($file) {
                if (substr($file, -3) === '.js') {
                    $output .= sprintf('<script type="module" src="/build/%s"></script>', $file) . "\n";
                } elseif (substr($file, -4) === '.css') {
                    $output .= sprintf('<link rel="stylesheet" href="/build/%s">', $file) . "\n";
                }
            }
        }

        return $output;
    }
}
