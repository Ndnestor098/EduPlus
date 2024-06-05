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
                            <td class="text-center w-6" @if($subjects->matematicas < 6) style="background-color: #e969697c; font-weight: 600;" 
                                                        @else style="background-color: #3cc2437a; font-weight: 600;" 
                                                        @endif>
                                                        @if($subjects->matematicas)
                                                            <a href="{{route("student.qualification.alone", ['subject'=>'matematicas'])}}">
                                                                {{ $subjects->matematicas }} 
                                                            </a>
                                                        @else 
                                                            No hay nota 
                                                        @endif
                                                    
                            </td>

                            <td class="text-center" @if($subjects->ingles < 6) style="background-color: #e969697c; font-weight: 600;"
                                                        @else style="background-color: #3cc2437a; font-weight: 600;" 
                                                        @endif>
                                                        @if($subjects->ingles)
                                                            <a href="{{route("student.qualification.alone", ['subject'=>'ingles'])}}">
                                                                {{ $subjects->ingles }}
                                                            </a> 
                                                        @else 
                                                            No hay nota 
                                                        @endif
                                                    
                            </td>

                            <td class="text-center" @if($subjects->fisica < 6) style="background-color: #e969697c; font-weight: 600;"
                                                        @else style="background-color: #3cc2437a; font-weight: 600;" 
                                                        @endif>
                                                        @if($subjects->fisica)
                                                            <a href="{{route("student.qualification.alone", ['subject'=>'fisica'])}}">
                                                                {{ $subjects->fisica }} 
                                                            </a>
                                                        @else 
                                                            No hay nota 
                                                        @endif
                                                    
                            </td>

                            <td class="text-center" @if($subjects->ciencia < 6) style="background-color: #e969697c; font-weight: 600;"
                                                        @else style="background-color: #3cc2437a; font-weight: 600;" 
                                                        @endif>
                                                        @if($subjects->ciencia)
                                                            <a href="{{route("student.qualification.alone", ['subject'=>'ciencia'])}}">
                                                                {{ $subjects->ciencia }} 
                                                            </a>
                                                        @else 
                                                            No hay nota 
                                                        @endif
                                                    
                            </td>

                            <td class="text-center" @if($subjects->computacion < 6) style="background-color: #e969697c; font-weight: 600;"
                                                        @else style="background-color: #3cc2437a; font-weight: 600;" 
                                                        @endif>
                                                        @if($subjects->computacion)
                                                            <a href="{{route("student.qualification.alone", ['subject'=>'computacion'])}}">
                                                                {{ $subjects->computacion }}
                                                            </a> 
                                                        @else 
                                                            No hay nota 
                                                        @endif
                                                    
                            </td>

                            <td class="text-center" @if($subjects->literatura < 6) style="background-color: #e969697c; font-weight: 600;"
                                                        @else style="background-color: #3cc2437a; font-weight: 600;" 
                                                        @endif>
                                                        @if($subjects->literatura)
                                                            <a href="{{route("student.qualification.alone", ['subject'=>'literatura'])}}">
                                                                {{ $subjects->literatura }} 
                                                            </a>
                                                        @else 
                                                            No hay nota 
                                                        @endif
                                                    
                            </td>

                            <td class="text-center" @if($subjects->arte < 6) style="background-color: #e969697c; font-weight: 600;"
                                                        @else style="background-color: #3cc2437a; font-weight: 600;" 
                                                        @endif>
                                                        @if($subjects->arte)
                                                            <a href="{{route("student.qualification.alone", ['subject'=>'arte'])}}">
                                                                {{ $subjects->arte }}
                                                            </a> 
                                                        @else 
                                                            No hay nota 
                                                        @endif
                                                    
                            </td>

                            <td class="text-center" @if($subjects->historia < 6) style="background-color: #e969697c; font-weight: 600;"
                                                        @else style="background-color: #3cc2437a; font-weight: 600;" 
                                                        @endif>
                                                        @if($subjects->historia)<a href="{{route("student.qualification.alone", ['subject'=>'historia'])}}">
                                                            {{ $subjects->historia }} 
                                                        </a>

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
