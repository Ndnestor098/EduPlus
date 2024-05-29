<x-app-layout>
    <x-slot name="title">
        Profesores
    </x-slot>
    
    <x-slot name='navigation'>@include('layouts.navigation')</x-slot>

    <x-slot name="header">
        <a href="{{route("teacher.admin")}}" class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profesores') }}
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
                            <th>Descripcion</th>
                            <th>Documento</th>
                            <th>Imagenes</th>
                            <th>Materia</th>
                            <th>Año C.</th>
                            <th>F. Entrega</th>
                        </tr>
                        @foreach($work as $item)
                            <tr>
                                <td><a href="{{route("teacher.work.edit", ['name'=>$item->title, 'id'=>$item->id])}}">{{ $item->title }}</a></td>
                                <td><a href="{{route("teacher.work.edit", ['name'=>$item->title, 'id'=>$item->id])}}">{{ Str::limit($item->description, 20) }}</a></td>
                                <td class="text-center"><a href="{{route("teacher.work.edit", ['name'=>$item->title, 'id'=>$item->id])}}">@if($item->pdf){{'Exist'}}@else{{'No exist'}}@endif</a></td>
                                <td class="text-center"><a href="{{route("teacher.work.edit", ['name'=>$item->title, 'id'=>$item->id])}}">@if($item->img){{'Exist'}}@else{{'No exist'}}@endif</a></td>
                                <td class="text-center"><a href="{{route("teacher.work.edit", ['name'=>$item->title, 'id'=>$item->id])}}">{{ ucfirst($item->subject) }}</a></td>
                                <td class="text-center"><a href="{{route("teacher.work.edit", ['name'=>$item->title, 'id'=>$item->id])}}">{{ $item->course }}</a></td>
                                <td class="text-center"><a href="{{route("teacher.work.edit", ['name'=>$item->title, 'id'=>$item->id])}}">{{ $item->deliver }}</a></td>
                                
                            </tr>
                        @endforeach
                    </table>
                </div>
                <div class="flex justify-center" style="width: 100%; height: 100%; padding: 10px 0px;">
                    <a href="{{route("teacher.work.add")}}" class="button-search">Agregar Tarea</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
