<x-app-layout>
    <x-slot name="title">
        Administradores
    </x-slot>
    
    <x-slot name='navigation'>@include('layouts.navigation')</x-slot>

    <x-slot name="header">
        <a href="{{route("administrador")}}" class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Administradores') }}
        </a>
    </x-slot>
    
    

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 container mx-auto">
                    <div>
                        <form action="" method="GET" class="flex flex-wrap md:flex-nowrap justify-between mb-10 items-center gap-5"  enctype="application/x-www-form-urlencoded" novalidate autocomplete="off">
                            @csrf
                            <div class="ml-5">
                                <select name="orden" id="orden">
                                    <option value="" disabled selected>Ordenar por:</option>
                                    
                                    <option value="name/ASC">Nombre | ASC</option>
                                    <option value="name/DESC">Nombre | DESC</option>
                                    <option value="email/ASC">Email | ASC</option>
                                    <option value="email/DESC">Email | DESC</option>
                                    <option value="cellphone/ASC">Telefono | ASC</option>
                                    <option value="cellphone/DESC">Telefono | DESC</option>
                                    <option value="salary/ASC">Salario | ASC</option>
                                    <option value="salary/DESC">Salario | DESC</option>
                                    <option value="started/ASC">F. Inico | ASC</option>
                                    <option value="started/DESC">F. Inico | DESC</option>
                                </select>
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
                            <th>Salary</th>
                            <th>F. Inicio</th>
                        </tr>
                        @foreach ($admin as $item)
                            <tr>
                                <td><a href="{{route("administrador.edit", ['name'=>$item->name, 'id'=>$item->id])}}">{{ $item->name }}</a></td>
                                <td><a href="{{route("administrador.edit", ['name'=>$item->name, 'id'=>$item->id])}}">{{ Str::limit($item->email,20) }}</a></td>
                                <td><a href="{{route("administrador.edit", ['name'=>$item->name, 'id'=>$item->id])}}">{{ $item->cellphone }}</a></td>
                                <td class="text-center"><a href="{{route("administrador.edit", ['name'=>$item->name, 'id'=>$item->id])}}">{{ $item->salary }}</a></td>
                                <td class="text-center"><a href="{{route("administrador.edit", ['name'=>$item->name, 'id'=>$item->id])}}">{{ $item->started }}</a></td>
                                
                            </tr>
                        @endforeach
                    </table>
                </div>
                <div class="flex justify-center" style="width: 100%; height: 100%; padding: 10px 0px;">
                    <a href="{{route("administrador.add")}}" class="button-search">Agregar Profesor</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
