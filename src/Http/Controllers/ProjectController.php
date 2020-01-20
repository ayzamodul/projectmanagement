<?php

namespace ayzamodul\projectmanagement\Http\Controllers;

use App\Http\Controllers\Controller;
use ayzamodul\projectmanagement\Models\Gorev;
use ayzamodul\projectmanagement\Models\Gorevler;
use ayzamodul\projectmanagement\Models\Kullanici;
use ayzamodul\projectmanagement\Models\KullaniciGorev;
use ayzamodul\projectmanagement\Models\Tasks;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{


    public function __construct()
    {
        $this->middleware("web");  // this will solve your problem
        $this->middleware("auth");
    }


    public function index($id = 0)
    {
        $active = "project";
        $id = Auth::user()->id;

        if (Auth::user()->hasRole('Admin')) {
            $list = Gorev::where('deleted', 0)->get();



            return view('Gorev::Gorev.index', compact('list', 'active'));
        } else {
            //  $list=User::orderBy('name','desc');
            //  $sa = DB::table('gorev')->get();
            //   $task = Gorevler::all();

            $kullanici = KullaniciGorev::all()->whereIn('kullanici_id', $id)->pluck('gorev_id');

            $list = Gorev::where('deleted', 0)->whereIn('id', $kullanici)->get();


            return view('Gorev::Gorev.index', compact('list', 'task', 'calisanlar', 'active'));
        }
    }

    public function form($id = 0)
    {
        $active = "project";
        $entry = new Gorev;
        $calisan_goster = [];

        if ($id > 0) {
            $entry = Gorev::find($id);
            if (Auth::user()->hasRole('Admin')) {
                $calisan_goster = $entry->calisanlar()->pluck('kullanici_id')->all();
                $sa = KullaniciGorev::where('gorev_id', $id)->get();
            } else if ($entry->project_leader == Auth::user()->id) {
                $calisan_goster = $entry->calisanlar()->pluck('kullanici_id')->all();
                $sa = KullaniciGorev::where('gorev_id', $id)->get();
            } else {
                return redirect(url('yonetim/gorev'));
            }

        }

        $calisanlar = Kullanici::where('deleted', 0)->get();
        $task = Gorevler::all();

//return $task->calisanlar()->get();
        //  return $entry->calisanlar()->get();
        //  return $calisan_goster;


        return view('Gorev::Gorev.form', compact('entry', 'calisanlar', 'calisan_goster', 'task', 'active'));

    }


    public function kaydet($id = 0)
    {
        //  return request()->only('password');


        $data = request()->only('project_name', 'aciklama', 'isShow', 'kullanici_id', 'baslangic_tarihi', 'bitis_tarihi');
        $calisanlar = request('calisanlar');
        $leader = request('leader');
        if (!in_array($leader, $calisanlar)) {
            array_push($calisanlar, $leader);
        }


        if ($id > 0) {
            $entry = Gorev::where('id', $id)->firstOrFail();
            $entry->update($data);
            $entry->project_leader = $leader;

            $entry->save();
            $entry->calisanlar()->sync($calisanlar);


        } else {
            $entry = Gorev::create($data);
            $entry->project_leader = $leader;
            $entry->save();
            $entry->calisanlar()->attach($calisanlar);
            $entry->calisanlar()->attach($leader);
        }


        return redirect()
            ->route('yonetim.gorev', $entry->id)
            ->with('status', 'İşleminiz Gerçekleştirildi')
            ->with('status_type', 'success');


    }


    public function sil(Request $request)
    {

        $tid = $request->input('tid');


        $project = Gorev::find($tid);
        $project->deleted = 1;

        $project->save();
        if ($project) {
            echo 1;
        } else {
            echo 0;
        }

    }


}
