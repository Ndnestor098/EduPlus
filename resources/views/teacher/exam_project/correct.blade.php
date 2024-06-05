<x-app-layout>
    <x-slot name="title">
        Asignar Nota: {{ $work->title }}
    </x-slot>
    
    <x-slot name='navigation'>@include('layouts.navigation')</x-slot>

    <x-slot name="header">
        <a href="{{route("teacher.correct.exam", ['nameWork' => $work->slug])}}" class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Asignar Nota: ') . $work->title }}
        </a>
    </x-slot>
    
    

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 container mx-auto">
                    <div> 
                        <form action="" method="GET" class="flex flex-wrap md:flex-nowrap justify-between mb-10 items-center gap-5"  enctype="application/x-www-form-urlencoded" novalidate autocomplete="off">
                            @csrf
                            <div class="ml-5 w-full">
                                <input type="text" name="name" id="name" placeholder="Buscar Estudiante">
                            </div>
                            <div class="flex items-center justify-center w-full md:justify-end">
                                <button class="button-search mr-5">Buscar</button>
                            </div>
                        </form>
                    </div>

                    <form action="{{ route('teacher.exam.qualification') }}" method="POST">
                        @csrf
                        <input type="hidden" name="work" value="{{$work}}">
                        <table class="table">
                            <tr>
                                <th>Estudiante</th>
                                <th>Año C.</th>
                                <th>Calificación</th>
                            </tr>
                            @foreach ($students as $item)
                                <tr>
                                    <td>{{ $item->name }}</td>
                                    <td class="text-center">{{ $item->course }}</td>
                                    <td class="text-center">
                                        <input type="hidden" name="students[{{ $item->student_id  }}][id]" value="{{ $item->student_id }}">
                                        <input type="number" name="students[{{ $item->student_id }}][note]" 
                                            @if($item->qualification)
                                                value="{{$item->qualification}}" 
                                            @endif min="0" max="10" step="0.01" style="width: 85px; height: 35px;">
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                        <p class="error text-center font-semibold" style="color: rgb(161, 44, 44)">
                            @if (gettype($errors) != gettype((object)array('1'=>1)))
                                {{ $errors }}
                            @endif
                        </p>
                        <div class="flex justify-center" style="width: 100%; height: 100%; padding: 10px 0px;">
                            <button type="submit" class="button-search">Subir Calificaciones</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
