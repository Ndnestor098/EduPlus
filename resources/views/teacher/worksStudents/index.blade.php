<x-app-layout>
    <x-slot name="title">
        Corregir: {{ $studentWorks->title }}
    </x-slot>
    
    <x-slot name='navigation'>@include('layouts.navigation')</x-slot>

    <x-slot name="header">
        <a href="{{route("teacher.works.students", ['nameWork' => $studentWorks->slug])}}" class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Corregir Tarea: ') . $studentWorks->title }}
        </a>
    </x-slot>
    
    

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 container mx-auto">
                    <div> 
                        <form action="" method="GET" class="flex flex-wrap md:flex-nowrap justify-between mb-10 items-center gap-5"  enctype="application/x-www-form-urlencoded" novalidate autocomplete="off">
                            @csrf
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
                            <th>Estudiante</th>
                            <th>Año C.</th>
                            <th>F. Entrega</th>
                            <th>Corregido</th>
                        </tr>
                        @foreach ($studentWorks->students as $item)
                            <tr>
                                <td><a href="{{ route("teacher.correct", ['nameStudent'=>$item->slug]) }}">{{ $item->name }}</a></td>
                                <td class="text-center"><a href="{{ route("teacher.correct", ['nameStudent' => $item->slug]) }}">{{ $item->course }}</a></td>
                                <td class="text-center"><a href="{{ route("teacher.correct", ['nameStudent' => $item->slug]) }}">{{ $item->created_at }}</a></td>
                                
                                @if (!$item->qualification)
                                    <td>
                                        <a href="{{ route("teacher.correct", ['nameStudent' => $item->slug]) }}">
                                            <img class="m-auto" src="/assets/img/bad.png" alt="Tareas a revisar: Pendiente" width="30px">
                                        </a>
                                    </td>
                                @else
                                    <td>
                                        <a href="{{ route("teacher.correct", ['nameStudent' => $item->slug]) }}">
                                            <img class="m-auto" src="/assets/img/check.png" alt="Tareas a revisadas: OK" width="30px">
                                        </a>
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
