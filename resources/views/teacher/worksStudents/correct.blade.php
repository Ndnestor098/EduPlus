<x-app-layout>
    <x-slot name="title">
        Corregir: {{$student->name}}
    </x-slot>

    <x-slot name='navigation'>@include('layouts.navigation')</x-slot>

    <x-slot name="header">
        <a href="{{route("teacher.works.students", ['nameWork'=>$student->work->slug])}}" class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Actividades') }}
        </a>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 container mx-auto">
                    <div class="flex gap-5 mb-8">
                        <a href="{{route("teacher.works.students", ['nameWork'=>$student->work->slug])}}">
                            <img src="/assets/img/back.png" alt="back" style="height: 25px">
                        </a>
                        <span class="text-lg font-semibold">Corregir a: {{$student->name}}</span>
                    </div>

                    <div class="flex items-center justify-center flex-col gap-5">
                        <div>
                            <span class="text-2xl font-semibold">{{$student->work->title}}</span>
                        </div>

                        @if ($student->image)
                            <div class="flex flex-col justify-center items-center gap-5">
                                @foreach (json_decode($student->image, true) as $item)
                                    <a class="flex justify-center" href="{{ $item }}" target="_blank">
                                        <img class="w-7/12 shadow-xl" style="box-shadow: 0px 13px 15px -15px rgba(153,153,153,1);" src="{{ $item }}" alt="imagen de la tarea o proyecto">
                                    </a>
                                @endforeach
                            </div> 
                        @endif

                        @if ($student->file)
                            <div class="flex justify-center gap-5 items-center w-full">
                                @foreach (json_decode($student->file, true) as $item)
                                    <a class="flex flex-col items-center gap-2" href="{{ $item }}" target="_blank">
                                        <img class="w-14" src="/assets/img/pdf.png" alt="logo pdf">
                                        <span>Archivo de apoyo de la tarea.</span>
                                    </a>
                                @endforeach
                            </div>
                        @endif
                        
                        <div class="mt-10 border border-solid border-naranja rounded-md p-2" style="box-shadow: 0px 13px 15px -15px rgba(153,153,153,1);">
                            <form action="{{route("correct.work")}}" method="POST" class="form" enctype="application/x-www-form-urlencoded" novalidate autocomplete="on">
                                @csrf
                                <input type="hidden" name="student_id" value="{{$student->student_id}}">
                                <input type="hidden" name="workStudent_id" value="{{$student->id}}">
                                <input type="hidden" name="slug" value="{{$student->work->slug}}">

                                <span class="text-xl text-center">Corregir Tarea</span>

                                <div>
                                    <label for="note">Nota <span>Se acepta decimales, se recomienda del 1 al 10.</span></label>
                                    @if ($student->qualification)
                                        <input type="number" id="note" name="note" step="0.01" min="0" max="10" value="{{$student->qualification}}" required>
                                    @else
                                        <input type="number" id="note" name="note" step="0.01" min="0" max="10" placeholder="0.00" required>
                                    @endif
                                </div>

                                <p class="error text-center font-semibold" style="color: rgb(161, 44, 44)">
                                    @if (gettype($errors) != gettype((object)array('1'=>1)))
                                        {{ $errors }}
                                    @endif
                                </p>

                                <div>
                                    <button type="submit" class="button-update">Correccion</button>
                                </div>
                            </form>
                            <form action="{{route("delete.work")}}" method="POST" class="form mt-5" enctype="application/x-www-form-urlencoded" novalidate autocomplete="on">
                                @csrf
                                @method("delete")
                                
                                <input type="hidden" name="workStudent_id" value="{{$student->id}}">
                                <input type="hidden" name="slug" value="{{$student->work->slug}}">

                                <button type="submit" class="button-delete">Eleminar</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
