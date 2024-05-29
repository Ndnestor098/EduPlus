<x-app-layout>
    <x-slot name="title">
        Profesores Editar
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
                    <div class="flex gap-5 mb-8">
                        <a href="{{route("teacher.admin")}}"><img src="/assets/img/back.png" alt="back" style="height: 25px"></a><span class="text-lg font-semibold">Profesor/a {{$user->name}}</span>
                    </div>

                    <div class="flex items-center justify-center flex-col">
                        <form action="" method="POST" class="form" enctype="application/x-www-form-urlencoded" novalidate autocomplete="off">
                            @csrf
                            <input type="hidden" name="id" value="{{$user->id}}">
                            <div>
                                <label for="name">Nombre</label>
                                <input type="text" name="name" id="name" required value="{{$user->name}}">
                            </div>
                            <div>
                                <label for="email">Email</label>
                                <input type="email" name="email" id="email" required value="{{$user->email}}">
                            </div>
                            <div>
                                <label for="cellphone">Telefono</label>
                                <input type="text" name="cellphone" id="cellphone" placeholder="Telefono *" value="{{ $user->cellphone }}" required >
                            </div>
                            <div>
                                <label for="subject">Asignatura</label>
                                <select name="subject" id="subject">
                                    @if ( $user->subjects )
                                        <option value="{{$user->subjects}}">{{ucfirst($user->subjects)}}</option>                                                                                                                  
                                    @endif
                                    <option value="literatera">Literatera</option>
                                    <option value="ingles">Ingles</option>
                                    <option value="historia">Historia</option>
                                    <option value="fisica">Fisica</option>
                                    <option value="computacion">Computacion</option>
                                    <option value="arte">Arte</option>
                                    <option value="ciencia">Ciencia</option>
                                    <option value="matematicas">Matematicas</option>
                                </select>
                            </div>
                            <div>
                                <label for="salary">Salario</label>
                                <input type="number" name="salary" id="salary" required value="{{$user->salary}}">
                            </div>
                            <div>
                                <label for="started">Fecha de Inicio</label>
                                <input type="date" name="started" id="started" required value="{{$user->started}}">
                            </div>
                            <p class="error text-center font-semibold" style="color: rgb(161, 44, 44)">
                                @if (gettype($errors) != gettype((object)array('1'=>1)))
                                    {{ $errors }}
                                @endif
                            </p>
                            <div>
                                <button type="submit" class="button-update">Actualizar</button>
                            </div>
                        </form>
                        <form action="" method="POST" class="form mt-5" enctype="application/x-www-form-urlencoded" novalidate autocomplete="on">
                            @csrf
                            @method("delete")
                            <input type="hidden" name="id"  value="{{auth()->user()->id}}">
                            <input type="hidden" name="email"  value="{{$user->email}}">
                            <input type="hidden" name="id_teacher" value="{{$user->id}}">
                            <button type="submit" class="button-delete">Eleminar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
