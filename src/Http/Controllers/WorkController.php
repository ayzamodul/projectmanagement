<?php

namespace ayzamodul\projectmanagement\Http\Controllers;

use App\Http\Controllers\Controller;
use ayzamodul\projectmanagement\Models\Feedback;
use ayzamodul\projectmanagement\Models\Gorev;
use ayzamodul\projectmanagement\Models\Gorevler;
use ayzamodul\projectmanagement\Models\Time;
use ayzamodul\projectmanagement\Models\Work;
use ayzamodul\projectmanagement\Models\WorkGallery;
use ayzamodul\projectmanagement\Models\Yonetici;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class WorkController extends Controller
{

    public function __construct()
    {
        $this->middleware("web");  // this will solve your problem
        $this->middleware("auth");
    }


    public function index($id = 0)
    {
        $active = "work";


        $id = Auth::user()->id;

        $list = Work::orderByRaw('FIELD(status,"3","0","1","2","4")')->where('deleted', 0)->where('user_id', $id)->orderByDesc('id')->get();


        return view('Gorev::Gorev.Work.index', compact('list', 'active'));

    }

    public function detailWork($id)
    {

        $imageGalleries = WorkGallery::where('work_id', $id)->where('deleted', 0)->pluck('gallery_image');
        $info = Work::where('id', $id)->first();
        $sa = $info->add_id;
        $add_user = Yonetici::find($sa);
        $gun[0] = ceil($info->timedata);

        $task[0] = $info->content;
        if ($info->add_name != null && mb_strtolower($info->add_name) != mb_strtolower($add_user->name)) {
            $add_name[0] = $info->add_name;
        } else {
            $add_name = [];
        }
        foreach ($imageGalleries as $row) {
            $mimType = mime_content_type('storage/' . $row);

            if ($mimType == "image/gif" || $mimType == "image/jpeg" || $mimType == "image/png" || $mimType == "image/bmp" || $mimType == "image/jpg" || $mimType=="image/pdf") {

                $image[] = $row;
            } else {
                $file[] = $row;
            }
        }

        $feedback = Feedback::where('work_id', $id)->where('isAdmin', 1)->orderByDesc('id')->pluck('notes');
        $feedbackUser = Feedback::where('work_id', $id)->where('isAdmin', 0)->orderByDesc('id')->pluck('notes');
        $first_start[0] = Time::where('work_id', $id)->where('status', 0)->pluck('start_time')->first();

        $last_time[0] = Time::where('work_id', $id)->where('status', 2)->pluck('end_time')->last();
        if (isset($info->id)) {

                if (!empty($image)) {
                    $array['image'] = $image;
                } else {
                    $array['image'] = [];
                }




            if (!empty($file)) {
                $array['file'] = $file;
            } else {
                $array['file'] = [];
            }


            if ($add_user != null) {
                $array['add_user'] = $add_user->name;  //
            } else {
                $array['add_user'] = "0";
            }
            if (isset($info->field) && $info->field != null) {
                $array['field'] = $info->field;         //
            } else {
                $array['field'] = "Belirsiz";
            }

            if (isset($info->start_date)) {
                $array['start_date'] = $info->start_date;  //
            } else {
                $array['start_date'] = "Belirsiz";
            }
            if (isset($info->finish_date)) {
                $array['finish_date'] = $info->finish_date;   //
            } else {
                $array['finish_date'] = "Belirsiz";
            }

            if ($info->status == 0) {
                $array['status'] = "İşlemde";  //
                $array['user_start_date'] = $first_start;
                $array['gun'] = $gun;
                $array['user_finish_date'] = [];
            } elseif ($info->status == 1) {
                $array['status'] = "Beklemede";  //
                $array['user_start_date'] = [];
                $array['gun'] = [];//
                $array['user_finish_date'] = [];
            } elseif ($info->status == 2) {
                $array['status'] = "Tamamlandı";
                $array['user_start_date'] = $first_start;
                $array['gun'] = $gun;//
                $array['user_finish_date'] = $last_time;
            } elseif ($info->status == 3) {
                $array['status'] = "İade";  //
                $array['user_start_date'] = [];
                $array['gun'] = $gun;
                $array['user_finish_date'] = [];
            } else {
                $array['status'] = "Onaylandı";
                $array['user_start_date'] = $first_start;
                $array['gun'] = $gun;//
                $array['user_finish_date'] = $last_time;
            }

            $array['task'] = $task;  //

            $array['feedback'] = $feedback;

            $array['feedbackUser'] = $feedbackUser;
            $updated = explode(" ", $info->updated_at);
            $array['updated_at'] = $updated[0];      //
            $array['add_name'] = $add_name;   //

            if ($info->content != null) {
                $array['work_content'] = $info->content;   //
            } else {
                $array['work_content'] = "Belirsiz";
            }
            echo json_encode($array);
        }
    }


    public function aktif(Request $request, $id)
    {
        $work = Work::find($id);
        if ($work->status != 0) {
            $time = new Time();
            $time->work_id = $work->id;
            $time->start_time = Carbon::now();
            $time->status = 0;
            $time->save();
            $work->status = 0;
            if ($work->task_id != null) {
                $task_id = $work->task_id;
                $up_task = Gorevler::find($task_id);
                $up_task->isShow = 0;
                $up_task->save();
            }
            $work->save();

            return back()->with('success', "Çalışma aktif edildi");
        } else {
            return back()->with('errors', "Çalışma zaten aktif halde!");
        }


    }

    public function pasif(Request $request, $id)
    {
        $work = Work::find($id);
        if ($work->status == 0) {
            $time = new Time();
            $time->work_id = $work->id;
            $time->end_time = Carbon::now();
            $time->status = 1;
            $time->save();
            $ilk = Time::all()->whereIn('work_id', $id)->whereIn('status', 0)->pluck('start_time')->last();
            $ilk = strtotime($ilk);
            $last = Carbon::now();
            $last = strtotime($last);
            $timeDiff = $last - $ilk;
            $timeDiff = $timeDiff / 86400;
            $work->timedata = $work->timedata + $timeDiff;
            $work->status = 1;
            if ($work->task_id != null) {
                $task_id = $work->task_id;
                $up_task = Gorevler::find($task_id);
                $up_task->isShow = 1;
                $up_task->save();
            }
            $work->save();
            return back()->with('errors', "Çalışma pasif edildi");
        } elseif ($work->status == 2) {
            return back()->with('errors', "Bitmiş Projeyi Pasif Hale Getiremezsiniz! Lütfen Önce Aktif Edin Veya Yeni Çalışma Ekleyin.");
        } else {
            return back()->with('errors', "Çalışma zaten pasif durumda.!");
        }


    }

    public function bitti(Request $request, $id)
    {
        $work = Work::find($id);
        if ($work->status == 0) {
            $time = new Time();
            $time->work_id = $work->id;
            $time->end_time = Carbon::now();
            $time->status = 2;
            $time->save();
            $ilk = Time::all()->whereIn('work_id', $id)->whereIn('status', 0)->pluck('start_time')->last();
            $ilk = strtotime($ilk);
            $last = Carbon::now();
            $last = strtotime($last);
            $timeDiff = $last - $ilk;
            $timeDiff = $timeDiff / 86400;

            $work->timedata = $work->timedata + $timeDiff;
            $work->status = 2;
            if ($work->task_id != null) {
                $task_id = $work->task_id;
                $up_task = Gorevler::find($task_id);
                $up_task->isShow = 2;
                $up_task->save();
            }
            $work->save();
            return back()->with('success', "Çalışma bitirildi.");
        } elseif ($work->status == 1) {
            $work->status = 2;
            $data = Time::where('work_id', $id)->pluck('start_time')->last();
            $datatwo = Time::where('work_id', $id)->pluck('end_time')->first();
            if ($data == null) {
                $time = new Time();
                $time->work_id = $work->id;
                $time->start_time = Carbon::now();
                $time->status = 0;
                $time->save();
            }
            if ($datatwo == null) {
                $timetwo = new Time();
                $timetwo->work_id = $work->id;
                $timetwo->end_time = Carbon::now();
                $timetwo->status = 2;
                $timetwo->save();
            }
            $work->save();
            return back()->with('success', "Çalışma bitirildi.");
        } elseif ($work->status == 3) {
            return back()->with('errors', "Lütfen görevi tamamlayınız.!");
        } else {
            return back()->with('errors', "Çalışma zaten bitirilmiş.!");
        }
    }

    public function create()
    {
        $active = "work";
        $project = Gorev::where('deleted', 0)->get();
        return view('Gorev::Gorev.Work.create', compact('project', 'active'));
    }

    public function store(Request $request)
    {
        $this->validate(request(), array(
            'content' => 'required',

        ));
        $work = new Work();
        $project = Gorev::where('deleted', 0)->get();
        $work->content = request('content');

        $work->project_name = request('project');
        $work->user_name = Auth::user()->name;
        $work->user_id = Auth::user()->id;
        $work->add_id = Auth::user()->id;
        $work->start_date = Carbon::now();
        $work->finish_date = request('finish_date');
        $work->status = 1;
        if ($request->supply) {
            $work->add_name = request('supply');
        } else {
            $work->add_name = Auth::user()->name;
        }

        if ($request->field != null) {
            $work->field = request('field');
        }
        $work->save();
        if (request()->file('imageGalleries')) {
            $files = $request->file('imageGalleries');
            if (count($files) > 0) {
                for ($i = 0; $i < count($files); $i++) {
                    $up = $files[$i]->store('galleries');

                    if ($up) {
                        $newFile = new WorkGallery();
                        $newFile->add_admin = Auth::user()->id;
                        $newFile->work_id = $work->id;
                        $newFile->gallery_image = $up;
                        $newFile->save();
                    }
                }
            }

        }


        return redirect()->route('work.index')->with('success', "Çalışma Kaydedildi");

    }

    public function update(Request $request, $id)
    {


        $work = Work::find($id);
        $project = Gorev::where('deleted', 0)->get();
        if (request('content') != null) {
            $work->content = request('content');
        }
        if (request('projeler') != null) {
            $work->project_name = request('projeler');
        }


        if (request('field') != null) {
            $work->field = request('field');
        }
        $work->user_name = Auth::user()->name;

        if (request('supply') != null) {
            $work->add_name = request('supply');
        }
        if (request('finish_date') != null) {
            $work->finish_date = request('finish_date');
        }


        $work->save();
        if (request()->file('imageGalleries')) {
            $files = $request->file('imageGalleries');
            if (count($files) > 0) {
                for ($i = 0; $i < count($files); $i++) {
                    $up = $files[$i]->store('galleries');

                    if ($up) {
                        $newFile = new WorkGallery();
                        $newFile->add_admin = Auth::user()->id;
                        $newFile->work_id = $work->id;
                        $newFile->gallery_image = $up;
                        $newFile->save();
                    }
                }
            }

        }

        return redirect()->route('work.index')->with('success', "Çalışma Güncellendi");
    }

    public function edit($id)
    {
        $active = "work";
        $project = Gorev::where('deleted', 0)->get();

        $work = Work::find($id);
        $imageGalleries = WorkGallery::where('work_id', $id)->where('deleted', 0)->get();

        $feedback = Feedback::where('work_id', $id)->where('deleted', 0)->get();

        return view('Gorev::Gorev.Work.edit', compact('work', 'project', 'imageGalleries', 'active', 'feedback'));
    }

    public function destroy($id)
    {
        $work = Work::find($id);
        $work->deleted = 1;
        $image = WorkGallery::where('work_id', $id)->get();
        foreach ($image as $img) {
            $img->deleted = 1;
            $img->save();
        }
        $work->save();

        return redirect()->route('work.index')->with('errors', "Silindi");
    }

    public function sil(Request $request)
    {

        $tid = $request->input('tid');


        $work = Work::find($tid);
        $work->deleted = 1;
        $image = WorkGallery::where('work_id', $tid)->get();
        foreach ($image as $img) {
            $img->deleted = 1;
            $img->save();
        }
        $work->save();
        if ($work) {
            echo 1;
        } else {
            echo 0;
        }

    }

    public function notesFeedback(Request $request)
    {

        $tid = $request->input('tid');
        $notes = $request->input('note');
        $feedback = new Feedback();
        $feedback->work_id = $tid;
        $feedback->notes = $notes;
        $feedback->isAdmin = 0;
        $feedback->save();
        if ($feedback) {
            echo 1;
        } else {
            echo 0;
        }


    }


    public function imageDelete($id)
    {
        $image = WorkGallery::find($id);
        $image->deleted = 1;
        $image->save();

        return back()->with('errors', "Silindi");
    }
}
