<x-app-layout>
    <x-slot name="title">
        Editar Actividades
    </x-slot>

    <x-slot name='navigation'>@include('layouts.navigation')</x-slot>

    <x-slot name="header">
        <a href="{{route("teacher.works")}}" class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Actividades') }}
        </a>
    </x-slot>
    
    

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 container mx-auto">
                    <div class="flex gap-5 mb-8">
                        <a href="{{route("teacher.works")}}"><img src="/assets/img/back.png" alt="back" style="height: 25px"></a><span class="text-lg font-semibold">Crea una nueva tarea</span>
                    </div>

                    <div class="flex items-center justify-center flex-col">
                        <form action="" method="POST" class="form" enctype="multipart/form-data" novalidate autocomplete="on">
                            @csrf
                            @method("put")
                            <input type="hidden" name="id" value="{{$work->id}}">
                            <div>
                                <label for="title">Titulo</label>
                                <input type="text" name="title" id="title" placeholder="Titulo *" value="{{$work->title}}" required autofocus>
                            </div>
                            <div>
                                <label for="description">Descripcion</label>
                                <textarea name="description" id="description" cols="40" rows="10" required autofocus placeholder="Descripcion *">{{$work->description}}</textarea>
                            </div>
                            <div>
                                <label for="file">File (pdf, doc, etc..) <span>No obligatorio</span></label>
                                <a @if($work->file) href="{{$work->file}}" target="_blank" @endif >File: {{$work->file}}</a>
                                <input type="file" name="file" id="file">
                            </div>
                            <div>
                                <label for="image">Imagen <span>No obligatorio</span></label>
                                <a @if($work->image) href="{{$work->image}}" target="_blank" @endif>image: {{$work->image}}</a>
                                <input type="file" name="image" id="image" accept="image/*">
                            </div>
                            <div>
                                <label>Materia</label>
                                <input type="text" id="subject" value="{{$work->subject}}"  disabled>
                            </div>
                            <div>
                                <label for="course">Curso o Año</label>
                                <select name="course" id="course">
                                    <option value="{{$work->course}}" selected>Seleccionado: Año {{$work->course}}</option>
                                    @foreach ($course as $item => $x)
                                        <option value="{{$x->course}}">Año {{$x->course}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="qualification">Metodo Calificativo</label>
                                <select name="qualification" id="qualification">
                                    <option value="{{$work->mtcf}}" selected>Seleccionado: {{$work->mtcf}}</option>
                                </select>
                            </div>
                            <div class="flex gap-5" style="flex-direction: row"> 
                                <label for="">Publicar: </label>
                                <div class="flex items-center" style="flex-direction: row">
                                    <span class="w-7">No</span>
                                    <input class="p-2 rounded" type="radio" name="public" required id="public" @if($work->public == 0) checked @endif value="0">
                                </div>
                                <div class="flex items-center" style="flex-direction: row">
                                    <span class="w-5">Si</span>
                                    <input class="p-2 rounded" type="radio" name="public" required id="public" @if($work->public == 1) checked @endif value="1">
                                </div>
                            </div>
                            <div>
                                <label for="deliver">Fecha de Entrega</label>
                                <input type="date" name="deliver" id="deliver" value="{{$work->deliver}}" min="{{ date('Y-m-d') }}" required autofocus>
                            </div>
                            
                            <p class="error text-center font-semibold" style="color: rgb(161, 44, 44)">
                                @if (gettype($errors) != gettype((object)array('1'=>1)))
                                    {{ $errors }}
                                @endif
                            </p>
                            
                            <div>
                                <button type="submit" class="button-update">Actualizar</button>
                            </div>
                        </form>
                        <form action="" method="POST" class="form mt-5" enctype="application/x-www-form-urlencoded" novalidate autocomplete="on">
                            @csrf
                            @method("delete")
                            <button type="submit" class="button-delete">Eleminar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>  
    <input type="hidden" id="csrf" value="{{ csrf_token() }}">
    <script src="/assets/js/work.js"></script>
</x-app-layout>
