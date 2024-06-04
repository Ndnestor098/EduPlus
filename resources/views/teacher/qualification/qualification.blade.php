<x-app-layout>
    <x-slot name="title">
        Metodo de calificaciones
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
                            <div class="flex gap-4 items-center justify-center w-full">
                                <p class="text-xl flex gap-4 font-semibold" style="color: #515c5b;">Porcentaje total de Evalucacion: <span class="text-center">{{$valor}}%</span></p>
                            </div>
                            <div class="flex items-center justify-center w-full md:justify-end">
                                <button class="button-search mr-5">Buscar</button>
                            </div>
                        </form>
                    </div>
                    <table class="table">
                        <tr>
                            <th>M. Calificativo</th>
                            <th>Porcentaje</th>
                            <th>Materia</th>
                            <th>Año C.</th>
                        </tr>
                        @foreach ($all as $item)
                            <tr>
                                <td class="text-center"><a href="{{route("teacher.qualification.edit", ['search'=>$item->id])}}">{{ $item->workType->name }}</a></td>
                                <td class="text-center"><a href="{{route("teacher.qualification.edit", ['search'=>$item->id])}}">{{ $item->percentage }}%</a></td>
                                <td class="text-center"><a href="{{route("teacher.qualification.edit", ['search'=>$item->id])}}">{{ $item->teacher->subject }}</a></td>
                                <td class="text-center"><a href="{{route("teacher.qualification.edit", ['search'=>$item->id])}}">{{ $item->course }}</a></td>
                            </tr>
                        @endforeach
                    </table>
                </div>
                <div class="flex justify-center" style="width: 100%; height: 100%; padding: 10px 0px;">
                    <a href="{{route("teacher.qualification.add")}}" class="button-search">Agregar Metodo Calificativo</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
