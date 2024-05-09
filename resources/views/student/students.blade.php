<x-app-layout>
    <x-slot name="title">
        Alumnos
    </x-slot>

    <x-slot name="header">
        <a href="{{route("alumnos")}}" class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Alumnos') }}
        </a>
    </x-slot>
    
    

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 container mx-auto">
                    <div>
                        <form action="" method="GET" class="flex justify-between mb-10"  enctype="application/x-www-form-urlencoded" novalidate autocomplete="off">
                            @csrf
                            <div class="ml-5">
                                <label for="orden">Ordenar por:</label>
                                <select name="orden" id="orden">
                                    <option value="name/ASC">Nombre | ASC</option>
                                    <option value="name/DESC">Nombre | DESC</option>
                                    <option value="email/ASC">Email | ASC</option>
                                    <option value="email/DESC">Email | DESC</option>
                                    <option value="course/ASC">Año | ASC</option>
                                    <option value="course/DESC">Año | DESC</option>
                                </select>
                            </div>
                            <div>
                                <button class="button-search mr-5">Buscar</button>
                            </div>
                        </form>
                    </div>
                    <table class="table">
                        <tr>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Año C.</th>
                            <th>Notas</th>
                        </tr>
                        @foreach ($students as $item)
                            <tr>
                                <td><a href="{{route("alumno.edit", ['name'=>$item->name, 'id'=>$item->id])}}">{{ $item->name }}</a></td>
                                <td><a href="{{route("alumno.edit", ['name'=>$item->name, 'id'=>$item->id])}}">{{ Str::limit($item->email,20) }}</a></td>
                                <td class="text-center"><a href="{{route("alumno.edit", ['name'=>$item->name, 'id'=>$item->id])}}">{{ $item->course }}</a></td>
                                <td style="background-color: #fafafa; font-weight: 600;" ><a href="{{route('alumnos.notas', ["id"=>$item->id])}}">Notas</a></td>
                            </tr>
                        @endforeach
                    </table>
                    {{$students->links()}}

                </div>
                
                <div class="flex justify-center" style="width: 100%; height: 100%; padding: 10px 0px;">
                    <a href="{{route("alumno.add")}}" class="button-search">Agregar Alumno</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
