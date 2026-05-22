<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\DoctorLanguageSpoken;
use Illuminate\Http\Request;
use App\Models\Languages;
use App\Models\InsurencePolicy;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use PhpOffice\PhpSpreadsheet\IOFactory;

class LanguageController extends Controller
{
    //
    public function index()
    {
        if (!get_user_permission('languages', 'r')) {
            return redirect()->route('admin.restricted_page');
        }

        $page_heading = "Languages";
        $list = Languages::orderBy('id','desc')->get();
        $mode='list';
        return view('admin.languages.list', compact('mode', 'page_heading', 'list'));
    }

    public function create($id = '')
    {
        if (!get_user_permission('languages', 'c')) {
            return redirect()->route('admin.restricted_page');
        }
        $page_heading = 'Languages';
        $mode = "Create";
        $title_en  = '';
        $title_ar  = '';
        $status = '';


        if ($id) {
            $mode = "Edit";
            $id = decrypt($id);
            $role = Languages::find($id);
            $title_en = $role->title;
            $status = $role->status;
            $title_ar = $role->title_ar;
        }
        return view('admin.languages.create', compact('mode', 'page_heading', 'id', 'status', 'title_en', 'title_ar'));
    }

    public function submit(REQUEST $request)
    {

        $status     = "0";
        $message    = "";
        $o_data     = [];
        $errors     = [];
        $o_data['redirect'] = route('admin.languages.list');
        $rules = [
            'title_en' => [
                'required',
                'min:3',
                Rule::unique('languages', 'title')
                ->ignore($request->id)
                ->where(function ($query) {
                    // Adjust the query to handle deleted_at correctly
                    $query->whereNull('deleted_at'); // Assuming '1' is not a valid timestamp
                }),
            ],
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $status = "0";
            $message = "Validation error occured";
            $errors = $validator->messages();
        } else {
            $title_ar = $request->title_ar;
            $title_en  = $request->title_en;
            $status = $request->status;
            $id         = $request->id;
            $check      = Languages::whereRaw('Lower(title) = ?', [strtolower($title_en)])->where('id', '!=', $id)->get();

            if ($check->count() > 0) {
                $status = "0";
                $message = "Language Already Addded";
                $errors['title_en'] = 'Language Already Added';
                if($request->json_action){
                    return response()->json([
                        'success' => true,
                        'message' => "Language Already Added",
                        'id' => '',
                        'name' => ''
                    ]);
                }
            } else {
                if ($id) {
                    DB::beginTransaction();
                    try {
                        $role   = Languages::find($id);
                        $role->title    = $title_en;
                        $role->status  = $request->status;
                        $role->title_ar  = $title_ar;
                        $role->last_updated_by = Auth::user()->id??0;
                        $role->save();

                        DB::commit();
                        $status = "1";
                        $message = "Language updated Successfully";
                    } catch (Exception $e) {
                        DB::rollback();
                        $message = "Failed to create language " . $e->getMessage();
                    }
                } else {
                    DB::beginTransaction();
                    try {
                        $role   = new Languages();
                        $role->title    = $title_en;
                        $role->status  = $request->status;
                        $role->title_ar  = $title_ar;
                        $role->created_by = Auth::user()->id??0;
                        $role->last_updated_by = Auth::user()->id??0;
                        $role->save();
                        $role_id            = $role->id;

                        DB::commit();
                        $status = "1";
                        $message = "Language Added Successfully";
                        if($request->json_action){
                            return response()->json([
                                'success' => true,
                                'message' => "Language added successfully",
                                'id' => $role_id,
                                'name' => $request->title_en
                            ]);
                        }
                    } catch (EXCEPTION $e) {
                        DB::rollback();
                        $message = "Faild to create language " . $e->getMessage();
                    }
                }
            }
        }

        echo json_encode(['status' => $status, 'errors' => $errors, 'message' => $message, 'oData' => $o_data]);
    }

    public function change_status(REQUEST $request)
    {
        $status = "0";
        $message = "";
        $o_data  = [];
        $errors = [];

        $id = $request->id;

        $item = Languages::where(['id' => $id])->get();

        if ($item->count() > 0) {

            Languages::where('id', '=', $id)->update(['status' => $request->status]);
            $status = "1";
            $message = "Status changed successfully";
        } else {
            $message = "Faild to change status";
        }

        echo json_encode(['status' => $status, 'errors' => $errors, 'message' => $message, 'oData' => $o_data]);
    }

    public function delete(REQUEST $request, $id)
    {
        $status = "0";
        $message = "";

        $id = decrypt($id);

        $category_data = Languages::where(['id' => $id])->first();

        if ($category_data) {
            $languages = DoctorLanguageSpoken::where('language_spoken_id', $id)->count();
            if ($languages) {
                $message = "Language cannot be delete, because this language may have association with doctors.";
            } else {
                $category_data->delete();
                $message = "Language deleted successfully";
                $status = "1";
            }
        } else {
            $message = "Invalid Role data";
        }

        echo json_encode([
            'status' => $status, 'message' => $message
        ]);
    }


public function importLanguagesFromFile()
{
    // Specify the file path (adjust path if needed)
    $filePath = public_path('NEWINSURANCE.xlsx'); 

    // Check if the file exists
    if (!file_exists($filePath)) {
       dd('File not found.');
    }

    // Load the spreadsheet from the file path
    $spreadsheet = IOFactory::load($filePath);

    // Get the first worksheet
    $worksheet = $spreadsheet->getActiveSheet();
    $e=InsurencePolicy::query()->forceDelete();
    // Loop through rows of the worksheet
    foreach ($worksheet->getRowIterator() as $row) {
        $cellIterator = $row->getCellIterator();
        $cellIterator->setIterateOnlyExistingCells(false); // Loop through all cells, even empty ones

        $data = [];
        foreach ($cellIterator as $cell) {
            $data[] = $cell->getValue(); // Get the cell value
        }

        // Skip the header row (assuming the first row is the header)
        // if ($row->getRowIndex() === 1) {
        //     continue;
        // }
        // SpecialIntrests::where('id','!=',0)->delete();
        // exit('delete');
       // $language = Qualifications::where('title_en', 'like', substr($data[0], 0, 5) . '%')->first();
        
        // Save each row to the languages table
        // if(empty($language) && empty($languages)){
            
                if(!empty($data[0])){
            $new_record= new InsurencePolicy;
            $new_record->title=$data[0];
            $new_record->status=1;
            
            $new_record->last_updated_by=0;
            $new_record->save();
            
                }
    
    }
    echo 'uploaded'; exit;
    return redirect()->back()->with('success', 'Languages imported successfully!');
}

}
