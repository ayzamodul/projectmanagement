@extends('yonetim.layouts.master')
@section('title', 'Çalışma Takvimi')
@section('content')
    <style>
        input:disabled::-webkit-input-placeholder { /* WebKit browsers */
            color: #fff;
        }

        input:disabled:-moz-placeholder { /* Mozilla Firefox 4 to 18 */
            color: #fff;
        }

        input:disabled::-moz-placeholder { /* Mozilla Firefox 19+ */
            color: #fff;
        }

        input:disabled:-ms-input-placeholder { /* Internet Explorer 10+ */
            color: #fff;
        }
    </style>
    <header class="page-header" style="width: 110%;margin-left: -5%">
        <div class="row-fluid">
            <h2 class="no-margin-bottom" id="h2" style="margin-left: 2%">&nbsp; Çalışma Takvimi</h2>
        </div>
    </header>
    <style>
        .col-md-2 {
            width: auto;
        }
        #ana_div {


            margin-right: 10px;


        }

        .list-type-1 .service-block {


            top: 10px;
        }
        .service-block {


            width: 100px;
        }
        .sa {
            border: 3px solid #069;
            position: relative;
            display: inline-block;


            float: left;

            height: 150px;
            width: 100px;
            margin: 10px;

        }

        .sa img {
            height: 146px;
            width: 94px;
        }

        .sa .btn {
            position: absolute;
            top: 0%;
            left: 90%;
            transform: translate(-50%, -50%);
            -ms-transform: translate(-50%, -50%);

            font-size: 16px;
            padding: 5px 10px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            text-align: center;
        }

        .sa .btn:hover {
            background-color: black;
        }
    </style>
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





    {{ Form::model($work,['route'=>['tasks.update',$work->id],'method'=>'PUT','class'=>'Form-horizontal','files'=>'true'])}}
    <section class="forms animated fadeIn">
        <div class="card" style="background-color: white" id="">

            <input type="hidden" value="{{$entry->id}}" name="project_id">
            <input type="hidden" value="{{$entry->project_name}}" name="project_name">

            <div class="card-header d-flex align-items-center">
                <h3 class="h4">Görev Güncelleme</h3>
            </div>

            <div class="row card-body max_h_c" id="deneme">

                <div class=" card col-md-6 max_h" id="">


                    <div class="form-group row"><br>
                        <label class="col-sm-3" for="calisanlar">Proje</label>
                        <div class="col-sm-9">
                            <input name="projeler" id="projeler" class="form-control"
                                   required disabled style="background-color: white"
                                   value="{{$work->tasks->project_name}}">


                        </div>
                    </div>
                    <div class="line"></div>

                    <div class="form-group row">
                        <label class="col-sm-3" for="user">Çalışan</label>p
                        <div class="col-sm-9">
                            @if($work->add_id==Auth::user()->id)
                                @php($canUpdate =1)
                            @endif
                            <select name="user" id="user" class="form-control"
                                    required {{isset($canUpdate) ? '' : 'disabled' }}>
                                @foreach($user as $row)
                                    <option
                                        value="{{$row->id}}"{{collect(old('user',$work->user_name))->contains($row->name) ? 'selected':''}} >{{$row->name}}</option>

                                @endforeach

                            </select>
                        </div>
                    </div>
                    <div class="line"></div>


                    @if($work->add_id==Auth::user()->id)

                        <div class="form-group row">
                            <label class=" col-sm-3 control-label">Alan</label>
                            <div class="col-sm-9">
                                <input type="text" name="field" class="form-control" id="field"
                                       placeholder="Örn: Admin paneli, Genel..."
                                       value="{{$work->field}}" required>
                            </div>
                        </div>
                        <div class="line"></div>
                    @else

                        <div class="form-group row">
                            <label class=" col-sm-3 control-label">Alan</label>
                            <div class="col-sm-9">
                                <input type="text" name="field" class="form-control" id="field"
                                       placeholder="Örn: Admin paneli, Genel..."
                                       value={{$work->field}}"" disabled style="background-color: white">
                            </div>
                        </div>
                        <div class="line"></div>
                    @endif

                    @if($work->add_id==Auth::user()->id)
                        @php($canUpdate =1)
                    @endif
                    <div class="form-group row">
                        <label class=" col-sm-3 control-label">İş Kaynağı</label>
                        <div class="col-sm-9">
                            <input type="text" name="supply" class="form-control" id="supply"
                                   placeholder="Örn: Tüzer Rehberlik, Tüzer Teknik..."
                                   value="{{$work->add_name}}"
                                   {{isset($canUpdate) ? '' : 'disabled' }} style="background-color: white">
                        </div>
                    </div>
                    <div class="line"></div>


                    <div class="form-group row">
                        @if($work->add_id==Auth::user()->id)
                            @php($canUpdate =1)
                        @endif
                        <label class="col-md-3" for="name">Bitiş</label>
                        <div class="col-md-9">
                            <input type="number" class="form-control" id="finish_date" name="finish_date"
                                   placeholder=""
                                   value="{{$work->finish_date}}" required
                                   {{isset($canUpdate) ? '' : 'disabled' }} style="background-color: white">
                        </div>
                    </div>
                    <div class="line"></div>

                    <div class="form-group row">
                        <label class="col-sm-3">Resim Ekle</label>
                        <div class="col-sm-9">
                            <input class="form-control" type="file" name="imageGalleries[]" multiple id="imageGalleries"
                                   placeholder="Resim Ekle">
                        </div>
                    </div>


                </div>
                <div class="card col-md-6 max_h">
                    @if($work->add_id==Auth::user()->id)
                        @php($canUpdate =1)
                    @endif
                    <div class="">
                        <div class="form-group "><br>
                            <label for="">Açıklama</label><br>
                            <textarea name="content" cols="30%" rows="14%" style="resize: none"
                                      class="form-control"
                                      {{isset($canUpdate) ? '' : 'disabled' }} required>{!!strip_tags($work->content)!!}</textarea>
                        </div>
                    </div>

                </div>


                <div class="line"></div>



                @if(isset($imageGalleries[0]))
                    <div class="line"></div>
                    <div class="card" id="">
                        <div class="card-header d-flex align-items-center" id="">
                            <h3 class="h4">Galeri:</h3>
                        </div>



                        <div class="row list-type-1 notes-colors">
                            @foreach($imageGalleries as  $image)
                                <div class="col-sm-6 col-md-2 subpage-block lightbox mfp-iframe"
                                     onclick="{{Storage::url($image->gallery_image)}}">
                                    @php ($mimType = Storage::mimeType($image->gallery_image))
                                    @if($mimType == "image/gif" || $mimType == "image/jpeg" || $mimType == "image/png" || $mimType == "image/bmp")
                                        <div class="sa">
                                            <a class="fancybox" href="{{Storage::url($image->gallery_image)}}"
                                               data-fancybox="images">

                                                <img
                                                    src="{{Storage::url($image->gallery_image)}}"
                                                    alt=""/>
                                            </a>
                                            @if($image->add_admin==Auth::user()->id)
                                                <form class="form-horizontal" method="post"
                                                      action="{{route('work.imageDelete',@$image->id)}}"
                                                      enctype="multipart/form-data">
                                                    {{csrf_field()}}
                                                    <button class="btn btn-xl btn-danger" data-placement="top"
                                                            title="Sil"
                                                            type="submit"
                                                            onclick="return confirm('Emin misiniz?')"><span
                                                            class="fa fa-trash"></span>
                                                    </button>
                                                </form>

                                            @endif
                                        </div>
                                    @else
                                        <a href="{{Storage::url($image->gallery_image)}}">
                                            <div class="subpage-block lightbox mfp-iframe"
                                                 onclick="{{Storage::url($image->gallery_image)}}">
                                                <div class="service-block">
                                                    <p class="service-info">
                                                        <i class=" pe-7s-icon pe-7s-copy-file"></i>


                                                    <p class="info-text">     {{\App\helper\systemHelper::fileType_notes($mimType)}}</p>
                                                </div>
                                            </div>

                                        </a>

                                    @endif
                                </div>
                            @endforeach
                        </div>



                    </div>
                @endif



                <div class="line"></div>
                @if(isset($feedback) && $feedback)
                    @foreach($feedback as $data)
                        @if(isset($data->isAdmin) && $data->isAdmin==1)
                            <div class="form-group row"><br>
                                <label class="col-sm-2" for=""><h4><span class="label label-danger">İade Notu :</span>
                                    </h4></label>
                                <div class="col-sm-10">
                        <textarea name="" id="" style="resize: none"
                                  class="form-control" disabled>{{$data->notes}}</textarea>
                                </div>
                            </div>
                            <div class="line"></div>



                        @elseif(isset($data->isAdmin) && $data->isAdmin==0)
                            <div class="form-group row"><br>
                                <label class="col-sm-2" for=""><h4><span class="label label-info">Çalışan Notu</span>
                                    </h4></label>
                                <div class="col-sm-10">
                        <textarea name="" id="" style="resize: none; background-color: white"
                                  class="form-control" disabled>{{$data->notes}}</textarea>
                                </div>
                            </div>
                            <div class="line"></div>
                        @else
                        @endif
                    @endforeach
                @endif


            <div class="form-actions">
                <button type="submit" style="float: right;margin-top: 1%" class="btn btn-primary">Güncelle</button>
            </div>
            </div>
        </div>

    </section>

    {{ Form::close()}}






