<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Settings;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        foreach ($this->values() as $key => $value) {
            Settings::set($key, $value);
        }
    }

    /**
     * @return array<string, mixed>
     */
    private function values(): array
    {
        return [
            'seo.title' => [
                'ru' => 'Perfectum — оператор связи 5G Standalone в Узбекистане',
                'uz' => "Perfectum — O'zbekistondagi 5G Standalone aloqa operatori",
                'en' => 'Perfectum — the 5G Standalone network operator in Uzbekistan',
            ],

            'seo.description' => [
                'ru' => 'Perfectum — высокоскоростной мобильный и домашний интернет на сети 5G Standalone. Тарифы, красивые номера и покрытие по всему Узбекистану.',
                'uz' => "Perfectum — 5G Standalone tarmog'iga asoslangan yuqori tezlikdagi mobil va uy interneti. Tariflar, chiroyli raqamlar va butun O'zbekiston bo'ylab qamrov.",
                'en' => 'Perfectum — high-speed mobile and home internet on a 5G Standalone network. Tariffs, premium numbers and coverage across Uzbekistan.',
            ],

            'seo.keywords' => [
                'ru' => 'Perfectum, 5G, интернет, мобильная связь, тарифы, Узбекистан',
                'uz' => "Perfectum, 5G, internet, mobil aloqa, tariflar, O'zbekiston",
                'en' => 'Perfectum, 5G, internet, mobile, tariffs, Uzbekistan',
            ],

            'seo.indexing_enabled' => true,

            'metrics.yandex' => $this->yandexMetrika(),
            'metrics.google' => $this->googleTagManager(),
        ];
    }

    private function yandexMetrika(): string
    {
        return <<<'HTML'
<!-- Yandex.Metrika counter -->
<script type="text/javascript">
    (function(m,e,t,r,i,k,a){
        m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
        m[i].l=1*new Date();
        for (var j = 0; j < document.scripts.length; j++) {if (document.scripts[j].src === r) { return; }}
        k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)
    })(window, document,'script','https://mc.yandex.ru/metrika/tag.js?id=109871468', 'ym');

    ym(109871468, 'init', {ssr:true, webvisor:true, clickmap:true, ecommerce:"dataLayer", referrer: document.referrer, url: location.href, accurateTrackBounce:true, trackLinks:true});
</script>
<noscript><div><img src="https://mc.yandex.ru/watch/109871468" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->
HTML;
    }

    private function googleTagManager(): string
    {
        return <<<'HTML'
<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-TXKZSKT3');</script>
<!-- End Google Tag Manager -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-TXKZSKT3"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
HTML;
    }
}
