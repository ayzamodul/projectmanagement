@extends('yonetim.layouts.master')
@section('title','Proje Yönetimi')
@section('content')


    <header class="page-header" style="width: 110%;margin-left: -5%">
        <div class="row-fluid">
            <h2 class="no-margin-bottom" id="h2" style="margin-left: 2%">&nbsp; Proje Adı: {{$entry->project_name}}</h2>
        </div>
    </header>
    @if(Session::has('success'))
        <div class="alert alert-success jquery-error-alert">
            <ul>
                <li>{{Session::get('success')}}</li>
            </ul>
        </div>

    @elseif(Session::has('errors'))
        <div class="alert alert-danger jquery-error-alert">
            <ul>
                <li>{{Session::get('errors')}}</li>
            </ul>
        </div>
    @endif


    <div class="card animated fadeIn" style="background-color: white" id="deneme">
        @can('not-ekleDuzenle')
        <div style="float: right">

            <a onclick="new_note({{$entry->id}})" class="btn btn-primary"
               style="margin-top: 15%">Not Ekle</a>
        </div>
            @else
                @if($entry->project_leader==Auth::user()->id)
                <div style="float: right">
                    <a onclick="new_note({{$entry->id}})" class="btn btn-primary"
                       style="margin-top: 15%">Not Ekle</a>
                </div>
                @endif


@endcan
        <div class="card-header d-flex align-items-center">
            <h3 class="h4">Not Listesi</h3>
        </div>
        <div class="card-body row-list-table">
            <div class="table-responsive">

                <table class="response table table-hover table-bordered" id="myTab">
                    <thead class="thead-dark">
                    <tr>

                        <th>#</th>
                        <th>Ekleyen</th>
                        <th>Not</th>

                        <th>Eklenme Tarihi</th>


                        <th>Detay</th>
                        @can('not-ekleDuzenle')
                            <th>Düzenle</th>
                        @else
                            @if($entry->project_leader==Auth::user()->id)
                                <th>Düzenle</th>
                            @endif
                        @endcan
                        @can('not-sil')
                            <th>Sil</th>
                        @else
                            @if($entry->project_leader==Auth::user()->id)
                                <th>Sil</th>
                            @endif
                        @endcan
                    </tr>
                    </thead>
                    <tbody>


                    @foreach($entry->comments as $comment)

                        <tr>

                            <td>{{$comment->id}}</td>
                            <td>{{$comment->name}}</td>


                            <td>
                                {!! substr(strip_tags($comment->comment), 0 , 100) !!}{{strlen(strip_tags($comment->comment))> 100 ?"...":""}}
                            </td>

                            <td>{{\Illuminate\Support\Carbon::parse($comment->created_at)->format("d/m/Y H:i:s")}}</td>
                            <td><a class="btn btn-info" onclick="$.students_info({{$comment->id}})"><i
                                        class="fa fa-list" aria-hidden="true" style="color: white"></i></a></td>
                            @can('not-ekleDuzenle')
                                <td style="width: 20px">
                                    <a href="{{route('comments.edit' , $comment->id)}}" class="btn btn btn-warning"
                                       data-toggle="tooltip" data-placement="top"
                                       title="Düzenle">
                                        <span class="far fa-edit"></span>
                                    </a>
                                </td>
                            @else
                                @if($entry->project_leader==Auth::user()->id)
                                    <td style="width: 20px">
                                        <a href="{{route('comments.edit' , $comment->id)}}" class="btn btn btn-warning"
                                           data-toggle="tooltip" data-placement="top"
                                           title="Düzenle">
                                            <span class="far fa-edit"></span>
                                        </a>
                                    </td>
                                    @endif
                            @endcan
                            @can('not-sil')
                                <td style="width: 70px">

                                    <button class="btn btn-xl btn-danger" data-placement="top" title="Sil"
                                            onclick="note_delete({{$comment->id}})"><span class="fa fa-trash"></span>
                                    </button>
                                </td>
                                    @else
                                        @if($entry->project_leader==Auth::user()->id)
                                    <td style="width: 70px">

                                        <button class="btn btn-xl btn-danger" data-placement="top" title="Sil"
                                                onclick="note_delete({{$comment->id}})"><span class="fa fa-trash"></span>
                                        </button>
                                    </td>
@endif


                            @endcan

                        </tr>

                    @endforeach
                    </tbody>
                </table>
                <br>
            </div>
        </div>
    </div>
