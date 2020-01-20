@extends('yonetim.layouts.master')
@section('title','Proje Yönetimi')
@section('content')
    <header class="page-header" style="width: 110%;margin-left: -5%">
        <div class="row-fluid" >
            <h2 class="no-margin-bottom"  id="h2" style="margin-left: 2%" >&nbsp; Proje Yönetimi</h2>
        </div>
    </header>
    <section class="animated fadeIn">




    <form method="post" action="{{route('yonetim.gorev.kaydet', @$entry->id)}}">
        <div class="card" style="background-color: white" id="deneme">
        <div class="card-header d-flex align-items-center">
            <h3 class="h4">{{$entry->project_name}}</h3>
        </div>

<br>
        <div class="container" id="deneme">
        <span><label for="name">Proje Başlangıç Tarihi=</label>{{$entry->baslangic_tarihi}}</span> &ensp; &ensp;
        <span><label for="name">Proje Bitiş Tarihi=</label>{{$entry->bitis_tarihi}}</span><br>


      <br>  <div class="row">
            <div class="col-md-8">
                <div class="form-group">
                    <label for="calisanlar">Proje Çalışanları</label>
                    <select name="calisanlar[]" id="calisanlar" class="form-control" multiple disabled>
                        @foreach($calisanlar as $users)
                            <option value="{{$users->id}}"{{collect(old('calisanlar',$calisan_goster))->contains($users->id) ? 'selected':''}}>{{$users->name}}</option>

                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <br>


        <div class="col-md-8">
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active"><a href="#t1" data-toggle="tab">Proje Açıklaması</a></li>
                <li role="presentation"><a href="#t2" data-toggle="tab">Notlar</a></li>
                <li role="presentation"><a href="#t3" data-toggle="tab">Gorevler</a></li>
            </ul>
            <div class="tab-content"><br>
                <div role="tabpanel" class="tab-pane active" id="t1">
                    {{$entry->aciklama}}
                </div>
                <div role="tabpanel" class="tab-pane" id="t2">
                    <h3 class="comments-title"><span class="glyphicon glyphicon-comment"></span> {{$entry->comments()->count()}} Adet Not Var</h3>
                    <div id="asd" class="row">

                        <div id="comment-form" class="col-md-8 col-md-offset-0">

                            @foreach($entry->comments as $comment)
                                <div class="comment">

                                    <div class="author-info">

                                        <img src="{{ "https://www.gravatar.com/avatar/" . md5(strtolower(trim($comment->email))) . "?s=50&d=mm" }}" class="author-image">
                                        <div class="author-name">
                                            <h4>{{ $comment->name }}</h4>
                                            <p class="author-time">{{date('d.m.Y H:i' ,strtotime($comment->created_at))}}</p>


                                        </div>


                                    </div>

                                    <div class="comment-content">
                                        {{$comment->comment}}


                                    </div>
                                </div>

                            @endforeach


                        </div>

                    </div>

                </div>
                <div role="tabpanel" class="tab-pane" id="t3">
                    <div id="comment-form" class="col-md-8 col-md-offset-0">

                        @foreach($entry->tasks as $task)

                            <div class="task">

                                <div class="author-info">


                                    <div class="author-name">
                                        <label for="user" style="font-size: 120%">Çalışanlar:</label>
                                        @foreach($task->calisanlar as $row)
                                            <span class="label label-primary label-many"
                                                  style="font-size: 120%">{{ $row->name }}</span>
                                        @endforeach


                                    </div>
                                    <br><br>
                                    <p class="col-md-4"><label>Verildiği
                                            Tarih:</label>{{date('d.m.Y' ,strtotime($task->created_at))}}
                                    </p>
                                    <p class="col-md-4"><label>Bitiş
                                            Tarihi:</label>{{date('d.m.Y' ,strtotime($task->finish_date))}}
                                    </p>

                                </div><br>

                                <hr>
                                <div class="comment-content" style="margin-left: 0%;">
                                        <textarea style="width: 550px; height: 150px;resize: none;background-color: white"
                                                  disabled>{{$task->task}}</textarea>


                                </div>
                            </div>

                            <br>
                        @endforeach


                    </div>
                </div>

            </div>
        </div>








        </div>
        <br><br> </div>
    </form>


    </section>

    @endsection
@section('head')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/css/select2.min.css" rel="stylesheet" />

@endsection


@section('footer')
    <script src='https://code.jquery.com/jquery-3.2.1.slim.min.js'></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/js/select2.min.js"></script>
    <script>
        $(function () {
            $('#calisanlar').select2({
                placeholder: 'Lütfen calisan seçiniz'
            });

        });
    </script>

@endsection
