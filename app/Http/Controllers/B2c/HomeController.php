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

class HomeController extends Controller
{
    /**
     * B2C Landing Page
     */
    public function index()
    {
        $banners = CmsBanner::active()->get();
        $promotions = CmsPromotion::active()->take(6)->get();
        $popularRoutes = CmsPopularRoute::active()->take(6)->get();
        $testimonials = CmsTestimonial::active()->take(3)->get();
        $siteSettings = CmsSiteSetting::allAsArray();
        $faqs = CmsFaq::active()->get();

        return view('b2c.home', compact(
            'banners',
            'promotions',
            'popularRoutes',
            'testimonials',
            'siteSettings',
            'faqs'
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

