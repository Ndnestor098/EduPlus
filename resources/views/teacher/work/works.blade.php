<x-app-layout>
    <x-slot name="title">
        Actividades
    </x-slot>
    
    <x-slot name='navigation'>@include('layouts.navigation')</x-slot>

    <x-slot name="header">
        <a href="{{route('teacher.works')}}" class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Actividades') }}
        </a>
    </x-slot>
    
    

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                @if($methodExist)
                    <div class="p-6 text-gray-900 container mx-auto">
                        <div> 
                            <form action="" method="GET" class="flex flex-wrap md:flex-nowrap justify-between mb-10 items-center gap-5"  enctype="application/x-www-form-urlencoded" novalidate autocomplete="off">
                                @csrf
                                <div class="ml-5 w-full">
                                    <select name="course" id="course">
                                        <option value="" disabled selected>Selecciona un Curso:</option>
                                        @foreach ($course as $item)
                                            <option value="{{$item->course}}">Curso {{$item->course}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="flex items-center justify-center w-full md:justify-end">
                                    <button class="button-search mr-5">Buscar</button>
                                </div>
                            </form>
                        </div>
                        <table class="table">
                            <tr>
                                <th>Titulo</th>
                                <th>M. Calificativo</th>
                                <th>Materia</th>
                                <th>Año C.</th>
                                <th>Publicado</th>
                                <th>F. Entrega</th>
                                <th>Corregir</th>
                            </tr>
                            @foreach($works as $item)
                                <tr>
                                    <td>
                                        <a href="{{route("teacher.work.edit", ['name'=>$item->slug, 'id'=>$item->id])}}">{{ $item->title }}</a>
                                    </td>
                                    
                                    <td>
                                        <a href="{{route("teacher.work.edit", ['name'=>$item->slug, 'id'=>$item->id])}}">{{ $item->workType->name }}</a>
                                    </td>
                                    
                                    <td class="text-center">
                                        <a href="{{route("teacher.work.edit", ['name'=>$item->slug, 'id'=>$item->id])}}">{{ ucfirst($item->subject) }}</a>
                                    </td>

                                    <td class="text-center">
                                        <a href="{{route("teacher.work.edit", ['name'=>$item->slug, 'id'=>$item->id])}}">{{ $item->course }}</a>
                                    </td>

                                    <td class="text-center">
                                        <a href="{{route("teacher.work.edit", ['name'=>$item->slug, 'id'=>$item->id])}}">@if($item->public){{'Publicado'}}@else{{'No publicado'}}@endif</a>
                                    </td>

                                    <td class="text-center">
                                        <a href="{{route("teacher.work.edit", ['name'=>$item->slug, 'id'=>$item->id])}}">{{ $item->deliver }}</a>
                                    </td>
                                    
                                    <td>
                                        <a href="{{route("teacher.work.edit", ['name'=>$item->slug, 'id'=>$item->id])}}">
                                            <img class="m-auto" src="/assets/img/evaluacion.png" alt="Tareas a revisar: Pendiente" width="30px">
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                    <div class="flex justify-center" style="width: 100%; height: 100%; padding: 10px 0px;">
                        <a href="{{route("teacher.work.add")}}" class="button-search">Agregar Tarea</a>
                    </div>
                @else
                    <div class="flex justify-center items-center h-12">
                        <p class="font-semibold text-rojo">Metodo Calificativo de Tareas no asignado.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
