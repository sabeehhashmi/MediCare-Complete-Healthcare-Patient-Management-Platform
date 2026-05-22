<?php
// app/Http/Controllers/front/PharmacyDetailController.php

namespace App\Http\Controllers\front;

use App\Http\Controllers\Controller;
use App\Models\Medicine;
use Illuminate\Http\Request;

class PharmacyDetailController extends Controller
{
    public function show($slug)
    {
        $medicine = Medicine::with(['category', 'productTags'])
            ->where('slug', $slug)
            ->where('status', 1)
            ->firstOrFail();

        $page_heading = $medicine->title_en;

        $related_medicines = Medicine::with('category')
            ->where('medicin_category_id', $medicine->medicin_category_id)
            ->where('id', '!=', $medicine->id)
            ->where('status', 1)
            ->limit(4)
            ->get();

        return view('front.pharmacy-listdetail', compact('page_heading', 'medicine', 'related_medicines'));
    }
}