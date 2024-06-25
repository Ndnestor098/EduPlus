<x-app-layout>
    <x-slot name="title">
        Tarea: {{$work->title}}
    </x-slot>

    <x-slot name='navigation'>@include('layouts.navigation')</x-slot>

    <x-slot name="header">
        <a href="{{route("student.works")}}" class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Actividades') }}
        </a>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 container mx-auto">
                    <div class="flex gap-5 mb-8">
                        <a href="{{route("student.works")}}"><img src="/assets/img/back.png" alt="back" style="height: 25px"></a>
                        <span class="text-lg font-semibold">Tarea: {{$work->title}}</span>
                    </div>

                    <div class="flex items-center justify-center flex-col gap-5">
                        <div>
                            <span class="text-2xl font-semibold">{{ $work->title }}</span>
                        </div>

                        <div class="flex justify-center w-full">
                            <p class="leading-relaxed text-justify">{{$work->description}}</p>
                        </div>

                        @if ($work->image)
                            @foreach (json_decode($work->image, true) as $item)
                                <div class="flex justify-center items-center">
                                    <a class="flex justify-center" href="{{ $item }}" target="_blank">
                                        <img class="w-7/12 shadow-xl" style="box-shadow: 0px 13px 15px -15px rgba(153,153,153,1);" src="{{ $item }}" alt="imagen de la tarea o proyecto">
                                    </a>
                                </div> 
                            @endforeach
                        @endif

                        @if ($work->file)
                            <div class="flex justify-center items-center flex-wrap w-full gap-4">
                                @foreach (json_decode($work->file, true) as $item)
                                        <a class="flex flex-col items-center gap-2" href="{{ $item }}" target="_blank">
                                            <img class="w-14" src="/assets/img/pdf.png" alt="logo pdf">
                                            <span>Archivo de apoyo de la tarea.</span>
                                        </a>
                                @endforeach
                            </div>
                        @endif
                        
                        @if ($work->deliver > $date)
                            <div class="mt-10 border border-solid border-naranja rounded-md p-2" style="box-shadow: 0px 13px 15px -15px rgba(153,153,153,1);">
                                <form action="{{route("upWork")}}" method="POST" class="form" enctype="multipart/form-data" novalidate autocomplete="on">
                                    @csrf
                                    <input type="hidden" name="subject" value="{{$work->subject}}">
                                    <input type="hidden" name="work_id" value="{{$work->id}}">
                                    <span class="text-xl text-center">Subir Tarea</span>
                                    <div>
                                        <label for="files">Archivos (pdf, doc, etc..) <span>Lo que corresponda.</span></label>
                                        <input type="file" name="files[]" id="files" accept=".doc,.docx,.xls,.xlsx,.pdf,.ppt,.pptx" multiple required>
                                    </div>
                                    <div>
                                        <label for="images">Imágenes <span>Lo que corresponda.</span></label>
                                        <input type="file" name="images[]" id="images" accept="image/*" multiple required>
                                    </div>
                                    <div>
                                        <button type="submit" class="button-update">Subir tarea</button>
                                    </div>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
