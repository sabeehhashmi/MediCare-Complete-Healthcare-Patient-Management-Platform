<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Banner;
use App\Models\HpManagement;

class HomepageManagementController extends Controller
{





    /**
     * Show the form for editing the specified resource.
     */
    public function edit()
    {
        if (!get_user_permission('homepage_management', 'u')) {
            return redirect()->route('admin.restricted_page');
        }

        $page_heading = "Homepage Management";

        // Get all rows and create the array which key should be the meta_key and value should be the meta_value using pluck
        $data = HpManagement::getAllMeta();
        

        return view("admin.homepage_management.create", compact('page_heading', 'data'));
    }

    /**
     * Update/store the specified resource in storage.
     */
    public function update(Request $request)
    {
        if (!get_user_permission('settings', 'u')) {
            return redirect()->route('admin.restricted_page');
        }


        // Get all the request data
        $data = $request->all();

        // Get all db data
        $db_data = HpManagement::getAllMeta();

        // Prepare the updates
        $updates = [];
        foreach ($data as $meta_key => $meta_value) {

            // If the meta key did not starts with frm_ then skip it
            if (strpos($meta_key, 'frm_') !== 0) {
                continue;
            }

            // if the meta value is null or empty then skip it
            if (empty($meta_value)) {
                continue;
            }

            // Split the $meta_key by the _ and get the last part
            $meta_key_parts = explode('_', $meta_key);
            $lastPart = end($meta_key_parts);

            // If it's img
            if ($lastPart == 'img') {
                $file = $request->file($meta_key);
                $file_name = time() . uniqid() . "." . $file->getClientOriginalExtension();
                $file->storeAs(config('global.homepage_image_upload_dir'), $file_name, config('global.upload_bucket'));
                $meta_value = $file_name;
            }

            // if the meta value is not same as the db value then add it to the updates array
            if (isset($db_data[$meta_key]) && $db_data[$meta_key] == $meta_value) {
                continue;
            }

            $updates[] = [
                'meta_key' => $meta_key,
                'meta_value' => $meta_value
            ];
        }

        // Perform the bulk update
        HpManagement::upsert($updates, ['meta_key'], ['meta_value']);

        // Return success
        return redirect()->route('admin.homepage-management')->with('success',  'Data Saved Successfully.');

       
    }
}
