<x-app-layout>
    <x-slot name="title">
        Notas Generales
    </x-slot>

    <x-slot name='navigation'>@include('layouts.navigation')</x-slot>

    <x-slot name="header">
        <a href="{{route('administrator.qualifications')}}" class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Calificaciones Generales') }}
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
                            <th style="background-color: rgb(238, 238, 238)">Curso o Año</th>
                            <th style="background-color: rgb(238, 238, 238)">Matematicas</th>
                            <th style="background-color: rgb(238, 238, 238)">Ingles</th>
                            <th style="background-color: rgb(238, 238, 238)">Fisica</th>
                            <th style="background-color: rgb(238, 238, 238)">Ciencias</th>
                            <th style="background-color: rgb(238, 238, 238)">Computacion</th>
                            <th style="background-color: rgb(238, 238, 238)">Literatura</th>
                            <th style="background-color: rgb(238, 238, 238)">Arte</th>
                            <th style="background-color: rgb(238, 238, 238)">Historia</th>
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

                            <td class="text-center  {{ !empty($item->matematicas) ? $item->matematicas < 6 ? 'bg-[#e969697c]' : 'bg-[#3cc2437a]' : ''}}">
                                    @if($item->matematicas)
                                        <a href="{{route("student.qualification.alone", ['subject'=>'matematicas'])}}">
                                            {{ $item->matematicas }} 
                                        </a>
                                    @else 
                                        No hay nota 
                                    @endif                    
                            </td>

                            <td class="text-center {{ !empty($item->ingles) ? $item->ingles < 6 ? 'bg-[#e969697c]' : 'bg-[#3cc2437a]' : ''}}">
                                    @if($item->ingles)
                                        <a href="{{route("student.qualification.alone", ['subject'=>'ingles'])}}">
                                            {{ $item->ingles }}
                                        </a> 
                                    @else 
                                        No hay nota 
                                    @endif                    
                            </td>

                            <td class="text-center {{ !empty($item->fisica) ? $item->fisica < 6 ? 'bg-[#e969697c]' : 'bg-[#3cc2437a]' : ''}}">
                                    @if($item->fisica)
                                        <a href="{{route("student.qualification.alone", ['subject'=>'fisica'])}}">
                                            {{ $item->fisica }} 
                                        </a>
                                    @else 
                                        No hay nota 
                                    @endif                    
                            </td>

                            <td class="text-center {{ !empty($item->ciencia) ? $item->ciencia < 6 ? 'bg-[#e969697c]' : 'bg-[#3cc2437a]' : ''}}">
                                @if($item->ciencia)
                                    <a href="{{route("student.qualification.alone", ['subject'=>'ciencia'])}}">
                                        {{ $item->ciencia }} 
                                    </a>
                                @else 
                                    No hay nota 
                                @endif
                            </td>

                            <td class="text-center {{ !empty($item->computacion) ? $item->computacion < 6 ? 'bg-[#e969697c]' : 'bg-[#3cc2437a]' : ''}}">
                                @if($item->computacion)
                                    <a href="{{route("student.qualification.alone", ['subject'=>'computacion'])}}">
                                        {{ $item->computacion }}
                                    </a> 
                                @else 
                                    No hay nota 
                                @endif
                            </td>

                            <td class="text-center {{ !empty($item->literatura) ? $item->literatura < 6 ? 'bg-[#e969697c]' : 'bg-[#3cc2437a]' : ''}}">
                                @if($item->literatura)
                                    <a href="{{route("student.qualification.alone", ['subject'=>'literatura'])}}">
                                        {{ $item->literatura }} 
                                    </a>
                                @else 
                                    No hay nota 
                                @endif
                            </td>

                            <td class="text-center {{ !empty($item->arte) ? $item->arte < 6 ? 'bg-[#e969697c]' : 'bg-[#3cc2437a]' : ''}}">
                                @if($item->arte)
                                    <a href="{{route("student.qualification.alone", ['subject'=>'arte'])}}">
                                        {{ $item->arte }}
                                    </a> 
                                @else 
                                    No hay nota 
                                @endif
                            </td>

                            <td class="text-center {{ !empty($item->historia) ? $item->historia < 6 ? 'bg-[#e969697c]' : 'bg-[#3cc2437a]' : ''}}">
                                @if($item->historia)
                                    <a href="{{route("student.qualification.alone", ['subject'=>'historia'])}}">
                                        {{ $item->historia }} 
                                    </a>
                                @else 
                                    No hay nota 
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </table>
                    <div style="margin-top: 15px">
                        {{$students->links()}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
