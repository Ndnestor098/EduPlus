<x-app-layout>
    <x-slot name="title">
        Notas
    </x-slot>

    <x-slot name='navigation'>@include('layouts.navigation')</x-slot>

    <x-slot name="header">
        <a href="{{route('student.admin')}}" class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Alumnos') }}
        </a>
    </x-slot>
    
    

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 container mx-auto">
                    <div class="flex gap-5 mb-8">
                        <a href="{{route("student.admin.edit", ['name'=>$student->name,'id'=>$student->id])}}"><img src="/assets/img/back.png" alt="back" style="height: 25px"></a><span class="text-lg font-semibold">Alumno/a: {{$student->name}}</span>
                    </div>
                    <table class="table">
                        <tr>
                            <th style="background-color: rgb(238, 238, 238)">Matematicas</th>
                            <th style="background-color: rgb(238, 238, 238)">Ingles</th>
                            <th style="background-color: rgb(238, 238, 238)">Fisica</th>
                            <th style="background-color: rgb(238, 238, 238)">Ciencias</th>
                            <th style="background-color: rgb(238, 238, 238)">Computacion</th>
                            <th style="background-color: rgb(238, 238, 238)">Literatura</th>
                            <th style="background-color: rgb(238, 238, 238)">Arte</th>
                            <th style="background-color: rgb(238, 238, 238)">Historia</th>
                        </tr>
                        <tr>
                            <td class="text-center w-6" @if($subjects->matematicas < 5) style="background-color: #e969697c; font-weight: 600;" 
                                                        @else style="background-color: #3cc2437a; font-weight: 600;" 
                                                        @endif>
                                                        @if($subjects->matematicas)
                                                            {{ $subjects->matematicas }} 
                                                        @else 
                                                            No hay nota 
                                                        @endif
                                                    
                            </td>

                            <td class="text-center" @if($subjects->ingles < 5) style="background-color: #e969697c; font-weight: 600;"
                                                        @else style="background-color: #3cc2437a; font-weight: 600;" 
                                                        @endif>
                                                        @if($subjects->ingles)
                                                            {{ $subjects->ingles }} 
                                                        @else 
                                                            No hay nota 
                                                        @endif
                                                    
                            </td>

                            <td class="text-center" @if($subjects->fisica < 5) style="background-color: #e969697c; font-weight: 600;"
                                                        @else style="background-color: #3cc2437a; font-weight: 600;" 
                                                        @endif>
                                                        @if($subjects->fisica)
                                                            {{ $subjects->fisica }} 
                                                        @else 
                                                            No hay nota 
                                                        @endif
                                                    
                            </td>

                            <td class="text-center" @if($subjects->ciencia < 5) style="background-color: #e969697c; font-weight: 600;"
                                                        @else style="background-color: #3cc2437a; font-weight: 600;" 
                                                        @endif>
                                                        @if($subjects->ciencia)
                                                            {{ $subjects->ciencia }} 
                                                        @else 
                                                            No hay nota 
                                                        @endif
                                                    
                            </td>

                            <td class="text-center" @if($subjects->computacion < 5) style="background-color: #e969697c; font-weight: 600;"
                                                        @else style="background-color: #3cc2437a; font-weight: 600;" 
                                                        @endif>
                                                        @if($subjects->computacion)
                                                            {{ $subjects->computacion }} 
                                                        @else 
                                                            No hay nota 
                                                        @endif
                                                    
                            </td>

                            <td class="text-center" @if($subjects->literatura < 5) style="background-color: #e969697c; font-weight: 600;"
                                                        @else style="background-color: #3cc2437a; font-weight: 600;" 
                                                        @endif>
                                                        @if($subjects->literatura)
                                                            {{ $subjects->literatura }} 
                                                        @else 
                                                            No hay nota 
                                                        @endif
                                                    
                            </td>

                            <td class="text-center" @if($subjects->arte < 5) style="background-color: #e969697c; font-weight: 600;"
                                                        @else style="background-color: #3cc2437a; font-weight: 600;" 
                                                        @endif>
                                                        @if($subjects->arte)
                                                            {{ $subjects->arte }} 
                                                        @else 
                                                            No hay nota 
                                                        @endif
                                                    
                            </td>

                            <td class="text-center" @if($subjects->historia < 5) style="background-color: #e969697c; font-weight: 600;"
                                                        @else style="background-color: #3cc2437a; font-weight: 600;" 
                                                        @endif>
                                                        @if($subjects->historia){{ $subjects->historia }} 

                                                        @else 
                                                            No hay nota 
                                                        @endif
                                                    
                            </td>

                        </tr>
                    </table>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
