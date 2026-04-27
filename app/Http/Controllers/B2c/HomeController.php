<?php

namespace App\Http\Controllers\B2c;

use App\Http\Controllers\Controller;
use App\Models\CmsBanner;
use App\Models\CmsPopularRoute;
use App\Models\CmsPromotion;
use App\Models\CmsTestimonial;
use App\Models\CmsPage;
use App\Models\CmsSiteSetting;
use App\Models\CmsFaq;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * B2C Landing Page
     */
    public function index()
    {
        $banners      = CmsBanner::active()->get();
        $promotions   = CmsPromotion::active()->take(6)->get();
        $popularRoutes = CmsPopularRoute::active()->take(6)->get();
        $testimonials = CmsTestimonial::active()->take(3)->get();
        $siteSettings = CmsSiteSetting::allAsArray();
        $faqs         = CmsFaq::active()->get();

        // ── B2C CMS Config data ──────────────────────────────────────────────
        $heroBanners        = DB::table('special_offers')->where('type', 'banner')->where('is_active', 1)->orderByDesc('created_at')->get();
        $hotDeals           = DB::table('special_offers')->where('type', 'hot_deal')->where('is_active', 1)->orderByDesc('created_at')->take(6)->get();
        $adBanners          = DB::table('special_offers')->where('type', 'ad')->where('is_active', 1)->orderByDesc('created_at')->take(3)->get();
        $popularDestinations = DB::table('popular_destinations')->orderBy('name')->get();
        $youtubeLinks       = DB::table('youtube_links')->orderByDesc('id')->get();
        $filmWatchLinks     = DB::table('film_watch_links')->orderByDesc('id')->get();
        $galleryItems       = DB::table('gallery_items')->orderByDesc('created_at')->take(12)->get();
        $socialMediaLinks   = DB::table('social_media_links')->orderBy('name')->get();
        $footerInfo         = DB::table('b2c_footer_infos')->first();

        return view('b2c.home', compact(
            'banners', 'promotions', 'popularRoutes', 'testimonials', 'siteSettings', 'faqs',
            'heroBanners', 'hotDeals', 'adBanners', 'popularDestinations',
            'youtubeLinks', 'filmWatchLinks', 'galleryItems', 'socialMediaLinks', 'footerInfo'
        ));
    }

    /**
     * Static CMS Page (About, Terms, Privacy, etc.)
     */
    public function page($slug)
    {
        $page = CmsPage::where('slug', $slug)->where('is_active', true)->firstOrFail();
        return view('b2c.page', compact('page'));
    }

    /**
     * Deals Page
     */
    public function deals()
    {
        $promotions = CmsPromotion::active()->get();
        return view('b2c.deals', compact('promotions'));
    }
}

