@extends('yonetim.layouts.master')
@section('title', 'Çalışma Takvimi')
@section('content')
    <header class="page-header" style="width: 110%;margin-left: -5%">
        <div class="row-fluid" >
            <h2 class="no-margin-bottom"  id="h2" style="margin-left: 2%" >&nbspÇalışma Takvimi</h2>
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

    {{ Form::open(['route'=>'work.store','method'=>'POST','class'=>'Form-horizontal','files'=>'true'])}}

    <section class="forms animated fadeIn">
        <div class="card" style="background-color: white" id="">


            <div class="card-header d-flex align-items-center">
                <h3 class="h4">Görev Ekleme</h3>
            </div>

            <div class="row card-body max_h_c" id="deneme">

                <div class=" card col-md-6 max_h" id="">


                    <div class="form-group row"><br>
                        <label class="col-sm-3" for="calisanlar">Proje</label>
                        <div class="col-sm-9">
                            <select name="project" id="project" class="form-control"
                                    required>
                                @foreach($project as $row)

                                    <option
                                        value="{{$row->project_name}}">{{$row->project_name}}</option>

                                @endforeach

                            </select>
                        </div>
                    </div>
                    <div class="line"></div>

                    <div class="form-group row">
                        <label class=" col-sm-3 control-label">Alan</label>
                        <div class="col-sm-9">
                            <input type="text" name="field" class="form-control" id="field"
                                   placeholder="Örn:Admin paneli"
                                   value="" required>
                        </div>
                    </div>
                    <div class="line"></div>

                    <div class="form-group row">
                        <label class=" col-sm-3 control-label">İş Kaynağı</label>
                        <div class="col-sm-9">
                            <input type="text" name="supply" class="form-control" id="supply"
                                   placeholder="Örn: Tüzer Rehberlik, Tüzer Teknik..."
                                   value="" >
                        </div>
                    </div>
                    <div class="line"></div>




                    <div class="form-group row">

                        <label class="col-md-3" for="name">Bitiş</label>
                        <div class="col-md-9">
                            <input type="number" class="form-control" id="finish_date" name="finish_date"
                                   placeholder="Tahmini Gün Sayısı"
                                   value="" >
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

                    <div class="">
                        <div class="form-group "><br>
                            <label for="">Açıklama</label><br>
                            <textarea name="content" id="content" cols="30%" rows="14%" style="resize: none"
                                      class="form-control"></textarea>
                            <p class="error" style="color: red;"></p>


                        </div>
                    </div>

                </div>



                <div class="line"></div>
                <div class="form-actions">
                    <button type="submit" style="float: right;margin-top: 1%" class="btn btn-primary">Ekle</button>
                </div>
            </div>



        </div>

    </section>
    {{ Form::close()}}













@endsection

@section('head')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/css/select2.min.css" rel="stylesheet"/>

@endsection


@section('footer')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/js/select2.min.js"></script>
    <script>
        $(function () {
            $('#project').select2({
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
        CKEDITOR.replace( 'content' );
        $("form").submit( function(e) {
            var total_length = CKEDITOR.instances['content'].getData().replace(/<[^>]*>/gi, '').length;
            if( !total_length ) {
                $(".results").html('');
                $(".error").html('Lütfen Açıklama Giriniz' );
                e.preventDefault();
            }

        });
    </script>
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

@endsection


