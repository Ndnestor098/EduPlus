<x-app-layout>
    <x-slot name="title">
        Calendiario
    </x-slot>
    
    <x-slot name='navigation'>@include('layouts.navigation')</x-slot>

    <x-slot name="header">
        <a href="{{route('calendar')}}" class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Calendiario') }}
        </a>
    </x-slot>

    

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 container mx-auto">
                            <div class="bg-gray-100 flex items-center justify-center">
                                {{----------------------------------------- Calendario -----------------------------------------}}
                                <div class="lg:w-7/12 md:w-9/12 sm:w-10/12 mx-auto p-4 w-full h-full">
                                    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                                        <div class="flex items-center justify-between px-6 py-3 bg-gray-700">
                                            <button id="prevMonth" class="text-white">Previous</button>
                                            <h2 id="currentMonth" class="text-white"></h2>
                                            <button id="nextMonth" class="text-white">Next</button>
                                        </div>
                                        <div class="grid grid-cols-7 gap-2 p-4" id="calendar">
                                            <!-- Calendar Days Go Here -->
                                        </div>
                                        <div id="myModal" class="modal hidden fixed inset-0 flex items-center justify-center z-50">
                                            <div class="modal-overlay absolute inset-0 bg-black opacity-50"></div>
                                        
                                            <div class="modal-container bg-white w-11/12 md:max-w-md mx-auto rounded shadow-lg z-50 overflow-y-auto">
                                                <div class="modal-content py-4 text-left px-6" style="background-color: #fff; box-shadow: 0px 13px 15px -13px rgba(153,153,153,1)">
                                                    <div class="flex justify-between items-center pb-3">
                                                    <p class="text-xl font-semibold">Fecha Seleccionada:</p>
                                                    <button id="closeModal" class="modal-close px-3 py-1 rounded-full bg-gray-200 hover:bg-gray-300 focus:outline-none focus:ring">✕</button>
                                                    </div>
                                                    <div id="modalDate" class="text-base font-semibold flex flex-col gap-3">

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                </div>
                    </div>
            </div>
        </div>
    </div>
    <script src="/assets/js/calendar.js"></script>
</x-app-layout>
