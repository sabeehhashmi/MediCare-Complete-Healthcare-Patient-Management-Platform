<?php
// app/Http/Controllers/admin/MedicineController.php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\MedicinCategory;
use App\Models\Medicine;
use App\Models\ProductTag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class MedicineController extends Controller
{
    public function index()
    {
        if (!get_user_permission('medicines', 'r')) {
            return redirect()->route('admin.restricted_page');
        }

        $page_heading = "Medicines";
        $list = Medicine::with('category')->orderBy('id', 'desc')->get();
        return view('admin.medicines.list', compact('page_heading', 'list'));
    }

    public function create($id = '')
    {
        if (!get_user_permission('medicines', 'c')) {
            return redirect()->route('admin.restricted_page');
        }
        
        $medicin_categories = MedicinCategory::where('status', 1)->orderBy('title', 'asc')->get();
        $product_tags = ProductTag::where('status', 1)->orderBy('name_en', 'asc')->get();
        $page_heading = 'Medicine';
        $medicine = null;
        $selected_tags = [];

        if ($id) {
            $id = decrypt($id);
            $medicine = Medicine::with('productTags')->find($id);
            if ($medicine) {
                $selected_tags = $medicine->productTags->pluck('id')->toArray();
            }
        }
        
        return view('admin.medicines.create', compact('page_heading', 'id', 'medicine', 'medicin_categories', 'product_tags', 'selected_tags'));
    }

    public function submit(Request $request)
    {
        $status = "0";
        $message = "";
        $o_data = [];
        $errors = [];
        $o_data['redirect'] = route('admin.medicines.list');

        $rules = [
            'title_en' => [
                'required',
                'min:2',
                Rule::unique('medicines', 'title_en')
                    ->ignore($request->id)
                    ->whereNull('deleted_at'),
            ],
            'title_ar' => 'required',
            'title_bn' => 'required',
            'medicin_category_id' => 'required|exists:medicine_categories,id',
            'description' => 'required',
            'price' => 'required|numeric|min:0',
            'sku' => [
                'nullable',
                Rule::unique('medicines', 'sku')
                    ->when($request->id, function ($rule) use ($request) {
                        return $rule->ignore($request->id);
                    }),
            ],

            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'gallery_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $status = "0";
            $message = "Validation error occurred";
            $errors = $validator->messages();
        } else {
            DB::beginTransaction();
            try {
                $data = [
                    'title_en' => $request->title_en,
                    'title' => $request->title_en,
                    'title_ar' => $request->title_ar,
                    'title_bn' => $request->title_bn,
                    'slug' => Str::slug($request->title_en),
                    'medicin_category_id' => $request->medicin_category_id,
                    'description' => $request->description,
                    'short_description' => $request->short_description,
                    'price' => $request->price,
                    'discount_price' => $request->discount_price,
                    'sku' => $request->sku,
                    'stock_quantity' => $request->stock_quantity ?? 0,
                    'manufacturer' => $request->manufacturer,
                    'prescription_required' => $request->prescription_required ?? 0,
                    'uses' => $request->uses,
                    'side_effects' => $request->side_effects,
                    'benefits' => $request->benefits,
                    'how_to_use' => $request->how_to_use,
                    'other_info' => $request->other_info,
                    'meta_title' => $request->meta_title,
                    'meta_description' => $request->meta_description,
                    'meta_keywords' => $request->meta_keywords,
                    'status' => $request->status ?? 0,
                    'featured' => $request->featured ?? 0,
                    'last_updated_by' => Auth::user()->id ?? 0
                ];

                if ($request->id) {
                    $medicine = Medicine::find($request->id);
                    if (!$medicine) {
                        throw new Exception("Medicine not found");
                    }
                    
                    // Handle main image
                    if ($request->hasfile('image')) {
                        $file = $request->file('image');
                        $file_name = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                        $file->storeAs(config('global.medicine_image_upload_dir'), $file_name, config('global.upload_bucket'));
                        $data['image'] = $file_name;
                    }
                    
                    // Handle gallery images
                    if ($request->hasfile('gallery_images')) {
                        $gallery_images = [];
                        foreach ($request->file('gallery_images') as $gallery_image) {
                            $file_name = time() . '_' . uniqid() . '.' . $gallery_image->getClientOriginalExtension();
                            $gallery_image->storeAs(config('global.medicine_image_upload_dir'), $file_name, config('global.upload_bucket'));
                            $gallery_images[] = $file_name;
                        }
                        $data['gallery_images'] = json_encode($gallery_images);
                    }
                    
                    $medicine->update($data);
                    
                    // Sync tags
                    if ($request->has('tags')) {
                        $medicine->productTags()->sync($request->tags);
                    }
                    
                    $message = "Medicine updated successfully";
                } else {
                    $data['created_by'] = Auth::user()->id ?? 0;
                    
                    // Handle main image
                    if ($request->hasfile('image')) {
                        $file = $request->file('image');
                        $file_name = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                        $file->storeAs(config('global.medicine_image_upload_dir'), $file_name, config('global.upload_bucket'));
                        $data['image'] = $file_name;
                    }
                    
                    // Handle gallery images
                    if ($request->hasfile('gallery_images')) {
                        $gallery_images = [];
                        foreach ($request->file('gallery_images') as $gallery_image) {
                            $file_name = time() . '_' . uniqid() . '.' . $gallery_image->getClientOriginalExtension();
                            $gallery_image->storeAs(config('global.medicine_image_upload_dir'), $file_name, config('global.upload_bucket'));
                            $gallery_images[] = $file_name;
                        }
                        $data['gallery_images'] = json_encode($gallery_images);
                    }
                    
                    $medicine = Medicine::create($data);
                    
                    // Attach tags
                    if ($request->has('tags')) {
                        $medicine->productTags()->attach($request->tags);
                    }
                    
                    $message = "Medicine added successfully";
                }

                DB::commit();
                $status = "1";
                
            } catch (Exception $e) {
                DB::rollback();
                $message = "Failed to save medicine: " . $e->getMessage();
            }
        }

        return response()->json(['status' => $status, 'errors' => $errors, 'message' => $message, 'oData' => $o_data]);
    }

    public function change_status(Request $request)
    {
        $status = "0";
        $message = "";
        $id = $request->id;

        $medicine = Medicine::find($id);
        if ($medicine) {
            $medicine->status = $request->status;
            $medicine->save();
            $status = "1";
            $message = "Status changed successfully";
        } else {
            $message = "Failed to change status";
        }

        return response()->json(['status' => $status, 'message' => $message]);
    }

    public function delete($id)
    {
        $status = "0";
        $message = "";

        try {
            $id = decrypt($id);
            $medicine = Medicine::find($id);
            
            if ($medicine) {
                $medicine->delete();
                $message = "Medicine deleted successfully";
                $status = "1";
            } else {
                $message = "Medicine not found";
            }
        } catch (Exception $e) {
            $message = "Invalid ID format";
        }

        return response()->json(['status' => $status, 'message' => $message]);
    }

    public function toggle_featured(Request $request)
    {
        $status = "0";
        $message = "";
        $id = $request->id;

        $medicine = Medicine::find($id);
        if ($medicine) {
            $medicine->featured = $request->featured;
            $medicine->save();
            $status = "1";
            $message = "Featured status changed successfully";
        } else {
            $message = "Failed to change featured status";
        }

        return response()->json(['status' => $status, 'message' => $message]);
    }
}