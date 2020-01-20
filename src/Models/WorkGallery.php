<?php

namespace ayzamodul\projectmanagement\Models;
use ayzamodul\projectmanagement\Models\Gorev;
use Carbon;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
class WorkGallery extends Authenticatable
{
   protected $table ='work_gallery';
    protected $fillable =[
      'work_id',
      'gallery_image',
    ];

    public static function imageGallery($fileName,$work_id){
        if(request()->file($fileName)){
            $files = $request->file($fileName);
            if (count($files) > 0){
                for ($i = 0; $i < count($files); $i++) {
                    $up = $files[$i]->store('galleries');
                    if ($up) {
                        $newFile = new WorkGallery();
                        $newFile->add_admin=Auth::user()->id;
                        $newFile->work_id= $work_id;
                        $newFile->gallery_image = $files[$i];
                        $newFile->save();
                    }
                }
            }

        }

    }

    public function create(Request $request){
        $validator      = $this->validate($request,[
            'name'   => 'required',
            'packets'   => 'required',
            'content'   => 'required',
            'lclass'    => 'required'
        ]);

        $name       = $request->input('name');
        $content    = $request->input('content');
        $packets_id  = $request->input('packets');
        $lclass  = $request->input('lclass');

        $add_note   = new notes();
        $add_note->name     = $name;
        $add_note->content     = $content;
        $add_note->packets_id   = $packets_id;
        $add_note->lclass_id   = $lclass;
        $add_note->author       = Auth::id();
        $add_note->save();
        if(!empty($request->file('files')) ) {
            $files = $request->file('files');
            if (count($files) > 0) {
                $errors = [];
                for ($i = 0; $i < count($files); $i++) {
                    $up = $files[$i]->store('notes');
                    if ($up) {
                        $nFile = new notes_files();
                        $nFile->note_id = $add_note->id;
                        $nFile->link = $up;
                        $nFile->save();
                    }

                }
            }
        }
        $packet     = packets::find($packets_id);
        $message    = $packet->title . ' konusunda yeni bir not eklendi. iyi çalışmalar';
        $this->add_notice($message,$packets_id);

        return Redirect::to(url('admin/lesson/notes/'.$add_note->id));
    }
}
