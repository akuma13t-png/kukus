@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-[#1b2838] py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="mb-8">
            <h1 class="text-4xl font-black text-white uppercase tracking-widest">
                Upload <span class="text-[#66c0f4]">New Game</span>
            </h1>
            <p class="text-gray-400 mt-2">Isi detail game Anda untuk dipublish di SteamClone Store. Game akan direview oleh Admin sebelum tayang.</p>
        </div>

        @if ($errors->any())
            <div class="bg-red-900/50 border border-red-500 p-4 mb-6 rounded text-red-200">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>• {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('games.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf

            {{-- 1. INFORMASI DASAR --}}
            <div class="bg-[#16202d] p-6 border-t-4 border-[#66c0f4] shadow-2xl">
                <h3 class="text-xl font-bold text-white mb-6 uppercase flex items-center gap-2">
                    <span class="bg-[#66c0f4] text-black w-6 h-6 flex items-center justify-center rounded text-xs font-black">1</span>
                    Basic Information
                </h3>

                <div class="space-y-4">
                    <div>
                        <label class="block text-[#66c0f4] text-xs font-bold uppercase mb-2">Game Title</label>
                        <input type="text" name="title" value="{{ old('title') }}" class="w-full bg-[#2a3f5a] text-white border border-black p-3 focus:outline-none focus:border-[#66c0f4] rounded-sm placeholder-gray-500" placeholder="Enter game title...">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-gray-400 text-xs font-bold uppercase mb-2">Genre</label>
                            <select name="genre" class="w-full bg-[#2a3f5a] text-white border border-black p-3 focus:outline-none focus:border-[#66c0f4] rounded-sm">
                                <option value="Action">Action</option>
                                <option value="RPG">RPG</option>
                                <option value="Strategy">Strategy</option>
                                <option value="Simulation">Simulation</option>
                                <option value="Adventure">Adventure</option>
                                <option value="Racing">Racing</option>
                                <option value="Sports">Sports</option>
                                <option value="Horror">Horror</option>
                                <option value="Indie">Indie</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-gray-400 text-xs font-bold uppercase mb-2">Price (IDR)</label>
                            <input type="number" name="price" value="{{ old('price') }}" class="w-full bg-[#2a3f5a] text-white border border-black p-3 focus:outline-none focus:border-[#66c0f4] rounded-sm" placeholder="e.g. 150000">
                        </div>
                    </div>

                    <div>
                        <label class="block text-gray-400 text-xs font-bold uppercase mb-2">Description</label>
                        <textarea name="description" rows="5" class="w-full bg-[#2a3f5a] text-white border border-black p-3 focus:outline-none focus:border-[#66c0f4] rounded-sm" placeholder="Tell us about your game...">{{ old('description') }}</textarea>
                    </div>
                </div>
            </div>

            {{-- 2. MEDIA & ASSETS --}}
            <div class="bg-[#16202d] p-6 border-t-4 border-green-500 shadow-2xl">
                <h3 class="text-xl font-bold text-white mb-6 uppercase flex items-center gap-2">
                    <span class="bg-green-500 text-black w-6 h-6 flex items-center justify-center rounded text-xs font-black">2</span>
                    Media Assets
                </h3>

                <div class="space-y-6">
                    {{-- Cover Image --}}
                    <div>
                        <label class="block text-green-400 text-xs font-bold uppercase mb-2">Cover Image (Main Thumbnail)</label>
                        <input type="file" name="cover_image" accept="image/*" class="block w-full text-sm text-gray-400
                            file:mr-4 file:py-2 file:px-4
                            file:rounded-sm file:border-0
                            file:text-xs file:font-bold file:uppercase
                            file:bg-green-600 file:text-white
                            hover:file:bg-green-500
                            cursor-pointer bg-[#2a3f5a] rounded-sm border border-black
                        "/>
                        <p class="text-[10px] text-gray-500 mt-1">Recommended size: 600x900px (Vertical)</p>
                    </div>

                    {{-- Screenshots --}}
                    <div>
                        <label class="block text-green-400 text-xs font-bold uppercase mb-2">Screenshots (Select Multiple)</label>
                        <input type="file" name="screenshots[]" accept="image/*" multiple class="block w-full text-sm text-gray-400
                            file:mr-4 file:py-2 file:px-4
                            file:rounded-sm file:border-0
                            file:text-xs file:font-bold file:uppercase
                            file:bg-green-600 file:text-white
                            hover:file:bg-green-500
                            cursor-pointer bg-[#2a3f5a] rounded-sm border border-black
                        "/>
                        <p class="text-[10px] text-gray-500 mt-1">Upload in-game screenshots to showcase gameplay.</p>
                    </div>

                    {{-- Trailer Video --}}
                    <div>
                        <label class="block text-green-400 text-xs font-bold uppercase mb-2">Trailer Video (Optional)</label>
                        <input type="file" name="trailer_video" accept="video/mp4,video/x-m4v,video/*" class="block w-full text-sm text-gray-400
                            file:mr-4 file:py-2 file:px-4
                            file:rounded-sm file:border-0
                            file:text-xs file:font-bold file:uppercase
                            file:bg-green-600 file:text-white
                            hover:file:bg-green-500
                            cursor-pointer bg-[#2a3f5a] rounded-sm border border-black
                        "/>
                        <p class="text-[10px] text-gray-500 mt-1">Max size: 20MB. Format: MP4.</p>
                    </div>
                </div>
            </div>

            <div class="flex justify-end pt-4">
                <button type="submit" class="bg-gradient-to-r from-[#66c0f4] to-[#419ec0] hover:brightness-110 text-white font-black text-lg px-10 py-4 rounded-sm shadow-lg uppercase tracking-widest transform hover:translate-y-[-2px] transition">
                    Submit Game
                </button>
            </div>

        </form>
    </div>
</div>
@endsection