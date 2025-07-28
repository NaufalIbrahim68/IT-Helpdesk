@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-2xl mx-auto">
        <h3 class="mb-6 text-2xl font-bold text-gray-900">Form Input Ticketing</h3>

        @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-6 flex items-center">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
            </svg>
            {{ session('success') }}
        </div>
        @endif

        @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-6">
            <div class="flex items-center mb-2">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
                <span class="font-medium">Terjadi kesalahan:</span>
            </div>
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <div class="p-6">
                <form method="POST" action="{{ route('inputdata.store') }}">
                    @csrf

                    {{-- Nama --}}
                    <div class="mb-6">
                        <label for="name" class="block text-sm font-medium text-black mb-2">Nama</label>
                        <input type="text" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-900 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               value="{{ Auth::user()->name }}" 
                               readonly>
                        <input type="hidden" name="name" value="{{ Auth::user()->name }}">
                    </div>

                    {{-- NPK --}}
                    <div class="mb-6">
                        <label for="npk" class="block text-sm font-medium text-black mb-2">NPK</label>
                        <input type="text" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-900 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               value="{{ Auth::user()->npk }}" 
                               readonly>
                        <input type="hidden" name="npk" value="{{ Auth::user()->npk }}">
                    </div>

                    {{-- Departemen --}}
                    <div class="mb-6">
                        <label for="department" class="block text-sm font-medium text-black mb-2">Departemen</label>

                        @php
                        $userDepartment = Auth::user()->department;
                        @endphp

                        @if (!empty($userDepartment) && strtolower($userDepartment) !== 'tidak diketahui')
                        <input type="text" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-900 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               value="{{ $userDepartment }}" 
                               readonly>
                        <input type="hidden" name="department" value="{{ $userDepartment }}">
                        @else
                        <input type="text" 
                               name="department" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-white text-gray-900 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('department') border-red-500 @enderror"
                               value="{{ old('department') }}" 
                               placeholder="Masukkan Departemen Anda" 
                               required>
                        @error('department')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        @endif
                    </div>

                    {{-- Subject --}}
                    <div class="mb-6">
                        <label for="subject" class="block text-sm font-medium text-black mb-2">Subject</label>
                        <input type="text" 
                               name="subject" 
                               id="subject" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-white text-gray-900 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('subject') border-red-500 @enderror" 
                               value="{{ old('subject') }}" 
                               placeholder="Masukkan subject tiket"
                               required>
                        @error('subject')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Deskripsi --}}
                    <div class="mb-6">
                        <label for="description" class="block text-sm font-medium text-black mb-2">Deskripsi</label>
                        <textarea name="description" 
                                  id="description" 
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-white text-gray-900 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('description') border-red-500 @enderror" 
                                  rows="4" 
                                  placeholder="Jelaskan masalah atau permintaan Anda secara detail..."
                                  required>{{ old('description') }}</textarea>
                        @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-end space-x-3">
                        <a href="{{ route('ticketing.index') }}" 
                           class="px-6 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors duration-200">
                            Batal
                        </a>
                        <button type="submit" 
                                class="px-6 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200">
                            Submit Tiket
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection