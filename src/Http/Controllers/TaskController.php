<?php

namespace ayzamodul\projectmanagement\Http\Controllers;

use App\Http\Controllers\Controller;
use Auth;
use ayzamodul\projectmanagement\Models\Comment;
use ayzamodul\projectmanagement\Models\Feedback;
use ayzamodul\projectmanagement\Models\Gorev;
use ayzamodul\projectmanagement\Models\Gorevler;
use ayzamodul\projectmanagement\Models\Kullanici;
use ayzamodul\projectmanagement\Models\KullaniciGorev;
use ayzamodul\projectmanagement\Models\Work;
use ayzamodul\projectmanagement\Models\WorkGallery;
use ayzamodul\projectmanagement\Models\Yonetici;
use Carbon\Carbon;
use Illuminate\Http\Request;


class TaskController extends Controller
{


    public function store(Request $request, $gorev_id = null)
    {

        $this->validate(request(), array(
            'content' => 'required',

        ));
        $personal = Yonetici::find($request->user);

        $work = new Work();
        $project = Gorev::where('deleted', 0)->get();
        $work->content = request('content');
        $work->project_name = $request->project_name;
        $work->field = request('field');
        if ($request->supply==null) {
            $work->add_name = Auth::user()->name;

        } else {

            $work->add_name = request('supply');
        }

        $work->user_name = $personal->name;
        $work->user_id = $personal->id;
        $work->status = 1;
        $work->add_id = Auth::user()->id;
        $work->start_date = Carbon::now();
        $work->finish_date = request('finish_date');
        $project_id = $request->project_id;

        $work->save();
        if(request()->file('imageGalleries')){
            $files = $request->file('imageGalleries');
            if (count($files) > 0){
                for ($i = 0; $i < count($files); $i++) {
                    $up = $files[$i]->store('galleries');

                    if ($up) {
                        $newFile = new WorkGallery();
                        $newFile->add_admin= \Illuminate\Support\Facades\Auth::user()->id;
                        $newFile->work_id= $work->id;
                        $newFile->gallery_image =$up;
                        $newFile->save();
                    }
                }
            }

        }

        return redirect()->route('gorev.list', $project_id)->with('success', "Görev Kaydedildi");

    }

    public function taskView($id)
    {
        $info = Gorevler::where('id', $id)->first();

        if (isset($info->id)) {

            $array['name'] = $info->name;  //
            $array['aciklama'] = $info->task;         //


            echo json_encode($array);
        }
    }

    public function ekle($id = 0)
    {

        $active = "project";
        $check = Gorev::find($id);
        if (Auth::user()->hasRole('Admin') || $check->project_leader == Auth::user()->id) {

            $users = request('users');

            $calisan_goster = [];
            if ($id > 0) {
                $entry = Gorev::find($id);
                $calisan_goster = $entry->calisanlar()->pluck('kullanici_id')->all();
                $project = Gorev::where('deleted', 0)->get();


            }
            $deneme = KullaniciGorev::where('gorev_id', $id)->pluck('kullanici_id');
            $users = Kullanici::whereIn('id', $deneme)->get();


            $calisanlar = Kullanici::all();
            $task = Gorevler::all();
            $comment = Comment::all();


            return view('Gorev::Gorev.Gorevler.new', compact('entry', 'calisanlar', 'calisan_goster', 'task', 'comment', 'users', 'deneme', 'active', 'project'));
        } else {
            return redirect(url('yonetim/gorev'));
        }

    }

    public function edit($id = null)
    {

        $active = "project";
        $project = Gorev::where('deleted', 0)->get();


        $work = Work::find($id);
        $entry = $work->tasks;

        $data = KullaniciGorev::where('gorev_id', $entry->id)->pluck('kullanici_id');
        $user = Kullanici::whereIn('id', $data)->get();
        if (Auth::user()->hasRole('Admin') || $entry->project_leader == Auth::user()->id) {


            $imageGalleries = WorkGallery::where('work_id', $id)->where('deleted', 0)->get();

            $feedback = Feedback::where('work_id', $id)->where('deleted', 0)->get();


            return view('Gorev::Gorev.Gorevler.tasks', compact('work', 'project', 'imageGalleries', 'active', 'feedback', 'entry', 'user'));


        } else {
            return redirect(url('yonetim/gorev'));
        }

    }

    public function update(Request $request, $id)
    {

        $this->validate(request(), array(
            'content' => 'required',

        ));
        $personal = Yonetici::find($request->user);
        $work = Work::find($id);
        $project = Gorev::all();
        if(request('content')!=null){
            $work->content = request('content');
        }
      if(request('project_name')){
          $work->project_name = $request->project_name;
      }
        if(request('field')){
            $work->field = request('field');
        }



        $work->add_id = Auth::user()->id;
        if ($request->supply) {
            $work->add_name = Auth::user()->name;

        } else {

            $work->add_name = request('supply');
        }
        $work->user_name = $personal->name;
        $work->user_id = $personal->id;
if(request('finish_date')){
    $work->finish_date = request('finish_date');
}


        $project_id = $request->project_id;

    $work->save();
        if(request()->file('imageGalleries')){
            $files = $request->file('imageGalleries');
            if (count($files) > 0){
                for ($i = 0; $i < count($files); $i++) {
                    $up = $files[$i]->store('galleries');

                    if ($up) {
                        $newFile = new WorkGallery();
                        $newFile->add_admin= \Illuminate\Support\Facades\Auth::user()->id;
                        $newFile->work_id= $work->id;
                        $newFile->gallery_image =$up;
                        $newFile->save();
                    }
                }
            }

        }

        return redirect()->route('gorev.list', $project_id)->with('success', "Görev Güncellendi");

    }


    public function taskDelete(Request $request)
    {

        $tid = $request->input('tid');

        $task = Gorevler::find($tid);

        $task->deleted = 1;
        $task->save();

        if ($task) {
            echo 1;
        } else {
            echo 0;
        }

    }


}
