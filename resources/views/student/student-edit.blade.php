<x-app-layout>
    <x-slot name="title">
        Alumnos Edit
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
                    <div class="flex gap-5 mb-8">
                        <a href="{{route("alumnos")}}"><img src="/assets/img/back.png" alt="back" style="height: 25px"></a><span class="text-lg font-semibold">Profesor/a {{$user->name}}</span>
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
                                <label for="course">Año</label>
                                <input type="number" name="course" id="course" required value="{{$user->course}}">
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
                        <form action="" method="POST" class="form mt-5">
                            @csrf
                            @method("delete")
                            <input type="hidden" name="id"  value="{{$user->id}}">
                            <button type="submit" class="button-delete">Eleminar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
