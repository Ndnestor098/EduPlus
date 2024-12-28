<x-app-layout>
    <x-slot name="title">
        Profesores Agregar
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
                        <a href="{{route("teacher.admin")}}"><img src="/assets/img/back.png" alt="back" style="height: 25px"></a><span class="text-lg font-semibold">Crea un nuevo profesor</span>
                    </div>

                    <div class="flex items-center justify-center flex-col">
                        <form action="" method="POST" class="form" enctype="application/x-www-form-urlencoded" novalidate autocomplete="on">
                            @csrf
                            @method("put")
                            
                            <div>
                                <label for="name">Nombre</label>
                                <input type="text" name="name" id="name" placeholder="Nombre y Apellido *" value="{{ old('email') }}" required autofocus>
                                @error('name')
                                    <p class="text-center font-semibold text-rojo">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="email">Email</label>
                                <input type="email" name="email" id="email" placeholder="Email *" value="{{ old('email') }}" required>
                                @error('email')
                                    <p class="text-center font-semibold text-rojo">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="cellphone">Telefono</label>
                                <input type="number" name="cellphone" id="cellphone" placeholder="Telefono *" value="{{ old('cellphone') }}" required>
                                @error('cellphone')
                                    <p class="text-center font-semibold text-rojo">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="subject">Asignatura</label>
                                <select name="subject" id="subject" value="{{ old('subject') }}" required>
                                    <option value="literatura">Literatura</option>
                                    <option value="ingles">Ingles</option>
                                    <option value="historia">Historia</option>
                                    <option value="fisica">Fisica</option>
                                    <option value="computacion">Computacion</option>
                                    <option value="arte">Arte</option>
                                    <option value="ciencia">Ciencia</option>
                                    <option value="matematicas">Matematicas</option>
                                    <option value="edu_fisica">Educacion Fisica</option>
                                </select>
                                @error('subject')
                                    <p class="text-center font-semibold text-rojo">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="salary">Salario</label>
                                <input type="number" name="salary" id="salary" placeholder="Salario *" required >
                                @error('salary')
                                    <p class="text-center font-semibold text-rojo">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="started">Fecha de Inicio</label>
                                <input type="date" name="started" id="started" value="{{ old('started') }}" required>
                                @error('started')
                                    <p class="text-center font-semibold text-rojo">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="password">Password</label>
                                <input type="password" name="password" id="password" placeholder="Password *" value="{{ old('password') }}" required>
                                @error('password')
                                    <p class="text-center font-semibold text-rojo">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="password_confirmation">Confirmar Password</label>
                                <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Confirmar Password *" required>
                                @error('password_confirmation')
                                    <p class="text-center font-semibold text-rojo">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <button type="submit" class="button-update">Agregar Profesor</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
