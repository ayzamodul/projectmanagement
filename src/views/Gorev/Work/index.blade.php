@extends('yonetim.layouts.master')
@section('title','Çalışma Takvimi')
@section('content')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.css" rel="stylesheet"/>

    <style>
        .jconfirm .jconfirm-box div.jconfirm-content-pane .jconfirm-content {
            overflow: hidden;
        }

        .jconfirm{
            z-index:9999;
        }
    </style>
    <header class="page-header" style="width: 110%;margin-left: -5%">
        <div class="row-fluid">
            <h2 class="no-margin-bottom" id="h2" style="margin-left: 2%">&nbsp; Çalışma Takvimi</h2>
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
    <section>

        <div class="card animated fadeIn" style="background-color: white" id="deneme">

            <div class="btn-group pull-right">

                <a href="{{route('work.create')}}" class="btn btn-primary" style="margin-top: 12%">Görev Ekle</a>

            </div>
            <div class="card-header d-flex align-items-center">
                <h3 class="h4">Görev Listesi</h3>
            </div>


            <div class="card-body row-list-table">
                <div class="table-responsive">

                    <table class="responsive table table-hover table-bordered" id="myTab">
                        <thead class="thead-dark">
                        <tr>

                            <th>#</th>
                            <th>İş Kaynağı</th>
                            <th>Proje Adı</th>
                            <th>Alan</th>
                            <th>Açıklama</th>
                            <th>İş Durumu</th>





                            <th>Detay</th>
                            <th>Seçenekler</th>


                        </tr>
                        </thead>
                        <tbody>


                        @foreach($list as $data)
                            <tr>

                                <td>{{$data->id}}</td>
                                @if(isset($data->add_name))
                                <td>{{$data->add_name}}
                                    @else
                                    <td>{{$data->add_user[0]->name}}
                               @endif
                                <td>{{$data->project_name}}</td>
                                <td>{!! substr(strip_tags($data->field), 0 , 50) !!}{{strlen(strip_tags($data->field))> 50 ?"...":""}}</td>

<td>{!! substr(strip_tags($data->content), 0 , 100) !!}{{strlen(strip_tags($data->content))> 100 ?"...":""}}</td>
                                <td>
                                    @if ($data->status==0)
                                        <span class="label label-info">İşlemde</span>
                                    @elseif($data->status==1)
                                        <span class="label label-warning">Beklemede</span>
                                    @elseif($data->status==2)
                                        <span class="label label-success"
                                              style="background-color: #6736FF">Tamamlandı</span>
                                    @elseif($data->status==3)
                                        <span class="label label-danger">İade</span>
                                    @else
                                        <span class="label label-success">Onaylandı</span>
                                    @endif
                                </td>





                                <td><a class="btn btn-info" onclick="$.work_info({{$data->id}})"><i
                                            class="fa fa-list" aria-hidden="true" style="color: white"></i></a></td>

                                <td>

                                    <div class="dropdown">
                                        <button class="dropbtn" style="background-color: #ff8a05"><font color="black">Seçenekler</font>
                                        </button>
                                        <div class="dropdown-content">
                                            <a href="{{route('work.edit',$data->id)}}"
                                               data-toggle="tooltip"
                                               data-placement="top" title="Düzenle">
                                                Düzenle
                                            </a>
                                            <a onclick="work_feedback({{$data->id}})">Not</a>
                                            <a href="{{route('yonetim.work.aktif' , $data->id)}}" id="" name="aktif">İşlemde</a>
                                            <a href="{{route('yonetim.work.pasif' , $data->id)}}">Beklemede</a>
                                            <a href="{{route('yonetim.work.bitti' , $data->id)}}">Tamamlandı</a>
                                            @role('Admin')
                                            <a onclick="work_delete({{$data->id}})">Sil</a>
                                            @else
                                                @if(Auth::user()->id == $data->add_id)
                                                    <a onclick="work_delete({{$data->id}})">Sil</a>
                                                @else
                                                    <a  onclick="myFunction()"><i>Sil</i></a>
                                                @endif
                                                @endrole
                                        </div>
                                    </div>

                                </td>


                            </tr>

                        @endforeach

                        </tbody>

                    </table>     <br>     <br>    <br>

                </div>
            </div>

        </div>
    </section>
