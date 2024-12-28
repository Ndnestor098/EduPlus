<x-app-layout>
    <x-slot name="title">
        Alumnos
    </x-slot>
    
    <x-slot name='navigation'>@include('layouts.navigation')</x-slot>

    <x-slot name="header">
        <a href="{{route("student.admin")}}" class="font-semibold text-xl text-gray-800 leading-tight p-3">
            {{ __('Alumnos') }}
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
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Telefono</th>
                            <th>Año C.</th>
                            <th class="hidden md:table-cell">Notas</th>
                        </tr>
                        @foreach ($students as $item)
                            <tr>
                                <td>
                                    <a href="{{route("student.admin.edit", ['name'=>$item->name, 'id'=>$item->id])}}">{{ $item->name }}</a>
                                </td>
                                <td>
                                    <a href="{{route("student.admin.edit", ['name'=>$item->name, 'id'=>$item->id])}}">{{ Str::limit($item->email,20) }}</a>
                                </td>
                                <td>
                                    <a href="{{route("student.admin.edit", ['name'=>$item->name, 'id'=>$item->id])}}">{{ $item->cellphone }}</a>
                                </td>
                                <td class="text-center">
                                    <a href="{{route("student.admin.edit", ['name'=>$item->name, 'id'=>$item->id])}}">{{ $item->course }}</a>
                                </td>
                                <td class="hidden md:table-cell" style="background-color: #fafafa; font-weight: 600;" >
                                    <a href="{{route('student.admin.notas', ["id"=>$item->id])}}">Notas</a>
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>
                
                <div class="flex justify-center" style="width: 100%; height: 100%; padding: 10px 0px;">
                    <a href="{{route("student.admin.add")}}" class="button-search">Agregar Alumno</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
