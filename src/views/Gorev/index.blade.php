@extends('yonetim.layouts.master')
@section('title','Proje Yönetimi')
@section('content')


    <header class="page-header" style="width: 110%;margin-left: -5%">
        <div class="row-fluid">
            <h2 class="no-margin-bottom" id="h2" style="margin-left: 2%">&nbsp; Proje Yönetimi</h2>
        </div>
    </header>



    <section>

        <style>
            .jconfirm .jconfirm-box div.jconfirm-content-pane .jconfirm-content {
                overflow: hidden;
            }
        </style>
        <div class="card animated fadeIn" style="background-color: white" id="deneme">

            @can('proje-ekleDuzenle')
                <a href="{{route('yonetim.gorev.yeni')}}" class="btn btn-primary" style="float:right; margin-top: 1%">Proje
                    Ekle</a>
            @endcan
            <div class="card-header d-flex align-items-center">
                <h3 class="h4">Proje Listesi</h3>
            </div>
            <div class="card-body row-list-table">

                <div class="table-responsive">
                    <table class="responsive table table-hover table-bordered" id="myTab">
                        <thead class="thead-dark">
                        <tr>

                            <th>#</th>
                            <th>Proje Adı</th>
                            <th>Çalışanlar</th>
                            <th>Yönetici</th>
                            <th>Başlangıç Tarihi</th>
                            <th>Durum</th>
                            <th>Detay</th>

                            <th>Seçenekler</th>
                        </tr>
                        </thead>
                        <tbody>


                        @foreach($list as $data)

                            <tr>

                                <td>{{$data->id}}</td>
                                <td>{{$data->project_name}}</td>
<td>
    @foreach($data->calisanlar->unique() as $sa)
    <span class="label label-info label-many">{{$sa->name}}</span>
@endforeach
</td>
                                <td>{{$data->leader[0]->name}}</td>



                                @if(isset($data->baslangic_tarihi))
                                    <td>{{\Illuminate\Support\Carbon::parse($data->baslangic_tarihi)->format("d/m/Y")}}</td>
                                @else
                                    <td>Henüz başlanmamış</td>
                                @endif
                                <td>
                                    @if ($data->isShow==1)
                                        <span class="label label-warning">Pasif</span>
                                    @elseif($data->isShow==0)
                                        <span class="label label-success">Aktif</span>
                                        @else
                                        <span class="label label-danger">Bitti</span>
                                    @endif
                                </td>

                                <td><a class="btn btn-info" onclick="$.project_info({{$data->id}})"><i
                                            class="fa fa-list" aria-hidden="true" style="color: white"></i></a></td>
                                <td style="width: 100px">

                                    <div class="dropdown">
                                        <button class="dropbtn" style="background-color: #ff8a05"><font color="black">Seçenekler</font>
                                        </button>
                                        <div class="dropdown-content">
                                            @can('proje-ekleDuzenle')
                                                <a href="{{route('yonetim.gorev.duzenle' , $data->id)}}">Düzenle</a>
                                                @else
                                                @if($data->project_leader==Auth::user()->id)
                                                    <a href="{{route('yonetim.gorev.duzenle' , $data->id)}}">Düzenle</a>
                                                    @endif
                                            @endcan
                                            <a href="{{route('gorev.list' , $data->id)}}">Görevler</a>
                                            <a href="{{route('comment.list' , $data->id)}}">Notlar</a>

                                            @can('proje-sil')

                                                    <a onclick="project_delete({{$data->id}})">Sil</a>
                                            @endcan
                                        </div>
                                    </div>


                                </td>

                            </tr>

                        @endforeach

                        </tbody>
                    </table><br><br>
                  
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
                        },

                        "order": [[0, "desc"]],
                    });


                });
                $.project_info = function (id) {

                    $.post('{{url('yonetim/gorev/view')}}/' + id, {
                        id: id,
                        _token: '{{csrf_token()}}'
                    }, function (ret) {


                        $.alert({

                            title: 'Proje Açıklaması',
                            columnClass: 'col-md-6 col-md-offset-3',
                            type: 'dark',
                            icon: 'fa fa-info-circle',
                            content: '' +


                                '<div class="form-group row">' +
                                ' <label class="col-sm-3 form-control-label">Proje Adı:</label>' +
                                ' <div class="col-sm-9">' +
                                '<p >' + ret.project + '</p>' +
                                '</div>' +
                                '</div>' +
                                '<div class="form-group row">' +
                                ' <label class="col-sm-3 form-control-label">Çalışanlar:</label>' +
                                ' <div class="col-sm-9">' +
                                '<p >' + ret.personal + '</p>' +
                                '</div>' +
                                '</div>' +
                                '<div class="form-group row">' +
                                ' <label class="col-sm-3 form-control-label">Proje Yöneticisi:</label>' +
                                ' <div class="col-sm-9">' +
                                '<p >' + ret.leader + '</p>' +
                                '</div>' +
                                '</div>' +
                                '<div class="form-group row">' +
                                ' <label class="col-sm-3 form-control-label">Başlangıç Tarihi:</label>' +
                                ' <div class="col-sm-9">' +
                                '<p >' + ret.baslangic_tarihi + '</p>' +
                                '</div>' +
                                '</div>' +
                                '<div class="form-group row">' +
                                ' <label class="col-sm-3 form-control-label">Bitiş Tarih:</label>' +
                                ' <div class="col-sm-9">' +
                                '<p >' + ret.bitis_tarihi + '</p>' +
                                '</div>' +
                                '</div>' +
                                '<div class="form-group row">' +
                                ' <label class="col-sm-3 form-control-label">Durumu:</label>' +
                                ' <div class="col-sm-9">' +
                                '<p >' + ret.isShow + '</p>' +
                                '</div>' +
                                '</div>' +
                                '<div class="form-group row">' +
                                ' <label class="col-sm-3 form-control-label">Açıklama:</label>' +
                                ' <div class="col-sm-12">' +
                                '<p >' + ret.aciklama + '</p>' +

                                '</div>' +
                                '</div>'
                              ,

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
                function project_delete(data_id) {

                    $.confirm({

                        type: 'red',
                        typeAnimated: true,
                        icon: 'fa fa-trash',
                        title: 'Uyarı!',
                        content: 'Projeyi silmek istiyor musunuz?',

                        buttons: {
                            formSubmit: {
                                text: 'Sil',
                                btnClass: 'btn-red',
                                action: function () {
                                    var name = this.$content.find('.name').val();
                                    var tid = data_id;
                                    var ajaxurl = "{{url('yonetim/gorev/sil')}}";
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
                                                content: 'Proje silindi!',
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

    </section>

@endsection


