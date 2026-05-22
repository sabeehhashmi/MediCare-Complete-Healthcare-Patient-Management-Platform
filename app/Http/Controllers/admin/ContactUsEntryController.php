<?php

namespace App\Http\Controllers\Admin;

use App\Exports\ExcelExporter;
use App\Http\Controllers\Controller;
use App\Models\ContactUsEntry;
use App\Models\ContactUsSetting;
use App\Models\Favourite;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Validator;
use App\Models\User;
use App\Models\VendorRating;

class ContactUsEntryController extends Controller
{



    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {


        // If it's not a reporting page then check the permission
        if (!get_user_permission('contact_us_entries', 'r')) {
            return redirect()->route('admin.restricted_page');
        }

        $page_heading = "Help Desk";


        // Get all the entries from the ContactUsEntry model with message with partial message
        $entries = ContactUsEntry::orderBy('id', 'desc')->paginate(10);

        return view('admin.contact_us.list', compact('page_heading', 'entries'));
    }



    public function show($contact_us)
    {
        if (!get_user_permission('contact_us_entries', 'c')) {
            return redirect()->route('admin.restricted_page');
        }

        $page_heading = "Help Desk";

        // Get the contact by the id
        $entry = ContactUsEntry::find($contact_us);

        if (!$entry) {
            return redirect()->route('admin.restricted_page');
        }


        return view('admin.contact_us.view', compact('page_heading', 'entry'));
    }


    public function indexAndCreate (Request $request) {

        if ($request->isMethod('post')) {

            return $this->store($request);

        } else {
            
            $page_heading = "Help Desk";

            // Get the Help Desk settings
            $contactUsSettings = ContactUsSetting::first()->toArray();
    
            return view('web.contact',compact('page_heading', 'contactUsSettings'));
        }
        
    }





    public function store(Request $request)
    {
        // validator for the name, email, dial_code, phone, message
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'subject' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'message' => 'required',
        ]);

        $status = 1;
        $message = "Form submitted successfully!";
        $errors = '';
        
        // If validation fails, return an error response
        if ($validator->fails()) {

            $status = "0";
            $message = "Validation error occured";
            $errors = $validator->messages();

            echo json_encode(['status' => $status, 'message' => $message, 'errors' => $errors]);die();

            return;

        }
        

        // Create the Help Desk entry
        $contactUsEntry = new ContactUsEntry();
        $contactUsEntry->name = $request->name;

        if ($request->email) {
            $contactUsEntry->email = $request->email;
        }
        

        if ($request->phone) {
            $contactUsEntry->phone = $request->phone;
        }

        if ($request->subject) {
            $contactUsEntry->subject = $request->subject;
        }

        $contactUsEntry->message = $request->message;

        $contactUsEntry->save();

        echo json_encode(['status' => $status, 'message' => $message, 'errors' => $errors]);die();
        return;

    }     

    public function updateStatus(Request $request, $contact_us)
{
    if (!get_user_permission('contact_us_entries', 'u')) {
        return redirect()->route('admin.restricted_page');
    }

    $entry = ContactUsEntry::find($contact_us);

    if (!$entry) {
        return redirect()->back()->with('error', 'Entry not found');
    }

    $request->validate([
        'status' => 'required|in:open,closed',
    ]);

    $entry->status = $request->status;
    $entry->save();

    return redirect()->back()->with('success', 'Status updated successfully');
}
    
}
