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
                <h3 class="h4">Not Eklenecek Proje: {{$entry->project_name}}</h3>
            </div>
    <div class="row">
        <div id="comment-form" name="yaz" class="col-md-8 col-md-offset-0" style="margin-top: 20px">
            {{Form::open(['route' => ['comments.store', $entry->id], 'method' => 'POST'])}}
<div class="card-body">
            <div class="row">

                <div class="col-md-12">
                    {{Form::label('comment', "Not Ekle:")}}
                    {{Form::textarea('comment',null, ['class' =>'form-control','required'=>'', 'rows' =>'5','cols'=> '20'])}}
                    {{ Form::submit( __('Not Ekle') , ['class' => 'btn btn-primary btn-block', 'style' => 'margin-top:15px;'])}}

                </div>
            </div>
</div>

            {{Form::close()}}
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

