<x-app-layout>
    <x-slot name="title">
        Materia: {{$subject}}
    </x-slot>

    <x-slot name='navigation'>@include('layouts.navigation')</x-slot>

    <x-slot name="header">
        <a href="{{route('student.qualification')}}" class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Vizualizar Notas de ').$subject }}
        </a>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 container mx-auto">
                    <div class="flex gap-5 mb-8">
                        <a href="{{route("student.qualification")}}"><img src="/assets/img/back.png" alt="back" style="height: 25px"></a>
                        <span class="text-lg font-semibold">Materia: {{$subject}}</span>
                    </div>

                    <div class="flex items-center justify-center flex-col gap-5">
                        <div class="flex flex-col gap-5 w-full">
                            @foreach ($works as $work)
                                <div style="box-shadow: 0px 6px 15px -13px rgba(0,0,0,0.75);">
                                    @foreach ($work as $item)
                                        <p>Tarea: <span class="font-bold">{{$item->work->title}}</span></p>
                                        <p>Fecha Entregada: <span class="font-bold">{{$item->created_at->format("Y-m-d")}}</span></p>
                                        <p>Calificacion: <span class="font-bold 
                                            @if($item->qualification > 6) {{'text-verde'}}
                                            @else {{'text-rojo'}} @endif">
                                            {{$item->qualification}}
                                            </span>
                                        </p>
                                    @endforeach
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