@endsection
@section('footer')
    <script
        src="https://code.jquery.com/jquery-3.4.1.js"
        integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU="
        crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function () {
            $('#myTab').DataTable({

                responsive: true,
                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/a5734b29083/i18n/Turkish.json"
                }
            });
            $.students_info = function (id) {

                $.post('{{url('yonetim/gorev/commentview')}}/' + id, {
                    id: id,
                    _token: '{{csrf_token()}}'
                }, function (ret) {


                    $.alert({

                        title: 'Not Detay',
                        columnClass: 'col-md-6 col-md-offset-3',
                        type: 'dark',
                        icon: 'fa fa-info-circle',
                        content: '' +


                            '<div class="form-group row">' +

                            ' <div class="col-sm-12">' +
                            ' <label class="form-control-label">İçerik:</label>'+
                        '<p >' + ret.yorum + '</p>' +
                            '</div>' +
                            '</div>' +
                            '</div>',
                        buttons: {

                            cancel: {
                                text: 'Kapat',
                                action: function () {
                                }
                            }
                        },
                    });
                }, "json");

            }
        });
        function new_note(data_id) {

            $.confirm({
                columnClass: 'col-md-5 col-md-offset-4',
                type: 'dark',
                icon: 'fa fa-info-circle',
                title: 'Lütfen not giriniz!',
                content: '' +
                    '<form action="" class="formName" xmlns="http://www.w3.org/1999/html">' +
                    '<div class="form-group">' +
                    '<label>Notunuz</label>' +
                    '<textarea type="text" placeholder="Notunuz" class="name form-control" cols="30%" rows="6%" style="resize: none" required> </textarea>' +
                    '</div>' +
                    '</form>',
                buttons: {
                    formSubmit: {
                        text: 'Gönder',
                        btnClass: 'btn-blue',
                        action: function () {
                            var name = this.$content.find('.name').val();
                            var tid = data_id;
                            var ajaxurl = "{{url('yonetim/gorev/newNote')}}";
                            var token = ' {{csrf_token()}}';
                            console.log(ajaxurl);
                            $.post(ajaxurl, {
                                _token: token,
                                note: name,
                                tid: tid
                            }, function (ret) {

                                if (ret == 1) {

                                    $.confirm({
                                        title: 'Bilgi',

                                        type: 'dark',
                                        icon: 'fa fa-info-circle',
                                        content: 'Notunuz iletildi.',
                                        buttons: {
                                            formSubmit: {
                                                text: 'Tamam',
                                                btnClass: 'btn',

                                                action: function () {
                                                    window.location.reload()
                                                }
                                            },

                                        },
                                    });


                                } else {
                                    $.alert("Ters giden bir şey oldu. Lütfen tekrar deneyin.")
                                }
                            });

                        }

                    },
                    İptal: function () {
                        //close

                    },
                },
                onContentReady: function () {
                    // bind to events
                    var jc = this;
                    this.$content.find('form').on('submit', function (e) {
                        // if the user submits the form by pressing enter in the field.
                        e.preventDefault();
                        jc.$$formSubmit.trigger('click'); // reference the button and click it
                    });

                }

            });

        }
        function note_delete(data_id) {

            $.confirm({

                type: 'red',
                typeAnimated: true,
                icon: 'fa fa-trash',
                title: 'Uyarı!',
                content: 'Notu silmek istiyor musunuz?',

                buttons: {
                    formSubmit: {
                        text: 'Sil',
                        btnClass: 'btn-red',
                        action: function () {

                            var name = this.$content.find('.name').val();
                            var tid = data_id;
                            var ajaxurl = "{{url('yonetim/gorev/commentsDelete')}}";
                            var token = ' {{csrf_token()}}';
                            console.log(ajaxurl);
                            $.post(ajaxurl, {
                                _token: token,
                                tid: tid
                            }, function (ret) {

                                if (ret == 1) {

                                    $.confirm({
                                        title: 'Bilgi',

                                        type: 'red',
                                        icon: 'fa fa-info-circle',
                                        content: 'Not silindi!',
                                        buttons: {
                                            formSubmit: {
                                                text: 'Tamam',
                                                btnClass: 'btn-red',

                                                action: function () {
                                                    window.location.reload()
                                                }
                                            },

                                        },
                                    });


                                } else {
                                    $.alert("Ters giden bir şey oldu. Lütfen tekrar deneyin.")
                                }
                            });

                        }

                    },
                    İptal: function () {
                        //close

                    },
                },
                onContentReady: function () {
                    // bind to events
                    var jc = this;
                    this.$content.find('form').on('submit', function (e) {
                        // if the user submits the form by pressing enter in the field.
                        e.preventDefault();
                        jc.$$formSubmit.trigger('click'); // reference the button and click it
                    });

                }

            });

        }
    </script>

@endsection


