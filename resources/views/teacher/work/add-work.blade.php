<x-app-layout>
    <x-slot name="title">
        Profesores Agregar
    </x-slot>

    <x-slot name='navigation'>@include('layouts.navigation')</x-slot>

    <x-slot name="header">
        <a href="{{route("teacher.works")}}" class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profesores') }}
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
                            <div>
                                <label for="title">Titulo</label>
                                <input type="text" name="title" id="title" placeholder="Titulo *" required autofocus>
                            </div>
                            <div>
                                <label for="description">Descripcion</label>
                                <textarea name="description" id="description" cols="40" rows="10" required autofocus placeholder="Descripcion *"></textarea>
                            </div>
                            <div>
                                <label for="file">File (pdf, doc, etc..) <span>No obligatorio</span></label>
                                <input type="file" name="file" id="file">
                            </div>
                            <div>
                                <label for="image">Imagen <span>No obligatorio</span></label>
                                <input type="file" name="image" id="image">
                            </div>
                            <div>
                                <label>Materia</label>
                                <input type="text" id="subject" value="{{$info}}" disabled>
                            </div>
                            <div>
                                <label for="course">Curso o Año</label>
                                <select name="course" id="course">
                                    <option value="" disabled selected>Selecciona el curso o año:</option>
                                    @foreach ($course as $item => $x)
                                        <option value="{{$x->course}}">Año {{$x->course}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="qualification">Metodo Calificativo</label>
                                <select name="qualification" id="qualification">
                                    <option value="" disabled selected>Selecciona el metodo calificativo:</option>
                                </select>
                            </div>
                            <div class="flex gap-5" style="flex-direction: row"> 
                                <label for="">Publicar: </label>
                                <div class="flex items-center" style="flex-direction: row">
                                    <span class="w-7">No</span>
                                    <input class="p-2 rounded" type="radio" name="public" required id="public" value="0">
                                </div>
                                <div class="flex items-center" style="flex-direction: row">
                                    <span class="w-5">Si</span>
                                    <input class="p-2 rounded" type="radio" name="public" required id="public" value="1">
                                </div>
                            </div>
                            <div>
                                <label for="deliver">Fecha de Entrega</label>
                                <input type="date" name="deliver" id="deliver" min="{{ date('Y-m-d') }}" required autofocus>
                            </div>
                            
                            <p class="error text-center font-semibold" style="color: rgb(161, 44, 44)">
                                @if (gettype($errors) != gettype((object)array('1'=>1)))
                                    {{ $errors }}
                                @endif
                            </p>
                            
                            <div>
                                <button type="submit" class="button-update">Agregar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>  
    <input type="hidden" id="csrf" value="{{ csrf_token() }}">
    <script src="/assets/js/work.js"></script>
</x-app-layout>
