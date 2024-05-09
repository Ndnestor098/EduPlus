<x-app-layout>
    <x-slot name="title">
        Profesores Agregar
    </x-slot>

    <x-slot name="header">
        <a href="{{route("profesores")}}" class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profesores') }}
        </a>
    </x-slot>
    
    

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 container mx-auto">
                    <div class="flex gap-5 mb-8">
                        <a href="{{route("profesores")}}"><img src="/assets/img/back.png" alt="back" style="height: 25px"></a><span class="text-lg font-semibold">Crea un nuevo profesor</span>
                    </div>

                    <div class="flex items-center justify-center flex-col">
                        <form action="" method="POST" class="form" enctype="application/x-www-form-urlencoded" novalidate autocomplete="off">
                            @csrf
                            @method("put")
                            <div>
                                <label for="name">Nombre</label>
                                <input type="text" name="name" id="name" placeholder="Nombre y Apellido *" required>
                            </div>
                            <div>
                                <label for="email">Email</label>
                                <input type="email" name="email" id="email" placeholder="Email *" required>
                            </div>
                            <div>
                                <label for="subject">Asignatura</label>
                                <select name="subject" id="subject">
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
                                <input type="number" name="salary" id="salary" placeholder="Salario *" required >
                            </div>
                            <div>
                                <label for="started">Fecha de Inicio</label>
                                <input type="date" name="started" id="started" required>
                            </div>
                            <div>
                                <label for="password">Password</label>
                                <input type="password" name="password" id="password" placeholder="Password *" required>
                            </div>
                            <div>
                                <label for="password_confirmation">Confirmar Password</label>
                                <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Confirmar Password *" required>
                            </div>
                            <p class="error text-center font-semibold" style="color: rgb(161, 44, 44)">
                                @if (gettype($errors) != gettype((object)array('1'=>1)))
                                    {{ $errors }}
                                @endif
                            </p>
                            
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
