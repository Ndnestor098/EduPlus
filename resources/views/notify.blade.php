<x-app-layout>
    <x-slot name="title">
        Area de Notifiaciones
    </x-slot>
    
    <x-slot name='navigation'>@include('layouts.navigation')</x-slot>

    <x-slot name="header">
        <a href="{{route('notification')}}" class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Area de Notifiaciones') }}
        </a>
    </x-slot>

    

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                @if($notify)
                    <div class="p-6 text-gray-900 container mx-auto">
                        <div>
                            {{---------------------------------- Area de Estudiante  ----------------------------------}}
                            @foreach ($notify as $item)
                                @php
                                    $createdDate = \Carbon\Carbon::parse($item->data['work']['created_at'])->format('Y/m/d');
                                @endphp

                                @if ($item->notifiable_type == 'App\\Models\\Student')
                                    <div class="flex w-full justify-between p-2 items-center">
                                        <a class="flex w-full justify-between items-center" 
                                            href="/student/work/{{ $item->data['work']['slug'] }}?notificationId={{ $item->id }}">
                                            <div>
                                                <p><span class="font-bold">Nombre de Trabajo:</span> {{ $item->data['work']['title'] }}</p>
                                                <p><span class="font-bold">Materia:</span> {{ $item->data['work']['subject'] }}.</p>
                                                <p><span class="font-bold">Fecha de Creación:</span> {{ $createdDate }}</p>
                                                <p><span class="font-bold">Curso o Año:</span> {{ $item->data['work']['course'] }}</p>
                                            </div>
                                            <div>
                                                @if($item->read_at)
                                                    <span><i class="fa-solid fa-eye text-verde text-lg"></i></span>
                                                @else
                                                    <span><i class="fa-solid fa-eye-slash text-rojo text-lg"></i></span>
                                                @endif
                                            </div>
                                        </a>
                                    </div>
                                    <br>
                                @endif
                            @endforeach

                            {{---------------------------------- Area de Profesor  ----------------------------------}}
                            @foreach ($notify as $item)
                                @php
                                    $createdDate = \Carbon\Carbon::parse($item->data['work']['created_at'])->format('Y/m/d');
                                @endphp

                                @if ($item->notifiable_type == 'App\\Models\\Teacher')
                                    <div class="flex w-full justify-between p-2 items-center">
                                        <a class="flex w-full justify-between items-center" href="/teacher/correct/student/{{ $item->data['work']['slug'] }}?work_id={{ $item->data['work']['work_id'] }}&notificationId={{ $item->id}}">
                                            <div>
                                                <p><span class="font-bold">Nombre del Alumno:</span> {{ $item->data['work']['name'] }}</p>
                                                <p><span class="font-bold">Materia:</span> {{ $subject }}.</p>
                                                <p><span class="font-bold">Fecha de Creación:</span> {{ $createdDate }}</p>
                                                <p><span class="font-bold">Curso o Año:</span> {{ $item->data['work']['course'] }}</p>
                                            </div>
                                            <div>
                                                @if($item->read_at)
                                                    <span><i class="fa-solid fa-eye text-verde text-lg"></i></span>
                                                @else
                                                    <span><i class="fa-solid fa-eye-slash text-rojo text-lg"></i></span>
                                                @endif
                                            </div>
                                        </a>
                                    </div>
                                    <br>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="flex justify-center items-center h-12">
                        <p class="font-semibold text-rojo">No hay Notificaciones.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
