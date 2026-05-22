<?php

namespace App\Http\Controllers\front;

use App\Http\Controllers\Controller;
use App\Models\Medicine;
use App\Models\MedicinCategory;
use App\Models\ProductTag;
use Illuminate\Http\Request;

class PharmacyListController extends Controller
{
    public function index(Request $request)
    {
        $page_heading = "Pharmacy";
        
        $categories = MedicinCategory::where('status', 1)->withCount('medicines')
            ->orderBy('title', 'asc')
            ->get();
        
        $tags = ProductTag::where('status', 1)
            ->orderBy('name_en', 'asc')
            ->get();

        $query = Medicine::with(['category', 'productTags'])
            ->where('status', 1);

        // Apply category filter
        if ($request->has('category') && $request->category != '') {
            $query->where('medicin_category_id', $request->category);
        }

        // Apply tag filter
        if ($request->has('tag') && $request->tag != '') {
            $query->whereHas('productTags', function($q) use ($request) {
                $q->where('product_tags.id', $request->tag);
            });
        }

        // Apply search
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title_en', 'like', "%{$search}%")
                  ->orWhere('title_ar', 'like', "%{$search}%")
                  ->orWhere('title_bn', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Apply sorting
        $sort = $request->get('sort', 'latest');
        switch ($sort) {
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'name_asc':
                $query->orderBy('title_en', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('title_en', 'desc');
                break;
            default:
                $query->orderBy('id', 'desc');
                break;
        }

        $medicines = $query->paginate(9)->withQueryString();

        // Get cart items
        $cart_items = [];
        if (auth()->check()) {
            $cart_items = auth()->user()->carts()->pluck('medicine_id')->toArray();
        } else {
            $cart_items = \App\Models\Cart::where('session_id', session()->getId())
                ->pluck('medicine_id')
                ->toArray();
        }

        return view('front.pharmacy-list', compact(
            'page_heading', 
            'medicines', 
            'categories', 
            'tags', 
            'cart_items'
        ));
    }

    
}