@extends('yonetim.layouts.master')
@section('title','Proje Yönetimi')
@section('content')
    <header class="page-header" style="width: 110%;margin-left: -5%">
        <div class="row-fluid">
            <h2 class="no-margin-bottom" id="h2" style="margin-left: 2%">&nbsp; Proje Yönetimi</h2>
        </div>
    </header>
<style>
    input {
        border: 2px solid whitesmoke;

        padding: 12px 10px;

        width: 250px;
    }
    button {
        border: none;

        padding: 12px 10px;
        text-align: center;
        cursor: pointer;
        background: coral;
        color: whitesmoke;
    }
    img {
        width: 50px;
        border-radius: 50px;
    }

</style>
    <section class="animated fadeIn">

        <div class="card" style="background-color: white" id="deneme">


            <div class="row card-body">

                <form method="post" action="{{route('yonetim.gorev.kaydet', @$entry->id)}}" class="">

                    {{csrf_field()}}



                    <div class="card-header d-flex align-items-center">
                        <h3 class="h4">Proje {{@$entry->id > 0 ? "Düzenle" : "Ekle"}}</h3>
                    </div>
                    <br>
                    <div class="container col-md-12">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="name">Proje Adı:</label>
                                    <input type="text" class="form-control" id="project_name" name="project_name" placeholder=""
                                           value="{{$entry->project_name}}">
                                </div>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="calisanlar">Çalışanlar</label><br>
                                    <select name="calisanlar[]" id="calisanlar" class="form-control" multiple
                                            required style="width: 100%">
                                        @foreach($calisanlar as $users)
                                            <option
                                                value="{{$users->id}}"{{collect(old('calisanlar',$calisan_goster))->contains($users->id) ? 'selected':''}}>{{$users->name}}</option>

                                        @endforeach

                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="calisanlar">Proje Yöneticisi</label><br>
                                    <select name="leader" id="leader" class="form-control"
                                            required style="width: 100%">
                                        @foreach($calisanlar as $users)
                                            <option
                                                value="{{$users->id}}"{{$users->id==$entry->project_leader ? 'selected':''}}>{{$users->name}}</option>

                                        @endforeach

                                    </select>
                                </div>
                            </div>
                        </div>

                        <br>

<div class="row">
                            <div class="col-md-4 ">
                                <div class="form-group">
                                    <label for="name">Başlangıç Tarihi</label>
                                    <input type="date" class="basicDate" id="baslangic_tarihi"
                                           name="baslangic_tarihi" placeholder="Başlangıç tarihi seçiniz."
                                           value="{{$entry->baslangic_tarihi}}" data-input>
                                </div>
                            </div>


                            <div class="col-md-4" >
                                <div class="form-group">
                                    <label for="name">Bitiş Tarihi</label>
                                    <input type="date" class="basicDate" id="bitis_tarihi" name="bitis_tarihi"
                                           placeholder="Bitiş tarihi seçiniz."
                                           value="{{$entry->bitis_tarihi}}" data-input>
                                </div>
                            </div>

                                <div class="col-md-4" >
                                    <label for="">Proje Aktif mi ?</label>
                                    <select class="form-control" name="isShow" id="{{$entry->isShow}}">
                                        <option value="0">Aktif</option>
                                        <option value="1">Pasif</option>
                                        <option value="3">Bitti</option>
                                    </select>
                                </div>
                    </div>
                        </div>


                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">İçerik</label>
                                    <textarea name="aciklama" id="" cols="30%" rows="10%"
                                              class="form-control" required>{{$entry->aciklama}}</textarea>
                                </div>
                            </div>




                        <br>

                    <div class="pull-right">
                        <button type="submit" class="btn btn-primary" style="margin-top: 12%">
                            {{@$entry->id > 0 ? "Güncelle" : "Kaydet"}}
                        </button>
                    </div>
                </form>


            </div>
        </div>

    </section>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>




@endsection

@section('head')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/css/select2.min.css" rel="stylesheet"/>

@endsection


@section('footer')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/js/select2.min.js"></script>
    <script>
        $(function () {
            $('#calisanlar').select2({
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
        $(".basicDate").flatpickr({
            disableMobile: "true",

            dateFormat: "Y-m-d H:i:s",
            locale: "tr"
        });

    </script>
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
        CKEDITOR.replace('aciklama', options);
    </script>



@endsection

