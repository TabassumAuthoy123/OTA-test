<?php

namespace App\Http\Controllers;

use App\Models\CmsBanner;
use App\Models\CmsPromotion;
use App\Models\CmsPopularRoute;
use App\Models\CmsTestimonial;
use App\Models\CmsPage;
use App\Models\CmsSiteSetting;
use App\Models\CmsFaq;
use App\Models\CmsPaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Brian2694\Toastr\Facades\Toastr;

class CmsCrudController extends Controller
{
    // ━━━━━ BANNERS ━━━━━
    public function banners()
    {
        $items = CmsBanner::orderBy('position')->get();
        return view('cms.banners', compact('items'));
    }

    public function storeBanner(Request $request)
    {
        $request->validate(['title' => 'required|max:255']);

        $image = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $name = Str::random(5) . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/cms/'), $name);
            $image = 'uploads/cms/' . $name;
        }

        CmsBanner::create([
            'title' => $request->title,
            'subtitle' => $request->subtitle,
            'image' => $image,
            'cta_text' => $request->cta_text,
            'cta_url' => $request->cta_url,
            'position' => $request->position ?? 0,
            'is_active' => $request->has('is_active'),
            'starts_at' => $request->starts_at,
            'expires_at' => $request->expires_at,
        ]);

        Toastr::success('Banner created successfully');
        return back();
    }

    public function updateBanner(Request $request, $id)
    {
        $banner = CmsBanner::findOrFail($id);
        $request->validate(['title' => 'required|max:255']);

        $data = $request->only(['title', 'subtitle', 'cta_text', 'cta_url', 'position', 'starts_at', 'expires_at']);
        $data['is_active'] = $request->has('is_active');

        if ($request->hasFile('image')) {
            if ($banner->image && file_exists(public_path($banner->image))) {
                @unlink(public_path($banner->image));
            }
            $file = $request->file('image');
            $name = Str::random(5) . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/cms/'), $name);
            $data['image'] = 'uploads/cms/' . $name;
        }

        $banner->update($data);
        Toastr::success('Banner updated');
        return back();
    }

    public function deleteBanner($id)
    {
        $banner = CmsBanner::findOrFail($id);
        if ($banner->image && file_exists(public_path($banner->image))) {
            @unlink(public_path($banner->image));
        }
        $banner->delete();
        return response()->json(['success' => true]);
    }

    // ━━━━━ PROMOTIONS ━━━━━
    public function promotions()
    {
        $items = CmsPromotion::orderBy('position')->get();
        return view('cms.promotions', compact('items'));
    }

    public function storePromotion(Request $request)
    {
        $request->validate(['title' => 'required|max:255']);

        $image = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $name = Str::random(5) . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/cms/'), $name);
            $image = 'uploads/cms/' . $name;
        }

        CmsPromotion::create([
            'title' => $request->title,
            'description' => $request->description,
            'discount_text' => $request->discount_text,
            'badge_text' => $request->badge_text,
            'badge_color' => $request->badge_color ?? '#FF6B35',
            'image' => $image,
            'url' => $request->url,
            'position' => $request->position ?? 0,
            'is_active' => $request->has('is_active'),
            'starts_at' => $request->starts_at,
            'expires_at' => $request->expires_at,
        ]);

        Toastr::success('Promotion created successfully');
        return back();
    }

    public function updatePromotion(Request $request, $id)
    {
        $promo = CmsPromotion::findOrFail($id);
        $data = $request->only(['title', 'description', 'discount_text', 'badge_text', 'badge_color', 'url', 'position', 'starts_at', 'expires_at']);
        $data['is_active'] = $request->has('is_active');

        if ($request->hasFile('image')) {
            if ($promo->image && file_exists(public_path($promo->image)))
                @unlink(public_path($promo->image));
            $file = $request->file('image');
            $name = Str::random(5) . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/cms/'), $name);
            $data['image'] = 'uploads/cms/' . $name;
        }

        $promo->update($data);
        Toastr::success('Promotion updated');
        return back();
    }

    public function deletePromotion($id)
    {
        $promo = CmsPromotion::findOrFail($id);
        if ($promo->image && file_exists(public_path($promo->image)))
            @unlink(public_path($promo->image));
        $promo->delete();
        return response()->json(['success' => true]);
    }

    // ━━━━━ POPULAR ROUTES ━━━━━
    public function popularRoutes()
    {
        $items = CmsPopularRoute::orderBy('position')->get();
        return view('cms.popular-routes', compact('items'));
    }

    public function storeRoute(Request $request)
    {
        $request->validate(['origin_city' => 'required', 'destination_city' => 'required']);

        $image = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $name = Str::random(5) . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/cms/'), $name);
            $image = 'uploads/cms/' . $name;
        }

        CmsPopularRoute::create([
            'origin_city' => $request->origin_city,
            'origin_code' => $request->origin_code,
            'destination_city' => $request->destination_city,
            'destination_code' => $request->destination_code,
            'starting_price' => $request->starting_price,
            'image' => $image,
            'position' => $request->position ?? 0,
            'is_active' => $request->has('is_active'),
        ]);

        Toastr::success('Popular route created successfully');
        return back();
    }

    public function updateRoute(Request $request, $id)
    {
        $route = CmsPopularRoute::findOrFail($id);
        $data = $request->only(['origin_city', 'origin_code', 'destination_city', 'destination_code', 'starting_price', 'position']);
        $data['is_active'] = $request->has('is_active');

        if ($request->hasFile('image')) {
            if ($route->image && file_exists(public_path($route->image)))
                @unlink(public_path($route->image));
            $file = $request->file('image');
            $name = Str::random(5) . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/cms/'), $name);
            $data['image'] = 'uploads/cms/' . $name;
        }

        $route->update($data);
        Toastr::success('Popular route updated');
        return back();
    }

    public function deleteRoute($id)
    {
        $route = CmsPopularRoute::findOrFail($id);
        if ($route->image && file_exists(public_path($route->image)))
            @unlink(public_path($route->image));
        $route->delete();
        return response()->json(['success' => true]);
    }

    // ━━━━━ TESTIMONIALS ━━━━━
    public function testimonials()
    {
        $items = CmsTestimonial::orderBy('position')->get();
        return view('cms.testimonials', compact('items'));
    }

    public function storeTestimonial(Request $request)
    {
        $request->validate(['customer_name' => 'required']);

        $avatar = null;
        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $name = Str::random(5) . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/cms/'), $name);
            $avatar = 'uploads/cms/' . $name;
        }

        CmsTestimonial::create([
            'customer_name' => $request->customer_name,
            'avatar' => $avatar,
            'rating' => $request->rating ?? 5,
            'review' => $request->review,
            'position' => $request->position ?? 0,
            'is_active' => $request->has('is_active'),
        ]);

        Toastr::success('Testimonial created successfully');
        return back();
    }

    public function updateTestimonial(Request $request, $id)
    {
        $item = CmsTestimonial::findOrFail($id);
        $data = $request->only(['customer_name', 'rating', 'review', 'position']);
        $data['is_active'] = $request->has('is_active');

        if ($request->hasFile('avatar')) {
            if ($item->avatar && file_exists(public_path($item->avatar)))
                @unlink(public_path($item->avatar));
            $file = $request->file('avatar');
            $name = Str::random(5) . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/cms/'), $name);
            $data['avatar'] = 'uploads/cms/' . $name;
        }

        $item->update($data);
        Toastr::success('Testimonial updated');
        return back();
    }

    public function deleteTestimonial($id)
    {
        $item = CmsTestimonial::findOrFail($id);
        if ($item->avatar && file_exists(public_path($item->avatar)))
            @unlink(public_path($item->avatar));
        $item->delete();
        return response()->json(['success' => true]);
    }

    // ━━━━━ STATIC PAGES ━━━━━
    public function pages()
    {
        $items = CmsPage::orderBy('title')->get();
        return view('cms.pages', compact('items'));
    }

    public function storePage(Request $request)
    {
        $request->validate(['title' => 'required', 'slug' => 'required|unique:cms_pages,slug', 'content' => 'required']);

        CmsPage::create([
            'title' => $request->title,
            'slug' => Str::slug($request->slug),
            'content' => $request->content,
            'meta_title' => $request->meta_title,
            'meta_description' => $request->meta_description,
            'is_active' => $request->has('is_active'),
        ]);

        Toastr::success('Page created successfully');
        return back();
    }

    public function editPage($id)
    {
        $page = CmsPage::findOrFail($id);
        return view('cms.edit-page', compact('page'));
    }

    public function updatePage(Request $request, $id)
    {
        $page = CmsPage::findOrFail($id);
        $request->validate(['title' => 'required', 'content' => 'required']);

        $page->update([
            'title' => $request->title,
            'slug' => Str::slug($request->slug ?? $page->slug),
            'content' => $request->content,
            'meta_title' => $request->meta_title,
            'meta_description' => $request->meta_description,
            'is_active' => $request->has('is_active'),
        ]);

        Toastr::success('Page updated');
        return redirect()->route('CmsPages');
    }

    public function deletePage($id)
    {
        CmsPage::findOrFail($id)->delete();
        return response()->json(['success' => true]);
    }

    // ━━━━━ SITE SETTINGS ━━━━━
    public function siteSettings()
    {
        $settings = CmsSiteSetting::allAsArray();
        $paymentMethods = CmsPaymentMethod::orderBy('position')->get();
        return view('cms.site-settings', compact('settings', 'paymentMethods'));
    }

    public function updateSiteSettings(Request $request)
    {
        $fields = [
            'hero_badge' => 'hero',
            'hero_title' => 'hero',
            'footer_phone' => 'footer_contact',
            'footer_email' => 'footer_contact',
            'footer_address' => 'footer_contact',
            'footer_description' => 'footer_contact',
            'social_facebook' => 'footer_social',
            'social_instagram' => 'footer_social',
            'social_twitter' => 'footer_social',
            'social_linkedin' => 'footer_social',
        ];

        foreach ($fields as $key => $group) {
            CmsSiteSetting::set($key, $request->input($key), $group);
        }

        Toastr::success('Site settings updated successfully');
        return back();
    }

    // ━━━━━ FAQs ━━━━━
    public function faqs()
    {
        $items = CmsFaq::orderBy('position')->get();
        return view('cms.faqs', compact('items'));
    }

    public function storeFaq(Request $request)
    {
        $request->validate(['question' => 'required', 'answer' => 'required']);

        CmsFaq::create([
            'question' => $request->question,
            'answer' => $request->answer,
            'position' => $request->position ?? 0,
            'is_active' => $request->has('is_active'),
        ]);

        Toastr::success('FAQ created successfully');
        return back();
    }

    public function updateFaq(Request $request, $id)
    {
        $faq = CmsFaq::findOrFail($id);

        $faq->update([
            'question' => $request->question,
            'answer' => $request->answer,
            'position' => $request->position ?? $faq->position,
            'is_active' => $request->has('is_active'),
        ]);

        Toastr::success('FAQ updated');
        return back();
    }

    public function deleteFaq($id)
    {
        CmsFaq::findOrFail($id)->delete();
        return response()->json(['success' => true]);
    }

    // ━━━━━ PAYMENT METHODS ━━━━━
    public function storePaymentMethod(Request $request)
    {
        $request->validate(['name' => 'required|max:255']);

        $image = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $name = Str::random(5) . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/cms/'), $name);
            $image = 'uploads/cms/' . $name;
        }

        CmsPaymentMethod::create([
            'name' => $request->name,
            'image' => $image,
            'position' => $request->position ?? 0,
            'is_active' => $request->has('is_active'),
        ]);

        Toastr::success('Payment method added');
        return back();
    }

    public function deletePaymentMethod($id)
    {
        $item = CmsPaymentMethod::findOrFail($id);
        if ($item->image && file_exists(public_path($item->image))) {
            @unlink(public_path($item->image));
        }
        $item->delete();
        return response()->json(['success' => true]);
    }
}