@endsection

@section('head')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/css/select2.min.css" rel="stylesheet"/>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.css" rel="stylesheet"/>

@endsection


@section('footer')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js"></script>
    <script>
        $(document).ready(function ($) {
// ----------------------------------------------------- MaxHeight
            $(".max_h_c").each(function (j, d) {
                var maxHeight = 0;
                $(d).find('.max_h').each(function (i, e) {
                    if (maxHeight < $(e).height()) maxHeight = $(e).height();

                });
                $(d).find('.max_h').height(maxHeight);
            });
        });
    </script>
    <script>
        $(function () {
            $('#projeler').select2({
                placeholder: 'Lütfen calisan seçiniz'

            });

        });


    </script>

    <script>
        $(function () {
            $('#user').select2({
                placeholder: 'Lütfen calisan seçiniz'

            });

        });

    </script>


    <script src="//cdn.ckeditor.com/4.6.2/basic/ckeditor.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ckeditor/4.12.1/plugins/autogrow/plugin.js"></script>
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
        CKEDITOR.replace('content', options);
    </script>



    <script>
        // Init fancyBox
        $().fancybox({
            selector: '.slick-slide:not(.slick-cloned)',
            hash: false

        });

        // Init Slick
        $(".main-slider").slick({
            slidesToShow: 3,
            slidesToScroll: 3,
            infinite: true,
            dots: false,
            arrows: false,
            responsive: [
                {
                    breakpoint: 960,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1
                    }
                }
            ]
        });


    </script>

@endsection












