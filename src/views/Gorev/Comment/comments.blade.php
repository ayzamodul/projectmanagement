@extends('yonetim.layouts.master')
@section('title','Not Yönetimi')
@section('content')
    <header class="page-header" style="width: 110%;margin-left: -5%">
        <div class="row-fluid" >
            <h2 class="no-margin-bottom"  id="h2" style="margin-left: 2%" >&nbsp; Proje Yönetimi</h2>
        </div>
    </header>
    <section class="animated fadeIn">
        <div class="card" style="background-color: white" id="deneme">
            <div class="card-header d-flex align-items-center">
                <h3 class="h4">Not Düzenle</h3>
            </div>
            <div class="card-body">
    <div class="row">
        <div class="col-md-8 col-md-offset-0">


            {{Form::model($comment,['route'=>['comments.update', $comment->id],'method'=>'PUT'])}}

            {{Form::label('İsim')}}
            {{Form::text('name',null,['class'=>'form-control','disabled'=> '','style'=>'background-color:white'])}}



            {{Form::label('comment', "Not Ekle:")}}
            {{Form::textarea('comment',null, ['class' =>'form-control','style'=>'resize:none'])}}
            {{Form::submit( __('Not Düzenle') , ['class' => 'btn btn-block btn-primary', 'style' => 'margin-top:15px;'])}}


            {{Form::close()}}


        </div>
    </div>
            </div></div></section>



    <script src="//cdn.ckeditor.com/4.6.2/standard/ckeditor.js"></script>
    <script
        src="https://cdnjs.cloudflare.com/ajax/libs/ckeditor/4.12.1/plugins/autogrow/plugin.js"></script>
    <script>
        var options = {


            language: 'tr',

            extraPlugins: 'autogrow',
            autoGrow_minHeight: 200,
            autoGrow_maxHeight: 600,
            filebrowserImageBrowseUrl: '/laravel-filemanager?type=Images',
            filebrowserImageUploadUrl: '/laravel-filemanager/upload?type=Images&_token=',
            filebrowserBrowseUrl: '/laravel-filemanager?type=Files',
            filebrowserUploadUrl: '/laravel-filemanager/upload?type=Files&_token='
        };
    </script>
    <script>
        CKEDITOR.replace('comment', options);
    </script>
    @endsection

