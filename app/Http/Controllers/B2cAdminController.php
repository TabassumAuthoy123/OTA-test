<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class B2cAdminController extends Controller
{
    // ─── B2C SECTION ────────────────────────────────────────────────────────────

    public function flightBookings(Request $request)
    {
        if ($request->get('export') === 'excel') {
            return $this->exportB2cFlightsCsv($request);
        }
        $q = DB::table('flight_bookings as fb')
            ->join('users as u', 'u.id', '=', 'fb.user_id')
            ->where('u.user_type', 3)
            ->select(
                'fb.booking_no', 'u.name as username', 'u.email',
                'fb.pnr_id', 'fb.departure_location', 'fb.arrival_location',
                'fb.departure_date', 'fb.flight_type', 'fb.status',
                'fb.total_fare', 'fb.adult', 'fb.child', 'fb.infant', 'fb.created_at'
            );
        if ($request->filled('search')) {
            $s = $request->search;
            $q->where(function ($w) use ($s) {
                $w->where('fb.booking_no', 'like', "%$s%")
                  ->orWhere('u.name', 'like', "%$s%")
                  ->orWhere('fb.pnr_id', 'like', "%$s%");
            });
        }
        if ($request->filled('status') && $request->status !== 'all') {
            $q->where('fb.status', $request->status);
        }
        if ($request->filled('start_date')) {
            $q->whereDate('fb.created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $q->whereDate('fb.created_at', '<=', $request->end_date);
        }
        $bookings = $q->orderByDesc('fb.created_at')->paginate(15)->withQueryString();
        return view('b2c_admin.flight_bookings', compact('bookings'));
    }

    public function tourBookings(Request $request)
    {
        if ($request->get('export') === 'excel') {
            return $this->exportToursCsv($request);
        }
        $q = DB::table('tour_bookings')->whereNull('b2b_user_id');
        if ($request->filled('search')) {
            $s = $request->search;
            $q->where(function ($w) use ($s) {
                $w->where('booking_id', 'like', "%$s%")
                  ->orWhere('name', 'like', "%$s%")
                  ->orWhere('email', 'like', "%$s%");
            });
        }
        if ($request->filled('filter_status') && $request->filter_status !== 'all') {
            $q->where('status', $request->filter_status);
        }
        if ($request->filled('start_date')) {
            $q->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $q->whereDate('created_at', '<=', $request->end_date);
        }
        $bookings = $q->orderByDesc('created_at')->paginate(15)->withQueryString();
        $total = DB::table('tour_bookings')->whereNull('b2b_user_id')->count();
        return view('b2c_admin.tour_bookings', compact('bookings', 'total'));
    }

    public function userList(Request $request)
    {
        if ($request->get('export') === 'excel') {
            return $this->exportUsersCsv($request);
        }
        $q = DB::table('users')->where('user_type', 3);
        if ($request->filled('search')) {
            $s = $request->search;
            $q->where(function ($w) use ($s) {
                $w->where('name', 'like', "%$s%")
                  ->orWhere('email', 'like', "%$s%")
                  ->orWhere('phone', 'like', "%$s%");
            });
        }
        if ($request->filled('filter_status') && $request->filter_status !== 'all') {
            $q->where('status', $request->filter_status);
        }
        $users = $q->orderByDesc('created_at')->paginate(50)->withQueryString();
        return view('b2c_admin.user_list', compact('users'));
    }

    public function upcomingFlights(Request $request)
    {
        if ($request->get('export') === 'excel') {
            return $this->exportUpcomingCsv($request);
        }
        $q = DB::table('flight_bookings as fb')
            ->join('users as u', 'u.id', '=', 'fb.user_id')
            ->where('u.user_type', 3)
            ->where('fb.departure_date', '>=', date('Y-m-d'))
            ->whereNotIn('fb.status', [3, 4])
            ->select(
                'fb.booking_no', 'u.name as username', 'fb.pnr_id',
                'fb.departure_location', 'fb.arrival_location',
                'fb.departure_date', 'fb.status', 'fb.total_fare',
                'fb.flight_type', 'fb.adult', 'fb.child', 'fb.infant', 'fb.gds'
            );
        if ($request->filled('search')) {
            $s = $request->search;
            $q->where(function ($w) use ($s) {
                $w->where('fb.booking_no', 'like', "%$s%")
                  ->orWhere('u.name', 'like', "%$s%")
                  ->orWhere('fb.pnr_id', 'like', "%$s%");
            });
        }
        $bookings = $q->orderBy('fb.departure_date')->paginate(15)->withQueryString();
        return view('b2c_admin.upcoming_flights', compact('bookings'));
    }

    // ─── B2C CONFIGURATION ──────────────────────────────────────────────────────

    public function commission()
    {
        $rules = DB::table('markup_rules')->where('channel', 'b2c')->orderByDesc('created_at')->get();
        $commissionRules = DB::table('commission_rules')->orderBy('name')->get();
        return view('b2c_admin.commission', compact('rules', 'commissionRules'));
    }

    public function assignCommission(Request $request)
    {
        $request->validate(['name' => 'required|string|max:100']);
        DB::table('markup_rules')->insert([
            'name'         => $request->name,
            'channel'      => 'b2c',
            'gds'          => 'all',
            'markup_type'  => 'percentage',
            'markup_value' => 0,
            'is_active'    => 1,
            'priority'     => 10,
            'created_at'   => now(),
            'updated_at'   => now(),
        ]);
        return back()->with('success', 'Commission set assigned.');
    }

    public function termsConditions()
    {
        $page = DB::table('cms_pages')->where('slug', 'terms-conditions')->first();
        return view('b2c_admin.terms_conditions', compact('page'));
    }

    public function saveTermsConditions(Request $request)
    {
        $request->validate(['content' => 'required']);
        $exists = DB::table('cms_pages')->where('slug', 'terms-conditions')->first();
        if ($exists) {
            DB::table('cms_pages')->where('slug', 'terms-conditions')->update([
                'content'    => $request->content,
                'updated_at' => now(),
            ]);
        } else {
            DB::table('cms_pages')->insert([
                'title'      => 'Terms & Conditions',
                'slug'       => 'terms-conditions',
                'content'    => $request->content,
                'is_active'  => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        return back()->with('success', 'Terms & Conditions saved.');
    }

    public function privacyPolicy()
    {
        $page = DB::table('cms_pages')->where('slug', 'privacy-policy')->first();
        return view('b2c_admin.privacy_policy', compact('page'));
    }

    public function savePrivacyPolicy(Request $request)
    {
        $request->validate(['content' => 'required']);
        $exists = DB::table('cms_pages')->where('slug', 'privacy-policy')->first();
        if ($exists) {
            DB::table('cms_pages')->where('slug', 'privacy-policy')->update([
                'content'    => $request->content,
                'updated_at' => now(),
            ]);
        } else {
            DB::table('cms_pages')->insert([
                'title'      => 'Privacy Policy',
                'slug'       => 'privacy-policy',
                'content'    => $request->content,
                'is_active'  => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        return back()->with('success', 'Privacy Policy saved.');
    }

    public function coinConfig()
    {
        $config = DB::table('b2c_coin_configs')->latest()->first();
        $transactions = DB::table('b2c_coin_configs')->orderByDesc('updated_at')->get();
        return view('b2c_admin.coin_config', compact('config', 'transactions'));
    }

    public function saveCoinConfig(Request $request)
    {
        $request->validate([
            'taka_per_coin'      => 'required|numeric|min:0',
            'coin_value'         => 'required|numeric|min:0',
            'min_redeem_coins'   => 'required|numeric|min:0',
            'max_redeem_percent' => 'required|numeric|min:0|max:100',
        ]);
        $data = [
            'taka_per_coin'      => $request->taka_per_coin,
            'coin_value'         => $request->coin_value,
            'min_redeem_coins'   => $request->min_redeem_coins,
            'max_redeem_percent' => $request->max_redeem_percent,
            'is_active'          => $request->has('is_active') ? 1 : 0,
            'updated_by'         => Auth::user()->name,
            'updated_at'         => now(),
        ];
        $existing = DB::table('b2c_coin_configs')->first();
        if ($existing) {
            DB::table('b2c_coin_configs')->where('id', $existing->id)->update($data);
        } else {
            $data['created_at'] = now();
            DB::table('b2c_coin_configs')->insert($data);
        }
        return back()->with('success', 'Coin configuration saved.');
    }

    public function gallery(Request $request)
    {
        $q = DB::table('gallery_items');
        if ($request->filled('search')) {
            $q->where('title', 'like', '%' . $request->search . '%');
        }
        $items = $q->orderByDesc('created_at')->paginate(15)->withQueryString();
        return view('b2c_admin.gallery', compact('items'));
    }

    public function storeGallery(Request $request)
    {
        $request->validate(['title' => 'required|string|max:200', 'section_type' => 'required']);
        $filePath = null;
        $fileSize = 0;
        $mediaType = 'other';
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileSize = round($file->getSize() / (1024 * 1024), 2);
            $ext = strtolower($file->getClientOriginalExtension());
            if (in_array($ext, ['mp4', 'avi', 'mov', 'wmv'])) {
                $mediaType = 'video';
            } elseif (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                $mediaType = 'image';
            }
            $name = time() . '_' . Str::slug($request->title) . '.' . $ext;
            $file->move(public_path('uploads/gallery'), $name);
            $filePath = 'uploads/gallery/' . $name;
        }
        DB::table('gallery_items')->insert([
            'title'        => $request->title,
            'description'  => $request->description,
            'section_type' => $request->section_type,
            'media_type'   => $mediaType,
            'file_path'    => $filePath,
            'file_size_mb' => $fileSize,
            'duration'     => null,
            'created_at'   => now(),
            'updated_at'   => now(),
        ]);
        return back()->with('success', 'Media added.');
    }

    public function updateGallery(Request $request, $id)
    {
        $request->validate(['title' => 'required|string|max:200', 'section_type' => 'required']);
        $data = [
            'title'        => $request->title,
            'description'  => $request->description,
            'section_type' => $request->section_type,
            'updated_at'   => now(),
        ];
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileSize = round($file->getSize() / (1024 * 1024), 2);
            $ext = strtolower($file->getClientOriginalExtension());
            $mediaType = 'other';
            if (in_array($ext, ['mp4', 'avi', 'mov', 'wmv'])) $mediaType = 'video';
            elseif (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) $mediaType = 'image';
            $name = time() . '_' . Str::slug($request->title) . '.' . $ext;
            $file->move(public_path('uploads/gallery'), $name);
            $data['file_path']    = 'uploads/gallery/' . $name;
            $data['file_size_mb'] = $fileSize;
            $data['media_type']   = $mediaType;
        }
        DB::table('gallery_items')->where('id', $id)->update($data);
        return back()->with('success', 'Media updated.');
    }

    public function deleteGallery($id)
    {
        DB::table('gallery_items')->where('id', $id)->delete();
        return back()->with('success', 'Media deleted.');
    }

    public function socialMedia(Request $request)
    {
        $q = DB::table('social_media_links');
        if ($request->filled('search')) $q->where('name', 'like', '%' . $request->search . '%');
        $items = $q->orderByDesc('created_at')->get();
        return view('b2c_admin.social_media', compact('items'));
    }

    public function storeSocialMedia(Request $request)
    {
        $request->validate(['name' => 'required', 'link' => 'required|url']);
        $logo = null;
        if ($request->hasFile('logo')) {
            $f = $request->file('logo');
            $n = time() . '_' . $f->getClientOriginalName();
            $f->move(public_path('uploads/social'), $n);
            $logo = 'uploads/social/' . $n;
        }
        DB::table('social_media_links')->insert(['name' => $request->name, 'link' => $request->link, 'logo' => $logo, 'created_at' => now(), 'updated_at' => now()]);
        return back()->with('success', 'Social media added.');
    }

    public function updateSocialMedia(Request $request, $id)
    {
        $request->validate(['name' => 'required', 'link' => 'required|url']);
        $data = ['name' => $request->name, 'link' => $request->link, 'updated_at' => now()];
        if ($request->hasFile('logo')) {
            $f = $request->file('logo');
            $n = time() . '_' . $f->getClientOriginalName();
            $f->move(public_path('uploads/social'), $n);
            $data['logo'] = 'uploads/social/' . $n;
        }
        DB::table('social_media_links')->where('id', $id)->update($data);
        return back()->with('success', 'Updated.');
    }

    public function deleteSocialMedia($id)
    {
        DB::table('social_media_links')->where('id', $id)->delete();
        return back()->with('success', 'Deleted.');
    }

    public function youtubeLinks(Request $request)
    {
        $q = DB::table('youtube_links');
        if ($request->filled('search')) $q->where('name', 'like', '%' . $request->search . '%');
        $items = $q->orderByDesc('created_at')->get();
        return view('b2c_admin.youtube_links', compact('items'));
    }

    public function storeYoutubeLink(Request $request)
    {
        $request->validate(['name' => 'required', 'link' => 'required|url']);
        $logo = null;
        if ($request->hasFile('logo')) {
            $f = $request->file('logo');
            $n = time() . '_' . $f->getClientOriginalName();
            $f->move(public_path('uploads/youtube'), $n);
            $logo = 'uploads/youtube/' . $n;
        }
        DB::table('youtube_links')->insert(['name' => $request->name, 'link' => $request->link, 'logo' => $logo, 'created_at' => now(), 'updated_at' => now()]);
        return back()->with('success', 'YouTube link added.');
    }

    public function updateYoutubeLink(Request $request, $id)
    {
        $data = ['name' => $request->name, 'link' => $request->link, 'updated_at' => now()];
        if ($request->hasFile('logo')) {
            $f = $request->file('logo');
            $n = time() . '_' . $f->getClientOriginalName();
            $f->move(public_path('uploads/youtube'), $n);
            $data['logo'] = 'uploads/youtube/' . $n;
        }
        DB::table('youtube_links')->where('id', $id)->update($data);
        return back()->with('success', 'Updated.');
    }

    public function deleteYoutubeLink($id)
    {
        DB::table('youtube_links')->where('id', $id)->delete();
        return back()->with('success', 'Deleted.');
    }

    public function filmWatch(Request $request)
    {
        $q = DB::table('film_watch_links');
        if ($request->filled('search')) $q->where('name', 'like', '%' . $request->search . '%');
        $items = $q->orderByDesc('created_at')->get();
        return view('b2c_admin.film_watch', compact('items'));
    }

    public function storeFilmWatch(Request $request)
    {
        $request->validate(['name' => 'required', 'link' => 'required|url']);
        $logo = null;
        if ($request->hasFile('logo')) {
            $f = $request->file('logo');
            $n = time() . '_' . $f->getClientOriginalName();
            $f->move(public_path('uploads/filmwatch'), $n);
            $logo = 'uploads/filmwatch/' . $n;
        }
        DB::table('film_watch_links')->insert(['name' => $request->name, 'link' => $request->link, 'logo' => $logo, 'created_at' => now(), 'updated_at' => now()]);
        return back()->with('success', 'Film Watch link added.');
    }

    public function updateFilmWatch(Request $request, $id)
    {
        $data = ['name' => $request->name, 'link' => $request->link, 'updated_at' => now()];
        if ($request->hasFile('logo')) {
            $f = $request->file('logo');
            $n = time() . '_' . $f->getClientOriginalName();
            $f->move(public_path('uploads/filmwatch'), $n);
            $data['logo'] = 'uploads/filmwatch/' . $n;
        }
        DB::table('film_watch_links')->where('id', $id)->update($data);
        return back()->with('success', 'Updated.');
    }

    public function deleteFilmWatch($id)
    {
        DB::table('film_watch_links')->where('id', $id)->delete();
        return back()->with('success', 'Deleted.');
    }

    public function popularDestinations(Request $request)
    {
        $q = DB::table('popular_destinations');
        if ($request->filled('search')) $q->where('name', 'like', '%' . $request->search . '%');
        $items = $q->orderByDesc('created_at')->get();
        return view('b2c_admin.popular_destinations', compact('items'));
    }

    public function storeDestination(Request $request)
    {
        $request->validate(['name' => 'required', 'description' => 'required']);
        $image = null;
        if ($request->hasFile('image')) {
            $f = $request->file('image');
            $n = time() . '_' . Str::slug($request->name) . '.' . $f->getClientOriginalExtension();
            $f->move(public_path('uploads/destinations'), $n);
            $image = 'uploads/destinations/' . $n;
        }
        DB::table('popular_destinations')->insert(['name' => $request->name, 'description' => $request->description, 'image' => $image, 'created_at' => now(), 'updated_at' => now()]);
        return back()->with('success', 'Destination added.');
    }

    public function updateDestination(Request $request, $id)
    {
        $request->validate(['name' => 'required', 'description' => 'required']);
        $data = ['name' => $request->name, 'description' => $request->description, 'updated_at' => now()];
        if ($request->hasFile('image')) {
            $f = $request->file('image');
            $n = time() . '_' . Str::slug($request->name) . '.' . $f->getClientOriginalExtension();
            $f->move(public_path('uploads/destinations'), $n);
            $data['image'] = 'uploads/destinations/' . $n;
        }
        DB::table('popular_destinations')->where('id', $id)->update($data);
        return back()->with('success', 'Updated.');
    }

    public function deleteDestination($id)
    {
        DB::table('popular_destinations')->where('id', $id)->delete();
        return back()->with('success', 'Deleted.');
    }

    // ─── SPECIAL OFFERS ─────────────────────────────────────────────────────────

    public function specialOfferList(Request $request, $type)
    {
        $dbType = $this->resolveType($type);
        $q = DB::table('special_offers')->where('type', $dbType);
        if ($request->filled('search')) $q->where('title', 'like', '%' . $request->search . '%');
        if ($request->filled('filter_status') && $request->filter_status !== 'all') {
            $q->where('is_active', $request->filter_status);
        }
        if ($request->filled('start_date')) $q->whereDate('created_at', '>=', $request->start_date);
        if ($request->filled('end_date'))   $q->whereDate('created_at', '<=', $request->end_date);
        $offers = $q->orderByDesc('created_at')->get();
        $total = $offers->count();
        $typeLabel = $this->typeLabel($type);
        return view('b2c_admin.special_offer_list', compact('offers', 'total', 'type', 'typeLabel'));
    }

    public function createOffer($type)
    {
        $typeLabel = $this->typeLabel($type);
        return view('b2c_admin.special_offer_create', compact('type', 'typeLabel'));
    }

    public function storeOffer(Request $request, $type)
    {
        $dbType = $this->resolveType($type);
        $photo = null;
        if ($request->hasFile('photo')) {
            $f = $request->file('photo');
            $n = time() . '_' . $f->getClientOriginalName();
            $f->move(public_path('uploads/offers'), $n);
            $photo = 'uploads/offers/' . $n;
        }
        DB::table('special_offers')->insert([
            'type'        => $dbType,
            'title'       => $request->title,
            'description' => $request->description,
            'photo'       => $photo,
            'link'        => $request->link,
            'is_active'   => 1,
            'user_id'     => Auth::id(),
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);
        return redirect(url('special-offer/lists/' . $type))->with('success', $this->typeLabel($type) . ' created.');
    }

    public function detailsOffer($id)
    {
        $offer = DB::table('special_offers')->where('id', $id)->first();
        if (!$offer) abort(404);
        return view('b2c_admin.special_offer_details', compact('offer'));
    }

    public function editOffer($type, $id)
    {
        $offer = DB::table('special_offers')->where('id', $id)->first();
        if (!$offer) abort(404);
        $typeLabel = $this->typeLabel($type);
        return view('b2c_admin.special_offer_update', compact('offer', 'type', 'typeLabel'));
    }

    public function updateOffer(Request $request, $type, $id)
    {
        $data = ['title' => $request->title, 'description' => $request->description, 'link' => $request->link, 'updated_at' => now()];
        if ($request->hasFile('photo')) {
            $f = $request->file('photo');
            $n = time() . '_' . $f->getClientOriginalName();
            $f->move(public_path('uploads/offers'), $n);
            $data['photo'] = 'uploads/offers/' . $n;
        }
        DB::table('special_offers')->where('id', $id)->update($data);
        return redirect(url('special-offer/lists/' . $type))->with('success', 'Updated.');
    }

    public function deleteOffer($id)
    {
        $offer = DB::table('special_offers')->where('id', $id)->first();
        $type = $offer ? $offer->type : 'hot_deal';
        DB::table('special_offers')->where('id', $id)->delete();
        $typeKey = $type === 'hot_deal' ? 'offer' : $type;
        return redirect(url('special-offer/lists/' . $typeKey))->with('success', 'Deleted.');
    }

    public function toggleOfferActive(Request $request, $id)
    {
        $offer = DB::table('special_offers')->where('id', $id)->first();
        if ($offer) {
            DB::table('special_offers')->where('id', $id)->update(['is_active' => $offer->is_active ? 0 : 1, 'updated_at' => now()]);
        }
        return response()->json(['success' => true]);
    }

    public function bannerList()
    {
        $banners = DB::table('special_offers')->where('type', 'banner')->orderByDesc('created_at')->get();
        return view('b2c_admin.banner_list', compact('banners'));
    }

    public function editBanner($id)
    {
        $offer = DB::table('special_offers')->where('id', $id)->where('type', 'banner')->first();
        if (!$offer) {
            $offer = (object)['id' => 0, 'photo' => null, 'link' => '', 'type' => 'banner', 'title' => '', 'is_active' => 1];
        }
        return view('b2c_admin.banner_update', compact('offer'));
    }

    public function updateBanner(Request $request, $id)
    {
        $data = ['link' => $request->link, 'title' => $request->title, 'updated_at' => now()];
        if ($request->hasFile('photo')) {
            $f = $request->file('photo');
            $n = time() . '_' . $f->getClientOriginalName();
            $f->move(public_path('uploads/offers'), $n);
            $data['photo'] = 'uploads/offers/' . $n;
        }
        if ($id == 0) {
            $data['type'] = 'banner';
            $data['is_active'] = 1;
            $data['user_id'] = Auth::id();
            $data['created_at'] = now();
            DB::table('special_offers')->insert($data);
        } else {
            DB::table('special_offers')->where('id', $id)->update($data);
        }
        return back()->with('success', 'Banner updated.');
    }

    public function storeBanner(Request $request)
    {
        $data = ['type' => 'banner', 'title' => $request->title, 'link' => $request->link, 'is_active' => 1, 'user_id' => Auth::id(), 'created_at' => now(), 'updated_at' => now()];
        if ($request->hasFile('photo')) {
            $f = $request->file('photo');
            $n = time() . '_' . $f->getClientOriginalName();
            $f->move(public_path('uploads/offers'), $n);
            $data['photo'] = 'uploads/offers/' . $n;
        }
        DB::table('special_offers')->insert($data);
        return back()->with('success', 'Banner added.');
    }

    public function deleteBanner($id)
    {
        DB::table('special_offers')->where('id', $id)->where('type', 'banner')->delete();
        return back()->with('success', 'Banner deleted.');
    }

    public function footerInfo()
    {
        $info = DB::table('b2c_footer_infos')->first();
        $social = $info ? json_decode($info->social_links ?? '[]', true) : [];
        $footerSections = $info ? json_decode($info->footer_sections ?? '{}', true) : [];
        $companyLinks = $info ? json_decode($info->company_links ?? '[]', true) : [];
        $supportLinks = $info ? json_decode($info->support_links ?? '[]', true) : [];
        $certifications = $info ? json_decode($info->certifications ?? '{}', true) : [];
        $certCodes = isset($certifications['codes']) ? $certifications['codes'] : [];
        $paymentMethods = $info ? json_decode($info->payment_methods ?? '[]', true) : [];
        return view('b2c_admin.footer_info', compact('info', 'social', 'footerSections', 'companyLinks', 'supportLinks', 'certifications', 'certCodes', 'paymentMethods'));
    }

    public function saveFooterInfo(Request $request)
    {
        $social = [];
        $names = $request->input('social_name', []);
        $links = $request->input('social_link', []);
        foreach ($names as $i => $name) {
            if ($name) $social[] = ['name' => $name, 'link' => $links[$i] ?? ''];
        }

        $companyLinks = [];
        $clLabels = $request->input('company_link_label', []);
        $clUrls   = $request->input('company_link_url', []);
        foreach ($clLabels as $i => $label) {
            if ($label) $companyLinks[] = ['label' => $label, 'url' => $clUrls[$i] ?? ''];
        }

        $supportLinks = [];
        $slLabels = $request->input('support_link_label', []);
        $slUrls   = $request->input('support_link_url', []);
        foreach ($slLabels as $i => $label) {
            if ($label) $supportLinks[] = ['label' => $label, 'url' => $slUrls[$i] ?? ''];
        }

        $paymentMethods = [];
        $pmNames = $request->input('payment_method_name', []);
        $pmLogos = $request->input('payment_method_logo', []);
        foreach ($pmNames as $i => $name) {
            if ($name) $paymentMethods[] = ['name' => $name, 'logo' => $pmLogos[$i] ?? ''];
        }

        $data = [
            'company_info'    => $request->company_info,
            'social_links'    => json_encode($social),
            'footer_sections' => json_encode([]),
            'company_links'   => json_encode($companyLinks),
            'support_links'   => json_encode($supportLinks),
            'certifications'  => json_encode([]),
            'payment_methods' => json_encode($paymentMethods),
            'updated_at'      => now(),
        ];
        $existing = DB::table('b2c_footer_infos')->first();
        if ($existing) {
            DB::table('b2c_footer_infos')->where('id', $existing->id)->update($data);
        } else {
            $data['created_at'] = now();
            DB::table('b2c_footer_infos')->insert($data);
        }
        return back()->with('success', 'Footer info saved.');
    }

    // ─── Private helpers ────────────────────────────────────────────────────────

    private function resolveType($key)
    {
        $map = ['offer' => 'hot_deal', 'ad' => 'ad', 'banner' => 'banner'];
        return $map[$key] ?? 'hot_deal';
    }

    private function typeLabel($key)
    {
        $map = ['offer' => 'Hot Deals', 'ad' => 'AD', 'banner' => 'Banner'];
        return $map[$key] ?? 'Offer';
    }

    public static function bookingStatusLabel($status)
    {
        $map = [0 => 'Booking Request', 1 => 'Booked', 2 => 'Issued', 3 => 'Cancelled', 4 => 'Ticket Cancelled'];
        return isset($map[$status]) ? $map[$status] : 'Unknown';
    }

    public static function journeyTypeLabel($type)
    {
        $map = [1 => 'One Way', 2 => 'Round Trip', 3 => 'Multi City'];
        return isset($map[$type]) ? $map[$type] : 'N/A';
    }

    private function exportB2cFlightsCsv(Request $request)
    {
        $rows = DB::table('flight_bookings as fb')
            ->join('users as u', 'u.id', '=', 'fb.user_id')
            ->where('u.user_type', 3)
            ->select('fb.booking_no', 'u.name as username', 'fb.pnr_id', 'fb.departure_location', 'fb.arrival_location', 'fb.departure_date', 'fb.status', 'fb.total_fare', 'fb.flight_type', 'fb.adult', 'fb.child', 'fb.infant')
            ->get()->toArray();
        return $this->exportCsv($rows, 'b2c_flight_bookings.csv', ['booking_no', 'username', 'pnr_id', 'departure_location', 'arrival_location', 'departure_date', 'status', 'total_fare', 'flight_type', 'adult', 'child', 'infant']);
    }

    private function exportToursCsv(Request $request)
    {
        $rows = DB::table('tour_bookings')->whereNull('b2b_user_id')->get()->toArray();
        return $this->exportCsv($rows, 'b2c_tour_bookings.csv', ['id', 'booking_id', 'name', 'email', 'tour_type', 'travel_date', 'amount', 'status']);
    }

    private function exportUsersCsv(Request $request)
    {
        $rows = DB::table('users')->where('user_type', 3)->get()->toArray();
        return $this->exportCsv($rows, 'b2c_users.csv', ['id', 'name', 'email', 'phone', 'status', 'created_at']);
    }

    private function exportUpcomingCsv(Request $request)
    {
        $rows = DB::table('flight_bookings as fb')
            ->join('users as u', 'u.id', '=', 'fb.user_id')
            ->where('u.user_type', 3)
            ->where('fb.departure_date', '>=', date('Y-m-d'))
            ->whereNotIn('fb.status', [3, 4])
            ->select('fb.booking_no', 'u.name as username', 'fb.pnr_id', 'fb.departure_location', 'fb.arrival_location', 'fb.departure_date', 'fb.status', 'fb.total_fare')
            ->get()->toArray();
        return $this->exportCsv($rows, 'b2c_upcoming_flights.csv', ['booking_no', 'username', 'pnr_id', 'departure_location', 'arrival_location', 'departure_date', 'status', 'total_fare']);
    }

    private function exportCsv($rows, $filename, $columns)
    {
        return response()->stream(function () use ($rows, $columns) {
            $out = fopen('php://output', 'w');
            fputcsv($out, $columns);
            foreach ($rows as $row) {
                $line = [];
                $arr = is_object($row) ? (array) $row : $row;
                foreach ($columns as $col) $line[] = $arr[$col] ?? '';
                fputcsv($out, $line);
            }
            fclose($out);
        }, 200, [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }
}
