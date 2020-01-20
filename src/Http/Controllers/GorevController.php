<?php

namespace ayzamodul\projectmanagement\Http\Controllers;

use App\Http\Controllers\Controller;
use Auth;
use ayzamodul\projectmanagement\Models\Gorev;
use ayzamodul\projectmanagement\Models\Gorevler;
use ayzamodul\projectmanagement\Models\Kullanici;
use ayzamodul\projectmanagement\Models\KullaniciGorev;
use ayzamodul\projectmanagement\Models\Work;

class GorevController extends Controller
{
    public function index($id = 0)
    {
        $active = "project";
        $entry = new Gorev;
        $calisan_goster = [];
        if ($id > 0) {
            $entry = Gorev::find($id);


            $calisan_goster = $entry->calisanlar()->pluck('kullanici_id')->all();

        }
        $calisanlar = Kullanici::all();


        return view('Gorev::Gorev.listele', compact('entry', 'calisanlar', 'calisan_goster', 'active','users'));

    }
    public function list($id)
    {

        $info = Gorev::where('id', $id)->first();

$dizi=[];
        foreach($info->calisanlar->unique() as $sa){
            array_push($dizi,$sa->name);
        }
$leader= $info->leader[0]->name;
        if (isset($info->id)) {
            if ($info->project_name != null) {
                $array['project'] = $info->project_name;  //
            } else {
                $array['project'] = "Henüz girilmemiş";
            }

            if (isset($dizi) &&$dizi != null) {
                $array['personal'] = $dizi;         //
            } else {
                $array['personal'] = "Henüz girilmemiş";
            }

            if (isset($info->baslangic_tarihi) &&$info->baslangic_tarihi != null) {
                $array['baslangic_tarihi'] = $info->baslangic_tarihi;  //
            } else {
                $array['baslangic_tarihi'] = "Henüz girilmemiş";
            }
            if (isset($info->bitis_tarihi) && $info->bitis_tarihi != null) {
                $array['bitis_tarihi'] = $info->bitis_tarihi;   //
            } else {
                $array['bitis_tarihi'] = "Henüz Devam Ediyor";
            }


if($info->isShow==0){
    $array['isShow'] = "Aktif";
}
elseif ($info->isShow==1){
    $array['isShow'] = "Pasif";
}else{
    $array['isShow'] = "Bitti";
}

            if (isset($leader) && $leader != null) {

                $array['leader'] = $leader;
            } else {
                $array['leader'] = "Hünüz Geri Bildirim Yapılmamış.";
            }
            if ($info->aciklama != null) {
                $array['aciklama'] = $info->aciklama;   //
            } else {
                $array['aciklama'] = "Henüz girilmemiş";
            }
            echo json_encode($array);
        }
    }
    public function taskList($id = 0)
    {
        $active = "project";


        $calisan_goster = [];

        if ($id > 0) {
            $entry = Gorev::find($id);

                $calisan_goster = $entry->calisanlar()->pluck('kullanici_id')->all();
                $row = KullaniciGorev::where('gorev_id', $id)->get();

        }

        $calisanlar = Kullanici::all();
        $task = Gorevler::where('deleted',0);


//return $task->calisanlar()->get();
        //  return $entry->calisanlar()->get();
        //  return $calisan_goster;


        return view('Gorev::Gorev.Gorevler.list', compact('entry', 'calisanlar', 'calisan_goster', 'task', 'active','work'));

    }

}