@endsection
@section('footer')
    <script
        src="https://code.jquery.com/jquery-3.4.1.js"
        integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU="
        crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>


    <script>
        $(document).ready(function () {


            var table = $('#myTab').DataTable({

                responsive: true,
                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/a5734b29083/i18n/Turkish.json"
                },
                "ordering": false


            });
            var selam = $.work_info = function (id) {
                $.post('{{url('yonetim/gorev/work/detailWork')}}/' + id, {
                    id: id,
                    _token: '{{csrf_token()}}'
                }, function (ret) {

                    var currnt_image_list = ret.image + '';

                   var selam = $.alert({

                        title: 'Detaylar',
                       columnClass: 'col-md-10 col-md-offset-1',
                        type: 'dark',
                        backgroundDismiss: true,
                       scrollToPreviousElement: true,
                       alignMiddle: true,
                        icon: 'fa fa-info-circle',

                        content: '' +

                            ' <div class="row form-group col-sm-6">' +
                            ' <label class="row col-sm-6 form-control-label">Sisteme Giren Kişi:</label>' +
                            ' <div class="col-sm-6">' +
                            '<p >' + ret.add_user + '</p>' +
                            '</div>' +
                            '</div>' +
                            ' <div class="row form-group col-sm-6">' +
                            ' <label class="row col-sm-6 form-control-label">Alan:</label>' +
                            ' <div class="col-sm-6">' +
                            '<p >' + ret.field + '</p>' +
                            '</div>' +
                            '</div>' +
                            ret.add_name.map(function (s) {


                                return '<div class="row form-group col-sm-6"><label class="row col-sm-6 form-control-label">İş Kaynağı:</label> <div class="col-sm-6" ><p>'+s+' </p></div></div>';


                            }) +
                            ' <div class="row form-group col-sm-6">' +
                            ' <label class="row col-sm-6 form-control-label">Veriliş Tarihi:</label>' +
                            ' <div class="col-sm-6">' +
                            '<p >' + ret.start_date + '</p>' +
                            '</div>' +
                            '</div>' +
                            ' <div class="row form-group col-sm-6">' +
                            ' <label class="row col-sm-6 form-control-label">Bitmesi Gereken Gün:</label>' +
                            ' <div class="col-sm-6">' +
                            '<p >' + ret.finish_date + '</p>' +
                            '</div>' +
                            '</div>' +
                            ' <div class="row form-group col-sm-6">' +
                            ' <label class="row col-sm-6 form-control-label">Durum:</label>' +
                            ' <div class="col-sm-6">' +
                            '<p >' + ret.status + '</p>' +
                            '</div>' +
                            '</div>' +










                            ret.user_start_date.map(function (s) {


                                return '<div class="row form-group col-sm-6"><label class="row col-sm-6 form-control-label">Başlanan Tarih:</label> <div class="col-sm-6" ><p>'+s+' </p></div></div>';


                            }) +



                            ret.user_finish_date.map(function (s) {


                                return '<div class="row form-group col-sm-6"><label class="row col-sm-6 form-control-label">Bitiş Tarihi:</label> <div class="col-sm-6" ><p>'+s+' </p></div></div>';


                            }) +


                            ret.gun.map(function (s) {


                                return '<div class="row form-group col-sm-6"><label class="row col-sm-6 form-control-label">İş Süresi:</label> <div class="col-sm-6" ><p>'+s+' </p></div></div>';


                            }) +





                            ' <div class="row form-group col-sm-12">' +
                            ' <label class="row col-sm-6 form-control-label">Görev Açıklama:</label>' +
                            ' <div class="col-sm-12">' +
                            '<p >' + ret.task + '</p>' +
                            '</div>' +
                            '</div>' +



                            ret.feedback.map(function (s) {


                                return '<div class="row form-group row"><label class="col-sm-12 form-control-label">İade Nedeni:</label> <div class="col-sm-12" ><p>'+s+' </p></div></div>';


                            }) +



                            ret.feedbackUser.map(function (s) {


                                return '<div class="row form-group row"><label class="col-sm-12 form-control-label">Kullanıcı Notu:</label> <div class="col-sm-12" ><p>'+s+' </p></div></div>';


                            }) +



                            ret.image.map(function (s) {


                                return '<a class="fancybox"  href="/storage/'+s+'" data-fancybox="images" target="_blank"> <img src="/storage/' + s + '" style="height: 64px; width: 64px" /> ';

                            })+


                       ret.file.map(function (s) {

2
                           return '<a  href="/storage/'+s+'" style="padding: 10px"><i class="fas fa-file"> Dosya Eki</i></a>'

                       }),




                       buttons: {

                            cancel: {
                                name: 'cancel',
                                text: 'Kapat',
                                action: function () {
                                }
                            },



                        },
                    }) ;
                }, "json");

            }


        });


        function myFunction() {
            $.alert({
                title: 'Silinemez!',
                content: 'Bu görev, yöneticiniz tarafından verildiği için silinemez. Bir hata olduğunu düşünüyorsanız lütfen proje yöneticinizle iletişime geçin.',
                type: 'red',
                icon: 'fa fa-warning',
                typeAnimated: true,
                buttons: {

                    Kapat: function () {
                    }
                }

            });
        }
        function work_delete(data_id) {

            $.confirm({

                type: 'red',
                typeAnimated: true,
                icon: 'fa fa-trash',
                title: 'Uyarı!',
                content: 'Çalışmayı silmek istiyor musunuz?',

                buttons: {
                    formSubmit: {
                        text: 'Sil',
                        btnClass: 'btn-red',
                        action: function () {
                            var name = this.$content.find('.name').val();
                            var tid = data_id;
                            var ajaxurl = "{{url('yonetim/gorev/work/sil')}}";
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
                                        content: 'Çalışma silindi!',
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
        function work_feedback(data_id) {

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
                            var ajaxurl = "{{url('yonetim/gorev/notesFeedback')}}";
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
                                        content: 'Geri bildiriminiz iletildi.',
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
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js"></script>


@endsection


