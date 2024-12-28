<x-app-layout>
    <x-slot name="title">
        Administrador Agregar
    </x-slot>

    <x-slot name='navigation'>@include('layouts.navigation')</x-slot>

    <x-slot name="header">
        <a href='{{route("administrador")}}' class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Administrador') }}
        </a>
    </x-slot>
    
    

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 container mx-auto">
                    <div class="flex gap-5 mb-8">
                        <a href='{{route("administrador")}}'><img src="/assets/img/back.png" alt="back" style="height: 25px"></a><span class="text-lg font-semibold">Crea un nuevo administrador</span>
                    </div>

                    <div class="flex items-center justify-center flex-col">
                        <form action="" method="POST" class="form" enctype="application/x-www-form-urlencoded" novalidate autocomplete="on">
                            @csrf
                            @method("put")
                            
                            <div>
                                <label for="name">Nombre</label>
                                <input type="text" name="name" id="name" placeholder="Nombre y Apellido *" required autofocus>
                                @error('name')
                                    <p class="text-center font-semibold text-rojo">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="email">Email</label>
                                <input type="email" name="email" id="email" placeholder="Email *" required autofocus>
                                @error('email')
                                    <p class="text-center font-semibold text-rojo">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="cellphone">Telefono</label>
                                <input type="number" name="cellphone" id="cellphone" placeholder="Telefono *" required autofocus>
                                @error('cellphone')
                                    <p class="text-center font-semibold text-rojo">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="salary">Salario</label>
                                <input type="number" name="salary" id="salary" placeholder="Salario *" required autofocus >
                                @error('salary')
                                    <p class="text-center font-semibold text-rojo">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="started">Fecha de Inicio</label>
                                <input type="date" name="started" id="started" required autofocus>
                                @error('started')
                                    <p class="text-center font-semibold text-rojo">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="password">Password</label>
                                <input type="password" name="password" id="password" placeholder="Password *" required autofocus>
                                @error('password')
                                    <p class="text-center font-semibold text-rojo">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="password_confirmation">Confirmar Password</label>
                                <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Confirmar Password *" required autofocus>
                                @error('password_confirmation')
                                    <p class="text-center font-semibold text-rojo">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <button type="submit" class="button-update">Agregar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
