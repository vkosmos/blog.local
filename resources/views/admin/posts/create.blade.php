@extends('admin.layout')

@section('content')

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Добавить статью
                <small>приятные слова..</small>
            </h1>
        </section>

        <!-- Main content -->
        <section class="content">


            <form method="POSt" action="{{route('posts.store')}}" enctype="multipart/form-data">
                @csrf

                <!-- Default box -->
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Добавляем статью</h3>
                        @include('admin.errors')
                    </div>
                    <div class="box-body">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Название</label>
                                <input type="text" name="title" class="form-control" id="exampleInputEmail1" placeholder="" value="{{old('title')}}">
                            </div>

                            <div class="form-group">
                                <label for="exampleInputFile">Лицевая картинка</label>
                                <input type="file" id="exampleInputFile" name="image">

                                <p class="help-block">Какое-нибудь уведомление о форматах..</p>
                            </div>

                            <div class="form-group">
                                <label>Категория</label>

                                <select class="form-control select2" style="width: 100%;" name="category_id">
                                    @foreach($categories as $key => $cat)
                                    <option value="{{$key}}">{{$cat}}</option>
                                    @endforeach
                                </select>

                            </div>

                            <div class="form-group">
                                <label>Теги</label>

                                <select class="form-control select2" multiple="multiple" data-placeholder="Выберите теги" style="width: 100%;" name="tags[]">
                                    @foreach($tags as $key => $tag)
                                    <option value="{{$key}}">{{$tag}}</option>
                                    @endforeach
                                </select>

                            </div>

                            <!-- Date -->
                            <div class="form-group">
                                <label>Дата:</label>

                                <div class="input-group date">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" class="form-control pull-right" id="datepicker" name="date" value="{{old('date')}}">
                                </div>
                                <!-- /.input group -->
                            </div>

                            <!-- checkbox -->
                            <div class="form-group">
                                <label>
                                    <input type="checkbox" class="minimal" name="is_featured">
                                </label>
                                <label>
                                    Рекомендовать
                                </label>
                            </div>

                            <!-- checkbox -->
                            <div class="form-group">
                                <label>
                                    <input type="checkbox" class="minimal" name="status">
                                </label>
                                <label>
                                    Черновик
                                </label>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Описание</label>
                                <textarea id="" cols="30" rows="10" class="form-control" name="description">
                                    {{old('description')}}
                                </textarea>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Полный текст</label>
                                <textarea id="" cols="30" rows="10" class="form-control" name="content"></textarea>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <button class="btn btn-default">Назад</button>
                        <button class="btn btn-success pull-right">Добавить</button>
                    </div>
                    <!-- /.box-footer-->
                </div>
                <!-- /.box -->
            </form>


        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

@endsection
