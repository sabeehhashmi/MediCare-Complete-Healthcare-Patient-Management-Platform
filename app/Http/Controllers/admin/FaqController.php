<?php

namespace App\Http\Controllers\Admin;

use App\Models\FaqModel;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;

class FaqController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!get_user_permission('settings','r')) {
            return redirect()->route('admin.restricted_page');
        }
        $page_heading = "FAQ For Patients";
        $filter = [];
        $params = [];
        $params['search_key'] = $_GET['search_key'] ?? '';
        $search_key = $params['search_key'];
        $list = FaqModel::get_faq_list($filter, $params)->paginate(10);
        return view("admin.faq.list", compact("page_heading", "list", "search_key"));
    }
    public function create(Request $request)
    {
        if (!get_user_permission('settings','c')) {
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
               
                $ins['title'] = $request->question;
                $ins['description'] = $request->answer;
                $ins['created_at'] = gmdate('Y-m-d H:i:s');
                $ins['created_by'] = session("user_id");
                $ins['updated_by'] = session("user_id");
                $ins['updated_at'] = gmdate('Y-m-d H:i:s');
                if (FaqModel::insert($ins)) {
                    $status = "1";
                    $message = "FAQ created";
                    $errors = '';
                } else {
                    $status = "0";
                    $message = "Something went wrong";
                    $errors = '';
                }
            }
            echo json_encode(['status' => $status, 'message' => $message, 'errors' => $errors]);die();
        } else {
            $page_heading = "Create FAQ";
            return view('admin.faq.create', compact('page_heading'));
        }

    }
    public function edit($id = '')
    {
        if (!get_user_permission('settings','u')) {
            return redirect()->route('admin.restricted_page');
        }
        $faq = FaqModel::find($id);
        if ($faq) {
            $page_heading = "Edit FAQ";
            return view('admin.faq.edit', compact('page_heading', 'faq'));
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
           
            $ins['title'] = $request->question;
            $ins['active'] = $request->active;
            $ins['description'] = $request->answer;
            $ins['updated_at'] = gmdate('Y-m-d H:i:s');
            $ins['updated_by'] = session("user_id");
            
            if (FaqModel::where('id', $request->id)->update($ins)) {

                $status = "1";
                $message = "FAQ updated";
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
        FaqModel::where('id', $id)->delete();
        $status = "1";
        $message = "faq removed successfully";
        echo json_encode(['status' => $status, 'message' => $message]);
    }

}
