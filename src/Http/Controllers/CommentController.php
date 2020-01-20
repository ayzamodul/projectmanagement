<?php

namespace ayzamodul\projectmanagement\Http\Controllers;

use App\Http\Controllers\Controller;
use ayzamodul\projectmanagement\Models\Gorev;
use ayzamodul\projectmanagement\Models\Gorevler;
use ayzamodul\projectmanagement\Models\Comment;
use ayzamodul\projectmanagement\Models\Kullanici;
use ayzamodul\projectmanagement\Http\Controllers\ProjectController;
use ayzamodul\projectmanagement\Models\KullaniciGorev;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


class CommentController extends Controller
{

    public function _construct()
    {
        $this->middleware('auth',['except' => 'store']);
    }




    public function new_note(Request $request)
    {

        $tid = $request->input('tid');
        $notes = $request->input('note');

        $comment = new Comment();
        $comment->name = Auth::user()->name;

        $comment->comment = $notes;
        $comment->approved = true;
        $comment->gorev_id = $tid;


        $comment->save();
        if ($comment) {
            echo 1;
        } else {
            echo 0;
        }


    }

    public function commentList($id){

        $active = "project";
        $entry = new Gorev;
        $calisan_goster = [];

        if ($id > 0) {
            $entry = Gorev::find($id);

            $calisan_goster = $entry->calisanlar()->pluck('kullanici_id')->all();

        }

        return view('Gorev::Gorev.Comment.list', compact('entry',  'calisan_goster','active'));
    }
    public function commentView($id){
        $info = Comment::where('id', $id)->first();

        if (isset($info->id)) {


            $array['yorum'] = $info->comment;         //



            echo json_encode($array);
    }}

    public function ekle($id=0)
    {

        $active = "project";
        $entry = new Gorev;
        $calisan_goster=[];
        if ($id > 0) {
            $entry = Gorev::find($id);

            $calisan_goster = $entry->calisanlar()->pluck('kullanici_id')->all();

        }
        $calisanlar = Kullanici::all();
        $task = Gorevler::all();
        $comment = Comment::all();

        return view('Gorev::Gorev.Comment.new', compact('entry','calisanlar', 'calisan_goster','task','comment','active'));

    }

    public function edit($id=null)
{
    $active = "project";
    $comment = Comment::find($id);

    return view('Gorev::Gorev.Comment.comments',compact('comment','active'));
}

    public function update(Request $request, $id)
    {
        $comment = Comment::find($id);
        $this->validate($request, array('comment'=>'required'));
        $comment->comment = $request->comment;
        $comment->save();

        Session::flash('success','Not Güncellendi');
        return redirect()->route('comment.list', $comment->gorev_id);
    }





    public function destroy(Request $request)
    {

        $tid = $request->input('tid');



        $comment = Comment::find($tid);
        $comment->deleted = 1;

        $comment->save();
        if ($comment) {
            echo 1;
        } else {
            echo 0;
        }

    }


}
