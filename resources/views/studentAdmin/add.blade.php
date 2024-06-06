<x-app-layout>
    <x-slot name="title">
        Alumnos Agregar
    </x-slot>

    <x-slot name='navigation'>@include('layouts.navigation')</x-slot>


    <x-slot name="header">
        <a href="{{route("student.admin")}}" class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Alumnos') }}
        </a>
    </x-slot>
    
    

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 container mx-auto">
                    <div class="flex gap-5 mb-8">
                        <a href="{{route('student.admin')}}"><img src="/assets/img/back.png" alt="back" style="height: 25px"></a><span class="text-lg font-semibold">Crear alumno/a</span>
                    </div>

                    <div class="flex items-center justify-center flex-col">
                        <form action="" method="POST" class="form" enctype="application/x-www-form-urlencoded" novalidate autocomplete="on">
                            @csrf
                            @method("put")

                            <div>
                                <label for="name">Nombre</label>
                                <input type="text" name="name" id="name" placeholder="Nombre Y Apellido *" required autofocus>
                            </div>
                            <div>
                                <label for="email">Email</label>
                                <input type="email" name="email" id="email" placeholder="Email *" required autofocus>
                            </div>
                            <div>
                                <label for="cellphone">Telefono</label>
                                <input type="number" name="cellphone" id="cellphone" placeholder="Telefono *" required autofocus>
                            </div>
                            <div>
                                <label for="course">Año</label>
                                <input type="number" name="course" id="course" placeholder="Año cursando *" required autofocus>
                            </div>
                            <div>
                                <label for="password">Password</label>
                                <input type="password" name="password" id="password" placeholder="Password *" required autofocus>
                            </div>
                            <div>
                                <label for="password_confirmation">Confirmar Password</label>
                                <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Confirmar Password *" autofocus>
                            </div>
                            <p class="error text-center font-semibold" style="color: rgb(161, 44, 44)">
                                @if (gettype($errors) != gettype((object)array('1'=>1)))
                                    {{ $errors }}
                                @endif
                            </p>
                            <div>
                                <button type="submit" class="button-update">Agregar Alumno</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
