<?php

namespace Database\Seeders;

use App\Models\BusinessCard;
use App\Models\Company;
use App\Models\Customer;
use App\Models\InteractionNote;
use App\Models\Meeting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class MarketingSimulationSeeder extends Seeder
{
    public function run(): void
    {
        $companies = Company::all();
        if ($companies->isEmpty()) {
            return;
        }

        // 20 Marketing & Sales Staff
        $staffTemplates = [
            [
                'name' => 'Ali Rıza Çelik',
                'title' => 'B2B Pazarlama Direktörü',
                'department' => 'Pazarlama & Büyüme',
                'phone' => '+90 532 987 65 43',
                'email_prefix' => 'aliriza',
                'is_leader' => true,
                'monthly_target' => 25,
                'bio' => 'B2B pazarlama stratejileri, kurumsal anlaşmalar ve büyüme pazarlaması üzerine 10+ yıllık deneyim.',
            ],
            [
                'name' => 'Zeynep Arslan',
                'title' => 'Kıdemli Performans Pazarlama Lideri',
                'department' => 'Dijital Pazarlama',
                'phone' => '+90 533 876 54 32',
                'email_prefix' => 'zeynep.arslan',
                'is_leader' => true,
                'monthly_target' => 20,
                'bio' => 'Google Ads, Meta Ads ve dönüşüm optimizasyonu (CRO) uzmanı.',
            ],
            [
                'name' => 'Burak Korkmaz',
                'title' => 'Büyüme & Growth Marketing Lideri',
                'department' => 'Büyüme & Satış',
                'phone' => '+90 535 765 43 21',
                'email_prefix' => 'burak.korkmaz',
                'is_leader' => true,
                'monthly_target' => 22,
                'bio' => 'Growth hacking, veri odaklı müşteri kazanımı ve funnel optimizasyonu lideri.',
            ],
            [
                'name' => 'Merve Yıldırım',
                'title' => 'Kurumsal Saha Satış Yöneticisi',
                'department' => 'Kurumsal Satış',
                'phone' => '+90 530 654 32 10',
                'email_prefix' => 'merve.yildirim',
                'monthly_target' => 18,
                'bio' => 'Kurumsal B2B satış, sıcak görüşmeler ve müşteri portföy yönetimi.',
            ],
            [
                'name' => 'Emre Şen',
                'title' => 'Dijital Pazarlama & SEO Stratejisti',
                'department' => 'Dijital Pazarlama',
                'phone' => '+90 534 543 21 09',
                'email_prefix' => 'emre.sen',
                'monthly_target' => 15,
                'bio' => 'Organik büyüme, teknik SEO ve arama motoru pazarlaması (SEM).',
            ],
            [
                'name' => 'Deniz Yılmaz',
                'title' => 'İçerik & Sosyal Medya Müdürü',
                'department' => 'İçerik & Marka',
                'phone' => '+90 536 432 10 98',
                'email_prefix' => 'deniz.yilmaz',
                'monthly_target' => 14,
                'bio' => 'Marka hikayeleştirme, video içerik stratejisi ve viral pazarlama kampanyaları.',
            ],
            [
                'name' => 'Pelin Aksoy',
                'title' => 'Lead Generation & Talep Yaratma Uzmanı',
                'department' => 'Satış & Talep Yaratma',
                'phone' => '+90 537 321 09 87',
                'email_prefix' => 'pelin.aksoy',
                'monthly_target' => 20,
                'bio' => 'Inbound/outbound potansiyel müşteri yaratma ve soğuk e-posta otomasyonları.',
            ],
            [
                'name' => 'Caner Erkin',
                'title' => 'İş Geliştirme & Partnerlikler (BDR)',
                'department' => 'İş Geliştirme',
                'phone' => '+90 538 210 98 76',
                'email_prefix' => 'caner.erkin',
                'monthly_target' => 16,
                'bio' => 'Stratejik ortaklıklar, acente kanalları ve B2B iş geliştirme.',
            ],
            [
                'name' => 'Elif Şahin',
                'title' => 'Müşteri Başarı & Dönüşüm Uzmanı (CSM)',
                'department' => 'Müşteri Başarısı',
                'phone' => '+90 539 109 87 65',
                'email_prefix' => 'elif.sahin',
                'monthly_target' => 18,
                'bio' => 'Müşteri onboarding, up-sell/cross-sell ve memnuniyet yönetimi.',
            ],
            [
                'name' => 'Tolga Aydın',
                'title' => 'Kurumsal Çözüm & Satış Danışmanı',
                'department' => 'Kurumsal Satış',
                'phone' => '+90 531 098 76 54',
                'email_prefix' => 'tolga.aydin',
                'monthly_target' => 15,
                'bio' => 'Büyük ölçekli kurumsal satış ve dijital kartvizit dönüşüm danışmanlığı.',
            ],
            [
                'name' => 'Begüm Koç',
                'title' => 'E-Posta & CRM Pazarlama Yöneticisi',
                'department' => 'CRM & Otomasyon',
                'phone' => '+90 532 123 45 67',
                'email_prefix' => 'begum.koc',
                'monthly_target' => 16,
                'bio' => 'HubSpot/Salesforce CRM kurguları, e-posta pazarlaması ve segmentasyon.',
            ],
            [
                'name' => 'Kerem Demirtaş',
                'title' => 'Dijital Reklam & Kampanya Yöneticisi',
                'department' => 'Dijital Pazarlama',
                'phone' => '+90 533 234 56 78',
                'email_prefix' => 'kerem.demirtas',
                'monthly_target' => 14,
                'bio' => 'Programatik reklamcılık, LinkedIn B2B reklamları ve performans analitiği.',
            ],
            [
                'name' => 'Hande Çetin',
                'title' => 'Etkinlik & Sponsorluk Pazarlaması',
                'department' => 'Saha & Etkinlik',
                'phone' => '+90 534 345 67 89',
                'email_prefix' => 'hande.cetin',
                'monthly_target' => 12,
                'bio' => 'B2B zirve, fuar organizasyonları ve kurumsal sponsorluk yönetimi.',
            ],
            [
                'name' => 'Volkan Öztürk',
                'title' => 'Ürün Pazarlama Yöneticisi (PMM)',
                'department' => 'Ürün Pazarlama',
                'phone' => '+90 535 456 78 90',
                'email_prefix' => 'volkan.ozturk',
                'monthly_target' => 14,
                'bio' => 'Go-to-market stratejileri, ürün konumlandırma ve rekabet analizi.',
            ],
            [
                'name' => 'Selen Güneş',
                'title' => 'Müşteri Deneyimi & İletişim Uzmanı',
                'department' => 'Müşteri İlişkileri',
                'phone' => '+90 536 567 89 01',
                'email_prefix' => 'selen.gunes',
                'monthly_target' => 15,
                'bio' => 'NPS artırma, müşteri geri bildirim döngüleri ve marka sadakati.',
            ],
            [
                'name' => 'Onur Kaya',
                'title' => 'Outbound Satış & Kanal Yöneticisi',
                'department' => 'Satış',
                'phone' => '+90 537 678 90 12',
                'email_prefix' => 'onur.kaya',
                'monthly_target' => 16,
                'bio' => 'Doğrudan satış kanalları, bayi yönetimi ve yüksek hacimli teklifler.',
            ],
            [
                'name' => 'Damla Kurt',
                'title' => 'Marka & PR İletişim Danışmanı',
                'department' => 'Kurumsal İletişim',
                'phone' => '+90 538 789 01 23',
                'email_prefix' => 'damla.kurt',
                'monthly_target' => 12,
                'bio' => 'Basın bültenleri, kurumsal PR ve medya ilişkileri yönetimi.',
            ],
            [
                'name' => 'Serkan Polat',
                'title' => 'Veri Analitiği & Pazarlama Raporlama',
                'department' => 'Pazarlama Operasyonları',
                'phone' => '+90 539 890 12 34',
                'email_prefix' => 'serkan.polat',
                'monthly_target' => 14,
                'bio' => 'Google Analytics 4, Looker Studio ve pazarlama KPI modelleme.',
            ],
            [
                'name' => 'Gözde Eren',
                'title' => 'B2B Müşteri Temsilcisi',
                'department' => 'Müşteri İlişkileri',
                'phone' => '+90 530 901 23 45',
                'email_prefix' => 'gozde.eren',
                'monthly_target' => 15,
                'bio' => 'Hızlı teklif hazırlama, müşteri toplantıları ve sözleşme süreçleri.',
            ],
            [
                'name' => 'Arda Bulut',
                'title' => 'Dijital Satış & Inbound Lead Yöneticisi',
                'department' => 'Dijital Satış',
                'phone' => '+90 531 012 34 56',
                'email_prefix' => 'arda.bulut',
                'monthly_target' => 17,
                'bio' => 'Web sitesi formları, canlı destek ve gelen talep dönüşüm yönetimi.',
            ],
        ];

        // 60 CRM Customers
        $customerTemplates = [
            ['name' => 'Kemal Yılmaz', 'company_name' => 'TechPlus Bilişim A.Ş.', 'title' => 'Genel Müdür', 'stage' => 'hot', 'phone' => '+90 532 111 22 33', 'email' => 'kemal@techplus.com'],
            ['name' => 'Selin Demir', 'company_name' => 'Finans Global Bank', 'title' => 'İnsan Kaynakları Direktörü', 'stage' => 'warm', 'phone' => '+90 533 444 55 66', 'email' => 'selin@finansglobal.com'],
            ['name' => 'Zeynep Kaya', 'company_name' => 'Kaya Mimarlık & Tasarım', 'title' => 'Kurucu Ortak', 'stage' => 'hot', 'phone' => '+90 530 222 33 44', 'email' => 'zeynep@kayamimarlik.com'],
            ['name' => 'Emre Can', 'company_name' => 'Delta Uluslararası Lojistik', 'title' => 'Operasyon Müdürü', 'stage' => 'cold', 'phone' => '+90 535 777 88 99', 'email' => 'emre@deltalojistik.com'],
            ['name' => 'Hakan Vural', 'company_name' => 'Trendyol Group', 'title' => 'Kurumsal İletişim Direktörü', 'stage' => 'hot', 'phone' => '+90 532 888 11 22', 'email' => 'hakan.vural@trendyol.com'],
            ['name' => 'Banu Sönmez', 'company_name' => 'Garanti BBVA', 'title' => 'Yeteneği Yönetimi Başkanı', 'stage' => 'hot', 'phone' => '+90 533 999 22 33', 'email' => 'banu.sonmez@garantibbva.com.tr'],
            ['name' => 'Cem Tanrıkulu', 'company_name' => 'Logo Yazılım A.Ş.', 'title' => 'B2B Pazarlama Direktörü', 'stage' => 'warm', 'phone' => '+90 534 111 33 44', 'email' => 'cem.tanrikulu@logo.com.tr'],
            ['name' => 'Derya Özkan', 'company_name' => 'Acıbadem Sağlık Grubu', 'title' => 'Medikal Operasyonlar Müdürü', 'stage' => 'hot', 'phone' => '+90 535 222 44 55', 'email' => 'derya.ozkan@acibadem.com'],
            ['name' => 'Eren Şahin', 'company_name' => 'LC Waikiki', 'title' => 'Global İK ve İletişim Müdürü', 'stage' => 'warm', 'phone' => '+90 536 333 55 66', 'email' => 'eren.sahin@lcwaikiki.com'],
            ['name' => 'Funda Çelik', 'company_name' => 'Deloitte Türkiye', 'title' => 'Kıdemli Danışmanlık Direktörü', 'stage' => 'hot', 'phone' => '+90 537 444 66 77', 'email' => 'funda.celik@deloitte.com.tr'],
            ['name' => 'Gökhan Alkan', 'company_name' => 'Rönesans Holding', 'title' => 'Satın Alma Başkanı', 'stage' => 'cold', 'phone' => '+90 538 555 77 88', 'email' => 'gokhan.alkan@ronesans.com'],
            ['name' => 'Halil İbrahim', 'company_name' => 'Getir Perakende', 'title' => 'Büyüme & Operasyon Lideri', 'stage' => 'hot', 'phone' => '+90 539 666 88 99', 'email' => 'halil.ibrahim@getir.com'],
            ['name' => 'İrem Karaca', 'company_name' => 'Eczacıbaşı Holding', 'title' => 'Dijital Dönüşüm Yöneticisi', 'stage' => 'warm', 'phone' => '+90 530 777 99 00', 'email' => 'irem.karaca@eczacibasi.com.tr'],
            ['name' => 'Kaan Yıldız', 'company_name' => 'Tofaş Türk Otomobil Fabrikası', 'title' => 'Kurumsal Satış Müdürü', 'stage' => 'hot', 'phone' => '+90 531 888 00 11', 'email' => 'kaan.yildiz@tofas.com.tr'],
            ['name' => 'Leyla Aslan', 'company_name' => 'PwC Danışmanlık', 'title' => 'Müşteri İlişkileri Direktörü', 'stage' => 'warm', 'phone' => '+90 532 999 11 22', 'email' => 'leyla.aslan@pwc.com.tr'],
            ['name' => 'Murat Bozok', 'company_name' => 'Pegasus Havayolları', 'title' => 'Pazarlama & Müşteri Deneyimi', 'stage' => 'cold', 'phone' => '+90 533 101 22 33', 'email' => 'murat.bozok@flypgs.com'],
            ['name' => 'Nilüfer Şen', 'company_name' => 'Akbank T.A.Ş.', 'title' => 'Kurumsal İnovasyon Lideri', 'stage' => 'hot', 'phone' => '+90 534 212 33 44', 'email' => 'nilufer.sen@akbank.com'],
            ['name' => 'Ozan Güven', 'company_name' => 'Koç Üniversitesi', 'title' => 'Genel Sekreter & İletişim', 'stage' => 'warm', 'phone' => '+90 535 323 44 55', 'email' => 'ozan.guven@ku.edu.tr'],
            ['name' => 'Pınar Deniz', 'company_name' => 'Tabanlıoğlu Mimarlık', 'title' => 'Proje Baş Mimarı', 'stage' => 'hot', 'phone' => '+90 536 434 55 66', 'email' => 'pinar.deniz@tabanlioglu.com'],
            ['name' => 'Rıza Kocaoğlu', 'company_name' => 'Ekol Lojistik A.Ş.', 'title' => 'Filo & Teknoloji Yöneticisi', 'stage' => 'cold', 'phone' => '+90 537 545 66 77', 'email' => 'riza.kocaoglu@ekol.com'],
            ['name' => 'Sarp Levendoğlu', 'company_name' => 'Mavi Giyim Sanayi', 'title' => 'Pazarlama Direktörü', 'stage' => 'hot', 'phone' => '+90 538 656 77 88', 'email' => 'sarp.levendoglu@mavi.com'],
            ['name' => 'Tuğba Ekinci', 'company_name' => 'Memorial Sağlık Grubu', 'title' => 'Uluslararası Pazarlama Müdürü', 'stage' => 'warm', 'phone' => '+90 539 767 88 99', 'email' => 'tugba.ekinci@memorial.com.tr'],
            ['name' => 'Uğur Polat', 'company_name' => 'KPMG Türkiye', 'title' => 'Denetim & Vergi Ortağı', 'stage' => 'hot', 'phone' => '+90 530 878 99 00', 'email' => 'ugur.polat@kpmg.com.tr'],
            ['name' => 'Vildan Atasever', 'company_name' => 'Nef Gayrimenkul', 'title' => 'Satış & Pazarlama Genel Müdürü', 'stage' => 'warm', 'phone' => '+90 531 989 00 11', 'email' => 'vildan.atasever@nef.com.tr'],
            ['name' => 'Yasin Çakır', 'company_name' => 'Ford Otosan', 'title' => 'Dijital Ürün & Mobilite Lideri', 'stage' => 'hot', 'phone' => '+90 532 090 11 22', 'email' => 'yasin.cakir@ford.com.tr'],
            ['name' => 'Zehra Güneş', 'company_name' => 'Bahçeşehir Üniversitesi', 'title' => 'Kariyer Merkezi Direktörü', 'stage' => 'warm', 'phone' => '+90 533 191 22 33', 'email' => 'zehra.gunes@bau.edu.tr'],
            ['name' => 'Ahmet Mümtaz Taylan', 'company_name' => 'Defacto Perakende', 'title' => 'İcra Kurulu Üyesi', 'stage' => 'hot', 'phone' => '+90 534 292 33 44', 'email' => 'ahmet.taylan@defacto.com.tr'],
            ['name' => 'Berna Laçin', 'company_name' => 'EY Danışmanlık', 'title' => 'Kurumsal Risk Direktörü', 'stage' => 'cold', 'phone' => '+90 535 393 44 55', 'email' => 'berna.lacin@ey.com.tr'],
            ['name' => 'Cihan Ünal', 'company_name' => 'Tahincioğlu Gayrimenkul', 'title' => 'Proje Geliştirme Müdürü', 'stage' => 'hot', 'phone' => '+90 536 494 55 66', 'email' => 'cihan.unal@tahincioglu.com'],
            ['name' => 'Demet Evgar', 'company_name' => 'İş Bankası', 'title' => 'Bireysel & Ticari Bankacılık', 'stage' => 'warm', 'phone' => '+90 537 595 66 77', 'email' => 'demet.evgar@isbank.com.tr'],
            ['name' => 'Engin Altan', 'company_name' => 'Borusan Holding', 'title' => 'Strateji & Yatırımlar', 'stage' => 'hot', 'phone' => '+90 538 696 77 88', 'email' => 'engin.altan@borusan.com'],
            ['name' => 'Fikret Kuşkan', 'company_name' => 'Mercedes-Benz Türk', 'title' => 'Kurumsal İletişim Müdürü', 'stage' => 'warm', 'phone' => '+90 539 797 88 99', 'email' => 'fikret.kuskan@daimler.com'],
            ['name' => 'Gülse Birsel', 'company_name' => 'Boyner Holding', 'title' => 'Marka & Kreatif Direktörü', 'stage' => 'hot', 'phone' => '+90 530 898 99 00', 'email' => 'gulse.birsel@boyner.com.tr'],
            ['name' => 'Halit Ergenç', 'company_name' => 'Sabancı Holding', 'title' => 'İnsan Kaynakları Grup Başkanı', 'stage' => 'hot', 'phone' => '+90 531 909 00 11', 'email' => 'halit.ergenc@sabanci.com'],
            ['name' => 'Işıl Yücesoy', 'company_name' => 'Liv Hospital', 'title' => 'Kurumsal Sağlık Koordinatörü', 'stage' => 'cold', 'phone' => '+90 532 010 11 22', 'email' => 'isil.yucesoy@livhospital.com'],
            ['name' => 'Kıvanç Tatlıtuğ', 'company_name' => 'Hepsiburada', 'title' => 'Kategori & Tedarik Lideri', 'stage' => 'hot', 'phone' => '+90 533 121 22 33', 'email' => 'kivanc.tatlitug@hepsiburada.com'],
            ['name' => 'Meltem Cumbul', 'company_name' => 'Doğa Koleji', 'title' => 'Eğitim Teknolojileri Direktörü', 'stage' => 'warm', 'phone' => '+90 534 232 33 44', 'email' => 'meltem.cumbul@dogakoleji.k12.tr'],
            ['name' => 'Nejat İşler', 'company_name' => 'QNB Finansbank', 'title' => 'B2B Müşteri Çözümleri', 'stage' => 'hot', 'phone' => '+90 535 343 44 55', 'email' => 'nejat.isler@qnbfinansbank.com'],
            ['name' => 'Oktay Kaynarca', 'company_name' => 'DAP Yapı', 'title' => 'Yönetim Kurulu Başkan Danışmanı', 'stage' => 'cold', 'phone' => '+90 536 454 55 66', 'email' => 'oktay.kaynarca@dapyapi.com.tr'],
            ['name' => 'Özge Özpirinçci', 'company_name' => 'Vakko Holding', 'title' => 'Mağazacılık & VIP Müşteri Lideri', 'stage' => 'hot', 'phone' => '+90 537 565 66 77', 'email' => 'ozge.ozpirincci@vakko.com.tr'],
            ['name' => 'Rıza Çalımbay', 'company_name' => 'Brisa Bridgestone', 'title' => 'Saha & Distribütör Kanal Müdürü', 'stage' => 'warm', 'phone' => '+90 538 676 77 88', 'email' => 'riza.calimbay@brisa.com.tr'],
            ['name' => 'Seda Sayan', 'company_name' => 'Koton Mağazacılık', 'title' => 'Tasarım & Koleksiyon Direktörü', 'stage' => 'warm', 'phone' => '+90 539 787 88 99', 'email' => 'seda.sayan@koton.com'],
            ['name' => 'Tolga Sarıtaş', 'company_name' => 'Papara Elektronik Para', 'title' => 'B2B Fintech Çözümleri Direktörü', 'stage' => 'hot', 'phone' => '+90 530 898 00 11', 'email' => 'tolga.saritas@papara.com'],
            ['name' => 'Tuba Büyüküstün', 'company_name' => 'Abdi İbrahim İlaç', 'title' => 'Pazarlama & Medikal İletişim', 'stage' => 'hot', 'phone' => '+90 531 909 11 22', 'email' => 'tuba.buyukustun@abdiibrahim.com.tr'],
            ['name' => 'Uraz Kaygılaroğlu', 'company_name' => 'Insider Growth Management', 'title' => 'Global Satış Direktörü', 'stage' => 'hot', 'phone' => '+90 532 010 22 33', 'email' => 'uraz.kaygilaroglu@useinsider.com'],
            ['name' => 'Vahide Perçin', 'company_name' => 'TED Koleji', 'title' => 'Genel Müdür', 'stage' => 'cold', 'phone' => '+90 533 121 33 44', 'email' => 'vahide.percin@ted.k12.tr'],
            ['name' => 'Yetkin Dikinciler', 'company_name' => 'Kordsa Teknik Tekstil', 'title' => 'İnovasyon & Ar-Ge Direktörü', 'stage' => 'warm', 'phone' => '+90 534 232 44 55', 'email' => 'yetkin.dikinciler@kordsa.com'],
            ['name' => 'Zerrin Tekindor', 'company_name' => 'Autoban Mimarlık & Tasarım', 'title' => 'İç Mimari Tasarım Direktörü', 'stage' => 'hot', 'phone' => '+90 535 343 55 66', 'email' => 'zerrin.tekindor@autoban212.com'],
            ['name' => 'Ali Atay', 'company_name' => 'iyzico Ödeme Hizmetleri', 'title' => 'İş Ortaklıkları Direktörü', 'stage' => 'hot', 'phone' => '+90 536 454 66 77', 'email' => 'ali.atay@iyzico.com'],
            ['name' => 'Bige Önal', 'company_name' => 'Paksoy Hukuk Bürosu', 'title' => 'Kurumsal Birleşme & Satın Alma Avukatı', 'stage' => 'warm', 'phone' => '+90 537 565 77 88', 'email' => 'bige.onal@paksoy.com.tr'],
            ['name' => 'Çağatay Ulusoy', 'company_name' => 'Peak Games', 'title' => 'Ürün Müdürü', 'stage' => 'hot', 'phone' => '+90 538 676 88 99', 'email' => 'cagatay.ulusoy@peak.com'],
            ['name' => 'Dilan Çiçek Deniz', 'company_name' => 'Armut.com', 'title' => 'Müşteri Başarı Lideri', 'stage' => 'warm', 'phone' => '+90 539 787 99 00', 'email' => 'dilan.deniz@armut.com'],
            ['name' => 'Ece Uslu', 'company_name' => 'Sinpaş GYO', 'title' => 'Pazarlama Koordinatörü', 'stage' => 'cold', 'phone' => '+90 530 898 11 22', 'email' => 'ece.uslu@sinpasgyo.com.tr'],
            ['name' => 'Furkan Andıç', 'company_name' => 'OBSS Teknoloji', 'title' => 'Kurumsal Yazılım Satış Lideri', 'stage' => 'hot', 'phone' => '+90 531 909 22 33', 'email' => 'furkan.andic@obss.tech'],
            ['name' => 'Hazal Kaya', 'company_name' => 'Netlog Lojistik Grubu', 'title' => 'Müşteri Çözümleri Direktörü', 'stage' => 'warm', 'phone' => '+90 532 010 33 44', 'email' => 'hazal.kaya@netlog.com.tr'],
            ['name' => 'İlker Kaleli', 'company_name' => 'Otokar Otomotiv', 'title' => 'Savunma & Ticari Satış Direktörü', 'stage' => 'hot', 'phone' => '+90 533 121 44 55', 'email' => 'ilker.kaleli@otokar.com.tr'],
            ['name' => 'Jale Arıkan', 'company_name' => 'Hergüner Bilgen Üçer Hukuk', 'title' => 'Kıdemli Ortak Avukat', 'stage' => 'warm', 'phone' => '+90 534 232 55 66', 'email' => 'jale.arikan@herguner.com.tr'],
            ['name' => 'Kerem Bürsin', 'company_name' => 'OMSAN Lojistik', 'title' => 'Otomotiv Lojistiği Grup Müdürü', 'stage' => 'hot', 'phone' => '+90 535 343 66 77', 'email' => 'kerem.bursin@omsan.com.tr'],
            ['name' => 'Leyla Lydia Tuğutlu', 'company_name' => 'Santa Farma İlaç', 'title' => 'Dış İlişkiler & Tanıtım Lideri', 'stage' => 'warm', 'phone' => '+90 536 454 77 88', 'email' => 'leyla.tugutlu@santafarma.com.tr'],
            ['name' => 'Mert Fırat', 'company_name' => 'Emlak Konut GYO', 'title' => 'Kurumsal İletişim & Pazarlama', 'stage' => 'hot', 'phone' => '+90 537 565 88 99', 'email' => 'mert.firat@emlakkonut.com.tr'],
        ];

        foreach ($companies as $company) {
            $companyAdmin = User::where('company_id', $company->id)->whereIn('role', ['company_admin', 'superadmin'])->first();
            $adminLeaderId = $companyAdmin ? $companyAdmin->id : null;

            // Seed 20 Staff
            $createdStaffUsers = [];
            $leaderUsers = [];

            foreach ($staffTemplates as $index => $t) {
                $emailDomain = $company->slug === 'vedubox' ? 'vedubox.com' : ($company->slug . '.com');
                $email = $t['email_prefix'] . '@' . $emailDomain;

                $user = User::updateOrCreate(
                    [
                        'company_id' => $company->id,
                        'email' => $email,
                    ],
                    [
                        'name' => $t['name'],
                        'password' => Hash::make('password'),
                        'role' => 'staff',
                        'phone' => $t['phone'],
                        'title' => $t['title'],
                        'department' => $t['department'],
                        'status' => 'active',
                        'leader_id' => (!empty($t['is_leader'])) ? $adminLeaderId : ($leaderUsers[0]->id ?? $adminLeaderId),
                    ]
                );

                if (!empty($t['is_leader'])) {
                    $leaderUsers[] = $user;
                }
                $createdStaffUsers[] = $user;

                // Business Card
                $slug = Str::slug($t['name']) . '-' . $company->id;
                BusinessCard::updateOrCreate(
                    [
                        'user_id' => $user->id,
                    ],
                    [
                        'company_id' => $company->id,
                        'slug' => $slug,
                        'avatar_url' => 'avatar.png',
                        'bio' => $t['bio'],
                        'direct_phone' => $t['phone'],
                        'work_email' => $email,
                        'work_address' => $company->address ?: 'İstanbul',
                        'website' => $company->website ?: 'https://monacard.com',
                        'theme_color' => $company->brand_color ?: '#00A86B',
                        'view_count' => rand(150, 950),
                        'vcard_download_count' => rand(30, 280),
                        'is_active' => true,
                    ]
                );
            }

            // Seed 60 Customers
            foreach ($customerTemplates as $cIdx => $cTemp) {
                $assignedStaff = $createdStaffUsers[$cIdx % count($createdStaffUsers)] ?? $companyAdmin;

                $customer = Customer::updateOrCreate(
                    [
                        'company_id' => $company->id,
                        'email' => $cTemp['email'],
                    ],
                    [
                        'staff_id' => $assignedStaff ? $assignedStaff->id : null,
                        'name' => $cTemp['name'],
                        'company_name' => $cTemp['company_name'],
                        'title' => $cTemp['title'],
                        'phone' => $cTemp['phone'],
                        'stage' => $cTemp['stage'],
                        'source' => ['nfc_tap', 'ocr_scan', 'manual', 'hubspot_sync'][$cIdx % 4],
                        'last_contact_at' => now()->subDays(rand(0, 20))->subHours(rand(1, 12)),
                    ]
                );

                // Notes
                InteractionNote::updateOrCreate(
                    [
                        'customer_id' => $customer->id,
                        'content' => "{$cTemp['company_name']} yetkilisi {$cTemp['name']} ile görüşüldü. Dijital kartvizit ve kurumsal CRM lisansı için teklif hazırlandı.",
                    ],
                    [
                        'staff_id' => $assignedStaff ? $assignedStaff->id : null,
                        'company_id' => $company->id,
                        'type' => ($cIdx % 3 === 0) ? 'voice' : 'text',
                        'ai_summary' => "{$cTemp['company_name']} için MonaCard kurumsal paket değerlendirmesi.",
                        'hubspot_synced' => true,
                        'salesforce_synced' => ($cIdx % 2 === 0),
                    ]
                );

                // Meetings for some customers
                if ($cIdx % 4 === 0) {
                    Meeting::updateOrCreate(
                        [
                            'company_id' => $company->id,
                            'title' => "{$cTemp['company_name']} - MonaCard Ürün Demosu & Teklif",
                            'start_time' => now()->addDays(rand(1, 14))->setHour(rand(10, 16))->setMinute(0)->setSecond(0),
                        ],
                        [
                            'staff_id' => $assignedStaff ? $assignedStaff->id : ($companyAdmin ? $companyAdmin->id : $createdStaffUsers[0]->id),
                            'meeting_type' => 'meet',
                            'meeting_link' => 'https://meet.google.com/abc-defg-hij',
                            'location' => 'Google Meet (Online)',
                            'status' => 'scheduled',
                            'participant_customer_ids' => [$customer->id],
                            'notes' => "Kurumsal entegrasyon ve ekip tanıtımı yapılacak.",
                        ]
                    );
                }
            }
        }
    }
}
