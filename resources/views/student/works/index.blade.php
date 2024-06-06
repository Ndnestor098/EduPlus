<x-app-layout>
    <x-slot name="title">
        Actividades
    </x-slot>
    
    <x-slot name='navigation'>@include('layouts.navigation')</x-slot>

    <x-slot name="header">
        <a href="{{route("student.works")}}" class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Actividades') }}
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
                                <select name="subject" id="subject">
                                    <option value="" disabled selected>Selecciona una Materia:</option>
                                    @foreach ($subjects as $item)
                                        <option value="{{$item->subject}}">Materia: {{$item->subject}}</option>
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
                            <th>Materia</th>
                            <th>F. Entrega</th>
                        </tr>
                        @foreach($works as $item)
                            <tr>
                                <td><a href="{{route("student.work.show", ['name'=>$item->slug])}}">{{ Str::limit($item->title, 20) }}</a></td>
                                <td><a href="{{route("student.work.show", ['name'=>$item->slug])}}">{{ Str::limit($item->description, 20) }}</a></td>
                                <td class="text-center"><a href="{{route("student.work.show", ['name'=>$item->slug])}}">{{ ucfirst($item->subject) }}</a></td>
                                <td class="text-center"><a href="{{route("student.work.show", ['name'=>$item->slug])}}">{{ $item->deliver }}</a></td>
                                
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
