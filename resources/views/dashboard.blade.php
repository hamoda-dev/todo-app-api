<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @if (session()->has('success_message'))
                        <div class="p-4 mb-4 text-green-700 border rounded border-green-900/10 bg-green-50" role="alert">
                            <strong class="text-sm font-medium">{{ session('success_message') }}</strong>
                        </div>
                    @endif

                    @if (session()->has('error_message'))
                        <div class="p-4 mb-4 text-green-700 border rounded border-green-900/10 bg-green-50" role="alert">
                            <strong class="text-sm font-medium">{{ session('error_message') }}</strong>
                        </div>
                    @endif

                    <h2 class="font-semibold mb-4">Api Key</h2>
                    <div class="p-4 border rounded text-sky-700 bg-sky-50 border-sky-900/10 overflow-auto" role="alert">
                        <strong class="text-sm font-medium">{{ $app->key }}</strong>
                    </div>

                    <form action="{{ route('dashboard.regenrate-key') }}" method="POST">
                        @csrf
                        <button type="submit" class="mx-2 my-2 bg-indigo-700 transition duration-150 ease-in-out hover:bg-indigo-600 rounded text-white px-6 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-offset-2  focus:ring-indigo-600">Regenrate KEY</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
