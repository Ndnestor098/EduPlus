<x-app-layout>
    <x-slot name="title">
        Agregar Metodo de calificaciones
    </x-slot>

    <x-slot name='navigation'>@include('layouts.navigation')</x-slot>

    <x-slot name="header">
        <a href="{{route("teacher.qualification")}}" class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Metodo de Calificaciones') }}
        </a>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 container mx-auto">
                    <div class="flex gap-5 mb-8">
                        <a href="{{route("teacher.qualification")}}"><img src="/assets/img/back.png" alt="back" style="height: 25px"></a><span class="text-lg font-semibold">Crea una nueva tarea</span>
                    </div>

                    <div class="flex items-center justify-center flex-col">
                        <form action="" method="POST" class="form" enctype="application/x-www-form-urlencoded" novalidate autocomplete="on">
                            @csrf
                            <input type="hidden" name="value" value="{{$search->id}}">
                            <div>
                                <label for="workType">Metodo de Calificacion</label>
                                <select name="workType" id="workType" required autofocus>
                                    <option value="{{$search->WorkType->name}}" selected>{{$search->WorkType->name}}</option>
                                    @foreach ($method as $item)
                                        <option value="{{$item->name}}">{{$item->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="percentage">Porcentajes a Asignar</label>
                                <input type="number" name="percentage" value="{{$search->percentage}}" placeholder="Porcentaje *" step="0,01" min="0" max="100">
                            </div>
                            <div>
                                <label>Materia</label>
                                <input type="text" value="{{$subject}}" disabled>
                            </div>
                            <div>
                                <label for="course">Curso o Año</label>
                                <select name="course" id="course">
                                    <option value="{{$search->course}}" selected>Año {{$search->course}}</option>
                                    @foreach ($course as $item)
                                        <option value="{{$item->course}}">Año {{$item->course}}</option>
                                    @endforeach
                                </select>
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
</x-app-layout>
