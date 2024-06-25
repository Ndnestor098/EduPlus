<x-app-layout>
    <x-slot name="title">
        Notas de {{$subject}}
    </x-slot>

    <x-slot name='navigation'>@include('layouts.navigation')</x-slot>

    <x-slot name="header">
        <a href="{{route('teacher.marks')}}" class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Calificacion de ').$subject }}
        </a>
    </x-slot>
    
    

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 container mx-auto">
                    <div>
                        <form action="" method="GET" class="flex flex-wrap md:flex-nowrap justify-between mb-10 items-center gap-5" enctype="application/x-www-form-urlencoded" novalidate autocomplete="off">
                            @csrf
                            <div class="ml-5 w-full">
                                <select name="course" id="course">
                                    <option value="" selected disabled>Selecciona un Curso:</option>
                                    @foreach ($course as $item)
                                        <option value="{{$item->course}}">Curso {{$item->course}}</option>
                                    @endforeach
                                </select>
                            </div> 
                            <div class="ml-5 w-full">
                                <input type="text" name="name" id="name" placeholder="Buscar Estudiante">
                            </div>
                            <div class="flex items-center justify-center w-full md:justify-end">
                                <button class="button-search mr-5">Buscar</button>
                            </div>
                        </form>
                    </div>
                    <table class="table">
                        <tr>
                            <th style="background-color: rgb(238, 238, 238)">Nombre</th>
                            <th style="background-color: rgb(238, 238, 238)">Curso</th>
                            <th style="background-color: rgb(238, 238, 238)">Materia</th>
                            <th style="background-color: rgb(238, 238, 238)">Calificacion</th>
                        </tr>
                        @foreach ($students as $item)
                            <tr>    
                            <td class="text-center w-6">
                                <a href="{{route("student.qualification.alone", ['subject'=>'matematicas'])}}">
                                    {{ $item->student->name }} 
                                </a>
                            </td>
                            <td class="text-center w-6">
                                <a href="{{route("student.qualification.alone", ['subject'=>'matematicas'])}}">
                                    {{ $item->student->course }} 
                                </a>
                            </td>
                            <td class="text-center w-6">
                                <a href="{{route("student.qualification.alone", ['subject'=>'matematicas'])}}">
                                    {{ $subject }} 
                                </a>
                            </td>

                            <td class="text-center" @if($item->subject < 6) style="background-color: #e969697c; font-weight: 600;"
                                                        @else style="background-color: #3cc2437a; font-weight: 600;" 
                                                        @endif>
                                                        @if($item->subject)<a href="{{route("student.qualification.alone", ['subject'=>'historia'])}}">
                                                            {{ $item->subject }} 
                                                        </a>

                                                        @else 
                                                            No hay nota 
                                                        @endif
                            </td>

                        </tr>
                        @endforeach
                        
                    </table>
                    <div class=" pt-2">
                        {{$students->links()}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
