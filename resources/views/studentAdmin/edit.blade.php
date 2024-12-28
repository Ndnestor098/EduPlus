<x-app-layout>
    <x-slot name="title">
        Alumnos Editar
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
                    <div class="flex gap-5 mb-8">
                        <a href="{{route("student.admin")}}"><img src="/assets/img/back.png" alt="back" style="height: 25px"></a><span class="text-lg font-semibold">Alumno/a {{$user->name}}</span>
                    </div>

                    <div class="flex items-center justify-center flex-col">
                        <form action="" method="POST" class="form" enctype="application/x-www-form-urlencoded" novalidate autocomplete="off">
                            @csrf
                            <input type="hidden" name="id" value="{{$user->id}}">

                            <div>
                                <label for="name">Nombre</label>
                                <input type="text" name="name" id="name" required value="{{$user->name}}">
                                @error('name')
                                    <p class="text-center font-semibold text-rojo">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="email">Email</label>
                                <input type="email" name="email" id="email" required value="{{$user->email}}">
                                @error('email')
                                    <p class="text-center font-semibold text-rojo">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="cellphone">Telefono</label>
                                <input type="text" name="cellphone" id="cellphone" placeholder="Telefono *" value="{{$user->cellphone}}" required>
                                @error('cellphone')
                                    <p class="text-center font-semibold text-rojo">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="course">Año</label>
                                <input type="number" name="course" id="course" required value="{{$user->course}}">
                                @error('course')
                                    <p class="text-center font-semibold text-rojo">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="password">Password <span>no obligatorio</span></label>
                                <input type="password" name="password" id="password" placeholder="Password">
                                @error('password')
                                    <p class="text-center font-semibold text-rojo">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="password_confirmation">Confirmar Password <span>no obligatorio</span></label>
                                <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Confirmar Password">
                                @error('password_comfirmation')
                                    <p class="text-center font-semibold text-rojo">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <button type="submit" class="button-update">Actualizar</button>
                            </div>
                        </form>

                        <form action="" method="POST" class="form mt-5" enctype="application/x-www-form-urlencoded" novalidate autocomplete="on">
                            <a href="{{route('student.admin.notas', ["id" => $user->id])}}" class="button-update text-center">Ver Notas del Estudiante</a>
                        </form>

                        <form action="" method="POST" class="form mt-5" enctype="application/x-www-form-urlencoded" novalidate autocomplete="on">
                            @csrf
                            @method("delete")
                            <input type="hidden" name="email" value="{{$user->email}}">
                            <input type="hidden" name="id_student" value="{{$user->id}}">
                            <button type="submit" class="button-delete">Eleminar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
