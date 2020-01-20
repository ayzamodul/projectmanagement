<?php

namespace ayzamodul\projectmanagement\Http\Controllers;

use App\Http\Controllers\Controller;
use ayzamodul\projectmanagement\Models\Feedback;
use ayzamodul\projectmanagement\Models\Kullanici;
use ayzamodul\projectmanagement\Models\Work;
use ayzamodul\projectmanagement\Models\WorkGallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Yajra\DataTables\Facades\DataTables;
use function GuzzleHttp\Psr7\str;


class EmployeeController extends Controller
{

    public function __construct()
    {
        $this->middleware("web");  // this will solve your problem
        $this->middleware("auth");
        $this->middleware('permission:personel-takibi', ['only' => ['index']]);
    }

    public function dataTable()
    {
        $type = Input::get('type');

        switch ($type) {


            case 'cons_demo_st':


                $list = Work::orderByRaw('FIELD(status,"0","3","1","2","4")')->orderByDesc('id');

                return DataTables::eloquent($list)->toJson();
                break;

        }

    }

    public function feedback(Request $request)
    {

        $tid = $request->input('tid');
        $notes = $request->input('note');
        $feedback = new Feedback();
        $feedback->work_id = $tid;
        $feedback->notes = $notes;
        $feedback->save();
        if ($feedback) {
            echo 1;
        } else {
            echo 0;
        }
        $work = Work::find($tid);
        $work->status = 3;
        $work->save();


    }
    public function feedbackPositive(Request $request)
    {

        $tid = $request->input('tid');



        $work= Work::find($tid);
        $work->status=4;
        $work->save();
        if ($work) {
            echo 1;
        } else {
            echo 0;
        }

    }

    public function index($id = 0)
    {
        $active = "feedback";

        $imageGalleries = WorkGallery::where('work_id', $id)->where('deleted', 0)->get();
        $list = Work::where('deleted', 0)->get();
        $user = Kullanici::where('deleted',0)->get();


        return view('Gorev::Gorev.Employee.index', compact('list', 'active', 'user','imageGalleries'));

    }


}
