@extends('yonetim.layouts.master')
@section('title','Personel Takibi')
@section('content')

    <link href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.css" rel="stylesheet"/>
    <style>
        .jconfirm .jconfirm-box div.jconfirm-content-pane .jconfirm-content {
            overflow: hidden;
        }

        .jconfirm {
            z-index: 9999;
        }
    </style>
    <header class="page-header" style="width: 110%;margin-left: -5%">
        <div class="row-fluid">
            <h2 class="no-margin-bottom" id="h2" style="margin-left: 2%">&nbsp; Personel Takibi</h2>
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

        <div class="card animated fadeIn" style="background-color: white" id="">


            <div class="card-header d-flex align-items-center">
                <h3 class="h4">Çalışma Listesi</h3>

            </div>
            <select data-column="1" class="col-md-2 filter-select"
                    style="margin-top: 15px; height: 30px ; width: 250px;left: 21px;">
                <option value="">Personel Seç</option>
                @foreach($user as $row)
                    <option value="{{$row->name}}">{{$row->name}}</option>
                @endforeach
            </select><br><br>

            <div class="card-body row-list-table">
                <div class="table-responsive">

                    <table class="responsive table table-hover table-bordered" id="myTable">
                        <thead class="thead-dark">
                        <tr>

                            <th>#</th>
                            <th>Çalışan</th>
                            <th>Proje Adı</th>
                            <th>Alan</th>

                            <th>Açıklama</th>
                            <th>Durumu</th>




                            <th>Detay</th>
                            <th>İşlem Yap</th>


                        </tr>
                        </thead>
                        <tbody>


                        </tbody>
                    </table>

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

            var table = $('#myTable').DataTable({


                "processing": false,
                "serverSide": true,
                "order": [[0, "desc"]],
                "ajax": {
                    url: "{{url('yonetim/gorev/datatable')}}?type=cons_demo_st",
                    type: 'GET'
                },

                'columns': [
                    {data: 'id', name: 'id'},
                    {data: 'user_name'},
                    {data: 'project_name', name: 'project_name'},
                    {data: null, name: 'field', render: function (data) {
                            var html = data.field;
                            var text2 = $('<textarea />').html(html).text();
                            return text2.length > 50 ?
                                text2.substr(0,50)+ '...':
                                text2;
                        }
                    },


                    {
                        data: null, name: 'content', render: function (data) {
                            var html = data.content;
                            var text2 = $('<textarea />').html(html).text();
                            return text2.length > 100 ?
                                text2.substr(0,100)+ '...':
                                text2;
                        }
                    },
                    {

                        data: null,
                        render: function (data) {

                            if (data.status == 0) {
                                var html = "  <span class='label label-info'>İşlemde</span>";

                                return html;
                            } else if (data.status == 1) {
                                var html = "    <span class='label label-warning'>Beklemede</span>";

                                return html;
                            } else if (data.status == 2) {
                                var html = "    <span class='label label-success' style='background-color: #6736FF'>Tamamlandı</span>";

                                return html;
                            } else if (data.status == 3) {
                                var html = "    <span class='label label-danger'>İade</span>";

                                return html;
                            } else {
                                var html = "    <span class='label label-success'>Onaylandı</span>";

                                return html;
                            }

                        },
                        "searchable": false, "orderable": false
                    },



                    {
                        data: null, render: function (data) {

                            return '<a class="btn btn-info"  onclick="$.work_info(' + data.id + ')"><i class="fa fa-list" aria-hidden="true" style="color: white"></i></a>'


                        },
                        "searchable": false,
                        "orderable": false,
                    },
                        @can('personel-takibi')
                    {

                        data: null, render: function (data) {
                            var html = "<div class='dropdown'>" +
                                "  <button class='dropbtn' style='background-color: #ff8a05'>" +
                                "<font color='black'>Seçenekler</font></button>" +
                                "  <div class='dropdown-content'>" +
                                "<a onclick='work_feedback(" + data.id + ")'>İade Et</a>" +
                                "<a onclick='work_feedbackPositive(" + data.id + ")'>Onayla</a>" +
                                "     </div>" +
                                "     </div>";
                            return html;

                        }, "searchable": false,
                        "orderable": false,

                    },

                    @endcan

                ],

                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/a5734b29083/i18n/Turkish.json"
                },

                responsive: true,
            });


            $('.filter-select').change(function () {

                table.column($(this).data('column'))
                    .search($(this).val())
                    .draw();
            });
            $.work_info = function (id) {
                $.post('{{url('yonetim/gorev/work/detailWork')}}/' + id, {
                    id: id,
                    _token: '{{csrf_token()}}'
                }, function (ret) {


                    $.alert({
                        title: 'Detaylar',
                        columnClass: 'col-md-10 col-md-offset-1',
                        type: 'dark',
                        backgroundDismiss: true,
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


                                return '<div class="row form-group row"><label class="col-sm-12 form-control-label">Kullanıcı Notu:</label> <div class="col-sm-12" ><p>'+s+' </p></div>';


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
                                text: 'Kapat',
                                action: function () {
                                }
                            }
                        },
                    });
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
                            var ajaxurl = "{{url('yonetim/gorev/feedback')}}";
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

        function work_feedbackPositive(data_id) {

            $.confirm({
                columnClass: 'col-md-5 col-md-offset-4',
                type: 'dark',
                icon: 'fa fa-info-circle',
                title: 'Lütfen not giriniz!',
                content: 'Görevi onaylamak istiyor musunuz?',

                buttons: {
                    formSubmit: {
                        text: 'Onayla',
                        btnClass: 'btn-blue',
                        action: function () {
                            var name = this.$content.find('.name').val();
                            var tid = data_id;
                            var ajaxurl = "{{url('yonetim/gorev/feedbackPositive')}}";
                            var token = ' {{csrf_token()}}';
                            console.log(ajaxurl);
                            $.post(ajaxurl, {
                                _token: token,
                                tid: tid
                            }, function (ret) {

                                if (ret == 1) {

                                    $.confirm({
                                        title: 'Bilgi',

                                        type: 'dark',
                                        icon: 'fa fa-info-circle',
                                        content: 'Görev onaylandı.',
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

    <script type="text/javascript" src="{{asset('js/fancybox.js')}}"></script>
@endsection


