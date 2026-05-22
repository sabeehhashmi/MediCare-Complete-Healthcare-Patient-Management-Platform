<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\UserInstruction;
use Validator;

class UserInstructionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!get_user_permission('faq','r')) {
            return redirect()->route('admin.restricted_page');
        }
        $page_heading = "User Instructions for Patients";
        $filter = [];
        $params = [];
        $params['search_key'] = $_GET['search_key'] ?? '';
        $search_key = $params['search_key'];
        $list = UserInstruction::get_instructions_list($filter, $params)->paginate(10);
        return view("admin.user_instructions.list", compact("page_heading", "list", "search_key"));
    }
    public function create(Request $request)
    {
        if (!get_user_permission('faq','c')) {
            return redirect()->route('admin.restricted_page');
        }
        if ($request->isMethod('post')) {
            $status = "0";
            $message = "";
            $errors = '';
            $validator = Validator::make($request->all(),
                [
                    'question' => 'required',
                    'answer' => 'required',
                ]
            );
            if ($validator->fails()) {
                $status = "0";
                $message = "Validation error occured";
                $errors = $validator->messages();
            } else {
                $ins['type'] = $request->type;
                $ins['title'] = $request->question;
                $ins['description'] = $request->answer;
                $ins['created_at'] = gmdate('Y-m-d H:i:s');
                $ins['created_by'] = session("user_id");
                $ins['updated_by'] = session("user_id");
                $ins['updated_at'] = gmdate('Y-m-d H:i:s');
                if (UserInstruction::insert($ins)) {
                    $status = "1";
                    $message = "User Instructions for Patients created";
                    $errors = '';
                } else {
                    $status = "0";
                    $message = "Something went wrong";
                    $errors = '';
                }
            }
            echo json_encode(['status' => $status, 'message' => $message, 'errors' => $errors]);die();
        } else {
            $page_heading = "Create User Instructions for Patients";
            return view('admin.user_instructions.create', compact('page_heading'));
        }

    }
    public function edit($id = '')
    {
        if (!get_user_permission('faq','u')) {
            return redirect()->route('admin.restricted_page');
        }
        $faq = UserInstruction::find($id);
        if ($faq) {
            $page_heading = "Edit User Instructions for Patients";
            return view('admin.user_instructions.edit', compact('page_heading', 'faq'));
        } else {
            abort(404);
        }
    }

    public function update(Request $request)
    {
        $status = "0";
        $message = "";
        $errors = '';
        $validator = Validator::make($request->all(),
            [
                'question' => 'required',
                'answer' => 'required',
            ]
        );
        if ($validator->fails()) {
            $status = "0";
            $message = "Validation error occured";
            $errors = $validator->messages();
        } else {
            $ins['type'] = $request->type;
            $ins['title'] = $request->question;
            $ins['description'] = $request->answer;
            $ins['updated_at'] = gmdate('Y-m-d H:i:s');
            $ins['updated_by'] = session("user_id");
            
            if (UserInstruction::where('id', $request->id)->update($ins)) {

                $status = "1";
                $message = "User Instructions for Patients updated";
                $errors = '';
            } else {
                $status = "0";
                $message = "Something went wrong";
                $errors = '';
            }
        }
        echo json_encode(['status' => $status, 'message' => $message, 'errors' => $errors]);die();
    }
    public function delete($id = '')
    {
        UserInstruction::where('id', $id)->delete();
        $status = "1";
        $message = "User Instructions for Patients removed successfully";
        echo json_encode(['status' => $status, 'message' => $message]);
    }
}
