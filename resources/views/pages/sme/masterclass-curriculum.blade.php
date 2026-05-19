@extends('layouts.backoffice')

@section('title', 'Masterclass Curriculum Builder — ' . $blueprint->title)

@section('sidebar-nav')
    @include('partials.sme-sidebar')
@endsection

@section('page-title', 'Curriculum Builder')

@push('styles')
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <style>
        .sortable-drag-fallback {
            transition: none !important;
            pointer-events: none !important;
            cursor: grabbing !important;
            opacity: 0.9 !important;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25) !important;
            z-index: 999999 !important;
            user-select: none !important;
            -webkit-user-select: none !important;
        }
        body.sortable-dragging, body.sortable-dragging * {
            user-select: none !important;
            -webkit-user-select: none !important;
        }
        /* Quill Vern-Style Overrides */
        .ql-toolbar.ql-snow {
            border: none !important;
            border-bottom: 1px solid #e4e4e7 !important;
            background-color: #f4f4f5 !important;
            padding: 10px 16px !important;
            font-family: inherit !important;
        }
        .ql-container.ql-snow {
            border: none !important;
            font-family: inherit !important;
        }
        .ql-editor {
            min-height: 140px;
            padding: 20px 24px !important;
            font-size: 0.75rem !important;
            font-weight: 700 !important;
            color: #27272a !important;
            line-height: 1.6 !important;
        }
        .ql-editor.ql-blank::before {
            color: #a1a1aa !important;
            font-style: normal !important;
            font-weight: 600 !important;
            font-size: 0.75rem !important;
        }
        .ql-editor img {
            cursor: pointer !important;
            user-select: auto !important;
            -webkit-user-select: auto !important;
            position: relative;
            z-index: 50;
        }
        .ql-editor img[style*="margin: auto"], 
        .ql-editor img.ql-align-center,
        .ql-editor .ql-align-center img {
            display: block !important;
            margin-left: auto !important;
            margin-right: auto !important;
            float: none !important;
        }
        .ql-editor img[style*="float: right"], 
        .ql-editor img.ql-align-right,
        .ql-editor .ql-align-right img {
            display: block !important;
            margin-left: auto !important;
            margin-right: 0 !important;
            float: right !important;
        }
        .ql-editor img[style*="float: left"], 
        .ql-editor img.ql-align-left,
        .ql-editor .ql-align-left img {
            display: inline-block !important;
            margin-left: 0 !important;
            margin-right: auto !important;
            float: left !important;
        }
        /* Quill Tooltip Pop-up (Link Input) Overrides */
        .ql-tooltip {
            background-color: #ffffff !important;
            border: 1px solid #e4e4e7 !important;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.2), 0 8px 10px -6px rgba(0, 0, 0, 0.2) !important;
            border-radius: 16px !important;
            padding: 12px 20px !important;
            color: #27272a !important;
            font-size: 12px !important;
            font-weight: 700 !important;
            z-index: 99999 !important;
            transform: translateY(16px) !important;
            transition: all 0.2s ease !important;
        }
        .ql-tooltip input[type="text"] {
            background-color: #f4f4f5 !important;
            border: 1px solid #d4d4d8 !important;
            border-radius: 10px !important;
            padding: 8px 14px !important;
            color: #18181b !important;
            font-size: 12px !important;
            font-weight: 700 !important;
            outline: none !important;
            margin: 0 12px !important;
            box-shadow: inset 0 2px 4px 0 rgba(0, 0, 0, 0.06) !important;
        }
        .ql-tooltip input[type="text"]:focus {
            border-color: #dc2626 !important;
            background-color: #ffffff !important;
            box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.15) !important;
        }
        .ql-tooltip a.ql-action::after {
            border-right: 2px solid #e4e4e7 !important;
            padding-right: 12px !important;
            margin-left: 4px !important;
        }
        .ql-tooltip a.ql-action,
        .ql-tooltip a.ql-remove {
            color: #dc2626 !important;
            font-weight: 800 !important;
            cursor: pointer !important;
            text-decoration: none !important;
            padding: 4px 8px !important;
            border-radius: 6px !important;
            transition: all 0.15s ease !important;
        }
        .ql-tooltip a.ql-action:hover,
        .ql-tooltip a.ql-remove:hover {
            background-color: #fef2f2 !important;
            color: #b91c1c !important;
        }
    </style>
@endpush

@section('content')
<div x-data="curriculumBuilder()" class="space-y-6 pb-12">
    {{-- Hero Header --}}
    <div class="bg-white rounded-3xl p-6 shadow-sm border border-zinc-200 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <span class="px-2.5 py-1 bg-red-50 text-primary text-[10px] font-black uppercase tracking-widest rounded-md">Blueprint: {{ $blueprint->id }}</span>
                <span class="px-2.5 py-1 bg-zinc-100 text-zinc-600 text-[10px] font-black uppercase tracking-widest rounded-md">{{ $blueprint->category }}</span>
            </div>
            <h1 class="text-2xl md:text-3xl font-black text-zinc-900 tracking-tight uppercase">{{ $blueprint->title }}</h1>
            <p class="text-zinc-500 font-medium text-xs mt-1 max-w-2xl">Rancang dan susun struktur kurikulum pelatihan, video pembelajaran, serta kuis evaluasi menggunakan antarmuka interaktif drag-and-drop.</p>
        </div>
        <div class="flex items-center gap-3 shrink-0">
            <button @click="saveDraft()" class="flex items-center gap-2 px-5 py-3 bg-zinc-50 border border-zinc-200 text-zinc-700 font-bold text-xs uppercase tracking-widest rounded-xl hover:bg-zinc-100 transition-all active:scale-95 shadow-sm">
                <span class="material-symbols-outlined text-base">save</span>
                Simpan Draft
            </button>
            <button @click="submitFinal()" class="flex items-center gap-2 px-5 py-3 bg-primary text-white font-bold text-xs uppercase tracking-widest rounded-xl hover:bg-primary/90 transition-all active:scale-95 shadow-lg shadow-primary/20">
                <span class="material-symbols-outlined text-base">rocket_launch</span>
                Submit Final
            </button>
        </div>
    </div>

    {{-- Chapter Grid Container --}}
    <div id="chapters-container" class="space-y-6">
        <template x-for="(chapter, chapterIndex) in chapters" :key="chapter.id">
            <div class="chapter-card select-none relative bg-white rounded-3xl p-6 shadow-sm border border-zinc-200 hover:shadow-md transition-all duration-300" :data-id="chapter.id">
                {{-- Top Right Actions --}}
                <div class="absolute top-6 right-6 flex items-center gap-2">
                    <button @click="openEditChapterModal(chapters.indexOf(chapter))" class="flex items-center gap-1.5 px-3 py-1.5 bg-zinc-50 border border-zinc-200 rounded-lg text-zinc-500 hover:text-primary hover:border-primary/20 transition-all text-[10px] font-bold uppercase tracking-wider">
                        <span class="material-symbols-outlined text-xs">edit</span>
                        Edit Bab
                    </button>
                    <button @click="deleteChapter(chapters.indexOf(chapter))" class="flex items-center gap-1.5 px-3 py-1.5 bg-red-50 border border-red-100 rounded-lg text-red-600 hover:bg-red-100 transition-all text-[10px] font-bold uppercase tracking-wider">
                        <span class="material-symbols-outlined text-xs">delete</span>
                        Hapus
                    </button>
                </div>

                <div class="flex items-start gap-4">
                    {{-- Drag Handle --}}
                    <div class="cursor-grab active:cursor-grabbing text-zinc-400 hover:text-primary transition-colors chapter-drag-handle mt-1 p-1.5 bg-zinc-50 hover:bg-red-50 rounded-xl border border-zinc-200 flex items-center justify-center shadow-2xs">
                        <span class="material-symbols-outlined text-xl">drag_indicator</span>
                    </div>

                    {{-- Chapter Number Badge --}}
                    <div class="w-11 h-11 bg-primary/10 border border-primary/20 flex items-center justify-center rounded-xl shadow-inner shrink-0">
                        <span class="text-lg font-black text-primary" x-text="String(chapters.indexOf(chapter) + 1).padStart(2, '0')"></span>
                    </div>

                    {{-- Chapter Content --}}
                    <div class="flex-1 min-w-0 pr-32">
                        <h3 class="text-lg font-black text-zinc-900 uppercase tracking-tight mb-1" x-text="chapter.title"></h3>
                        <p class="text-zinc-500 text-xs font-medium leading-relaxed mb-4" x-text="chapter.summary || 'Belum ada rangkuman bab.'"></p>

                        {{-- Items Container --}}
                        <div class="space-y-3 mb-4">
                            <h4 class="text-[10px] font-black uppercase tracking-widest text-zinc-400 mb-2 flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-primary"></span>
                                Struktur Materi Bab (<span x-text="chapter.items.length"></span> Item)
                            </h4>

                            <div :id="`items-container-${chapter.id}`" class="items-container space-y-2 min-h-[40px] p-2 bg-zinc-50/50 rounded-xl border border-dashed border-zinc-200">
                                <template x-for="(item, itemIndex) in chapter.items" :key="item.id">
                                    <div class="item-card select-none flex items-center gap-3 bg-white border border-zinc-200/80 p-3 rounded-xl hover:border-primary/40 transition-all group/item shadow-sm" :data-id="item.id">
                                        <div class="cursor-grab active:cursor-grabbing text-zinc-300 hover:text-zinc-500 item-drag-handle">
                                            <span class="material-symbols-outlined text-base">drag_handle</span>
                                        </div>
                                        <div class="w-8 h-8 flex items-center justify-center rounded-lg shrink-0"
                                             :class="item.type === 'video' ? 'bg-red-50 text-primary' : 'bg-amber-50 text-amber-600'">
                                            <span class="material-symbols-outlined text-base" x-text="item.type === 'video' ? 'play_circle' : 'quiz'"></span>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-xs font-bold text-zinc-900 truncate">
                                                <span class="text-primary font-black mr-1" x-text="`${chapters.indexOf(chapter) + 1}.${chapter.items.indexOf(item) + 1}`"></span>
                                                <span x-text="item.title.replace(/^\d+\.\d+\s*/, '')"></span>
                                            </p>
                                            <p class="text-[9px] font-bold text-zinc-400 uppercase tracking-wider" x-text="item.meta"></p>
                                        </div>
                                        <div class="flex items-center gap-1.5 shrink-0">
                                            <button @click="openDetailModal(chapters.indexOf(chapter), chapter.items.indexOf(item))" title="Lihat Detail" class="w-7 h-7 rounded-lg bg-zinc-50 border border-zinc-200 text-zinc-400 hover:text-primary hover:border-primary/20 flex items-center justify-center transition-all">
                                                <span class="material-symbols-outlined text-xs">visibility</span>
                                            </button>
                                            <button @click="openEditItemModal(chapters.indexOf(chapter), chapter.items.indexOf(item))" title="Edit Item" class="w-7 h-7 rounded-lg bg-zinc-50 border border-zinc-200 text-zinc-400 hover:text-primary hover:border-primary/20 flex items-center justify-center transition-all">
                                                <span class="material-symbols-outlined text-xs">edit</span>
                                            </button>
                                            <button @click="deleteItem(chapters.indexOf(chapter), chapter.items.indexOf(item))" title="Hapus Item" class="w-7 h-7 rounded-lg bg-red-50 border border-red-100 text-red-500 hover:bg-red-100 flex items-center justify-center transition-all">
                                                <span class="material-symbols-outlined text-xs">delete</span>
                                            </button>
                                        </div>
                                    </div>
                                </template>
                                <template x-if="chapter.items.length === 0">
                                    <div class="py-6 text-center text-zinc-400 text-[11px] font-medium italic">
                                        Belum ada video atau kuis di bab ini. Klik tombol di bawah untuk menambahkan.
                                    </div>
                                </template>
                            </div>
                        </div>

                        {{-- Add Item Buttons --}}
                        <div class="flex items-center gap-2.5 pt-1">
                            <button @click="openAddVideoModal(chapters.indexOf(chapter))" class="flex items-center gap-1.5 px-4 py-2 border border-primary/20 bg-red-50/50 rounded-lg text-[11px] font-black uppercase tracking-widest text-primary hover:bg-red-50 hover:border-primary/40 transition-all shadow-2xs">
                                <span class="material-symbols-outlined text-sm">add_circle</span>
                                Tambah Video
                            </button>
                            <button @click="openAddQuizModal(chapters.indexOf(chapter))" class="flex items-center gap-1.5 px-4 py-2 border border-amber-500/20 bg-amber-50/50 rounded-lg text-[11px] font-black uppercase tracking-widest text-amber-600 hover:bg-amber-50 hover:border-amber-500/40 transition-all shadow-2xs">
                                <span class="material-symbols-outlined text-sm">quiz</span>
                                Tambah Kuis
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </div>

    {{-- Global Add Chapter Button --}}
    <div class="flex justify-center mt-6 mb-2">
        <button @click="openAddChapterModal()" class="flex items-center gap-2 px-6 py-3.5 border border-dashed border-zinc-300 bg-white hover:bg-zinc-50 text-zinc-600 hover:text-primary hover:border-primary/40 rounded-2xl font-bold text-xs uppercase tracking-widest transition-all shadow-sm active:scale-95 group">
            <span class="material-symbols-outlined text-lg transition-transform group-hover:rotate-90">add_circle</span>
            Tambah Edu-Segmen / Bab Baru
        </button>
    </div>

    {{-- Footer Actions Bar (Non-floating, placed at the bottom) --}}
    <div class="bg-white rounded-3xl border border-zinc-200 p-6 flex flex-col md:flex-row justify-between items-center gap-4 shadow-sm mt-8">
        <div>
            <a href="{{ route('sme.dashboard') }}" class="text-zinc-600 font-bold uppercase tracking-widest text-xs hover:text-primary flex items-center gap-2">
                <span class="material-symbols-outlined text-sm">arrow_back</span>
                Kembali ke Dashboard
            </a>
        </div>
        <div class="flex gap-4">
            <button @click="saveDraft()" class="px-6 py-3.5 bg-zinc-100 border border-zinc-200 text-zinc-700 font-black uppercase tracking-widest text-xs rounded-xl hover:bg-zinc-200 transition-all active:scale-95 shadow-sm">
                Simpan Draft
            </button>
            <button @click="submitFinal()" class="px-6 py-3.5 bg-primary text-white font-black uppercase tracking-widest text-xs rounded-xl hover:bg-primary/90 transition-all active:scale-95 shadow-lg shadow-primary/20 flex items-center gap-2">
                <span class="material-symbols-outlined text-base">rocket_launch</span>
                Submit Final Kurikulum
            </button>
        </div>
    </div>

    {{-- ==================== MODALS ==================== --}}
    <template x-teleport="body">
        <div>
            {{-- Modal Add / Edit Chapter --}}
            <div x-show="chapterModalOpen" class="fixed inset-0 z-[200] flex items-center justify-center p-4 bg-zinc-900/60 backdrop-blur-sm" x-cloak>
                <div class="bg-white w-full max-w-2xl rounded-[2.5rem] shadow-2xl overflow-hidden flex flex-col">
                    <div class="px-6 py-4 border-b border-zinc-100 flex justify-between items-center bg-zinc-50/50 shrink-0">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-xl bg-primary/10 text-primary flex items-center justify-center shadow-inner shrink-0">
                                <span class="material-symbols-outlined text-base">folder_special</span>
                            </div>
                            <h3 class="text-xl font-black uppercase tracking-tight text-zinc-900 mb-0" x-text="modalChapterMode === 'add' ? 'Tambah Bab Baru' : 'Edit Informasi Bab'"></h3>
                        </div>
                        <button @click="chapterModalOpen = false" class="w-8 h-8 rounded-full bg-white border border-zinc-200 text-zinc-400 flex items-center justify-center hover:bg-zinc-100 hover:text-zinc-900 transition-all shadow-sm shrink-0">
                            <span class="material-symbols-outlined text-base">close</span>
                        </button>
                    </div>
                    <div class="p-6 space-y-5 bg-white">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-zinc-400 uppercase tracking-widest ml-1 block">Judul Bab</label>
                            <input type="text" x-model="activeChapterForm.title" placeholder="Contoh: BAB 1 DASAR VIBRASI" class="w-full px-5 py-3 bg-white border border-zinc-200 rounded-xl focus:ring-4 focus:ring-primary/10 focus:border-primary outline-none transition-all font-bold text-sm text-zinc-900 placeholder-zinc-300 shadow-2xs">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-zinc-400 uppercase tracking-widest ml-1 block">Rangkuman / Deskripsi Bab</label>
                            <textarea x-model="activeChapterForm.summary" rows="3" placeholder="Tuliskan rangkuman materi yang akan dibahas pada bab ini..." class="w-full px-5 py-3 bg-white border border-zinc-200 rounded-xl focus:ring-4 focus:ring-primary/10 focus:border-primary outline-none transition-all font-medium text-sm text-zinc-700 placeholder-zinc-300 shadow-2xs"></textarea>
                        </div>
                    </div>
                    <div class="px-6 pb-4 pt-0 bg-white grid grid-cols-2 gap-3 shrink-0">
                        <button @click="chapterModalOpen = false" class="py-2.5 rounded-xl border border-zinc-200 text-xs font-bold text-zinc-500 hover:bg-zinc-50 transition-all uppercase tracking-widest shadow-2xs">Batal</button>
                        <button @click="saveChapterModal()" class="py-2.5 rounded-xl bg-primary text-white text-xs font-black uppercase tracking-widest shadow-md shadow-primary/20 hover:shadow-primary/40 transition-all active:scale-95">Simpan Bab</button>
                    </div>
                </div>
            </div>

            {{-- Modal Add / Edit Video (High-Fidelity Vern-Style) --}}
            <div x-show="videoModalOpen" class="fixed inset-0 z-[200] flex items-center justify-center p-4 bg-zinc-900/60 backdrop-blur-sm" x-cloak>
                <div class="bg-white w-full max-w-5xl rounded-[2.5rem] shadow-2xl overflow-hidden flex flex-col border border-zinc-100 my-auto max-h-[90vh]">
                    {{-- Header Bar --}}
                    <div class="px-6 py-4 border-b border-zinc-100 flex justify-between items-center bg-white shrink-0">
                        <div class="space-y-1">
                            <h3 class="text-xl font-black uppercase tracking-tight text-zinc-900 mb-0" x-text="modalItemMode === 'add' ? 'Upload Materi Video Baru' : 'Edit Materi Video'"></h3>
                            <div class="inline-flex items-center gap-2 px-2.5 py-0.5 bg-red-50 border border-red-100 rounded-lg text-primary text-[9px] font-black uppercase tracking-widest shadow-2xs">
                                <span class="material-symbols-outlined text-xs">target</span>
                                <span x-text="`TARGET PENEMPATAN: BAB ${String(activeItemChapterIndex + 1).padStart(2, '0')} - ${chapters[activeItemChapterIndex]?.title || ''}`"></span>
                            </div>
                        </div>
                        <button @click="videoModalOpen = false" class="w-8 h-8 rounded-full bg-zinc-50 border border-zinc-200 text-zinc-400 flex items-center justify-center hover:bg-zinc-100 hover:text-zinc-900 transition-all shadow-sm shrink-0">
                            <span class="material-symbols-outlined text-base">close</span>
                        </button>
                    </div>

                    {{-- Modal Body (2 Columns Layout) --}}
                    <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6 bg-white overflow-y-auto max-h-[75vh]">
                        {{-- Left Column: Video & Summary --}}
                        <div class="space-y-6">
                            {{-- Video Upload Zone / Preview --}}
                            <div>
                                <label class="text-[10px] font-black text-zinc-400 uppercase tracking-widest block mb-3 ml-1">File Video MP4</label>
                                <input x-ref="videoInput" type="file" accept="video/mp4,video/*" class="hidden" @change="handleVideoUpload($event)">
                                
                                {{-- Empty State --}}
                                <template x-if="!activeItemForm.videoUploaded && !isUploadingVideo">
                                    <div @click="$refs.videoInput.click()"
                                         @dragover.prevent="videoDragging = true"
                                         @dragleave.prevent="videoDragging = false"
                                         @drop.prevent="handleVideoDrop($event)"
                                         :class="videoDragging ? 'border-primary bg-primary/10 scale-[1.02]' : 'border-red-200 bg-red-50/20 hover:bg-red-50/40'"
                                         class="cursor-pointer border-2 border-dashed transition-all rounded-3xl p-8 flex flex-col items-center justify-center text-center group min-h-[240px]">
                                        <div class="w-16 h-16 rounded-full bg-primary text-white flex items-center justify-center shadow-lg shadow-primary/30 group-hover:scale-110 transition-transform mb-4">
                                            <span class="material-symbols-outlined text-3xl">cloud_upload</span>
                                        </div>
                                        <p class="text-sm font-bold text-zinc-900 mb-1">Drag & Drop File Video MP4 di Sini</p>
                                        <p class="text-[10px] font-medium text-zinc-400">Atau klik untuk memilih file dari perangkat Anda</p>
                                        <div class="mt-4 pt-4 border-t border-red-100/60 w-full flex justify-center gap-4 text-[10px] font-bold text-zinc-400">
                                            <span>Maksimal 500MB</span>
                                            <span>•</span>
                                            <span>Resolusi 1080p disarankan</span>
                                        </div>
                                    </div>
                                </template>

                                {{-- Uploading State (Progress Bar & Animasi) --}}
                                <template x-if="isUploadingVideo">
                                    <div class="border-2 border-primary/40 bg-primary/5 rounded-3xl p-8 flex flex-col items-center justify-center text-center min-h-[240px] space-y-6 shadow-inner">
                                        <div class="relative w-20 h-20 flex items-center justify-center mx-auto">
                                            <div class="absolute inset-0 rounded-full border-4 border-primary/20 animate-pulse"></div>
                                            <div class="absolute inset-0 rounded-full border-4 border-primary border-t-transparent animate-spin"></div>
                                            <span class="material-symbols-outlined text-3xl text-primary animate-bounce">cloud_upload</span>
                                        </div>
                                        <div class="space-y-2 w-full max-w-xs mx-auto">
                                            <div class="flex justify-between text-xs font-black text-zinc-800">
                                                <span>Mengunggah Video MP4...</span>
                                                <span x-text="`${videoUploadProgress}%`" class="text-primary font-extrabold"></span>
                                            </div>
                                            <div class="w-full bg-zinc-200/80 h-3 rounded-full overflow-hidden p-0.5 shadow-inner">
                                                <div class="bg-primary h-full rounded-full transition-all duration-300" :style="`width: ${videoUploadProgress}%`"></div>
                                            </div>
                                            <p class="text-[10px] font-bold text-zinc-400">Mohon tidak menutup jendela selama proses sinkronisasi</p>
                                        </div>
                                    </div>
                                </template>

                                {{-- Filled Success State --}}
                                <template x-if="activeItemForm.videoUploaded && !isUploadingVideo">
                                    <div class="space-y-4">
                                        {{-- Real Video Preview Box --}}
                                        <div class="rounded-3xl overflow-hidden relative bg-zinc-950 shadow-xl border border-zinc-800 group min-h-[240px] flex items-center justify-center">
                                            <video :src="activeItemForm.videoPreviewUrl" controls class="absolute inset-0 w-full h-full object-contain bg-zinc-950" @loadedmetadata="activeItemForm.videoDuration = new Date($event.target.duration * 1000).toISOString().substr(14, 5); activeItemForm.meta = `Video MP4 • ${activeItemForm.videoDuration} • ${activeItemForm.videoFilesize}`"></video>
                                            

                                            {{-- Remove Video Button --}}
                                            <button @click="activeItemForm.videoUploaded = false; if(activeItemForm.videoPreviewUrl && activeItemForm.videoPreviewUrl.startsWith('blob:')) URL.revokeObjectURL(activeItemForm.videoPreviewUrl); activeItemForm.videoPreviewUrl = ''" class="absolute top-4 right-4 z-10 w-8 h-8 rounded-full bg-zinc-900/80 backdrop-blur-md border border-zinc-700/50 text-zinc-400 hover:text-white hover:bg-red-600 hover:border-red-600 flex items-center justify-center transition-all shadow-lg">
                                                <span class="material-symbols-outlined text-xs">close</span>
                                            </button>
                                        </div>

                                        {{-- Active Video / Success Banner with Replace Video Button --}}
                                        <div class="bg-emerald-50 border border-emerald-200 rounded-2xl p-4 flex items-center justify-between text-emerald-700 shadow-2xs gap-4">
                                            <div class="flex items-center gap-3 min-w-0">
                                                <div class="w-8 h-8 rounded-xl bg-emerald-500 text-white flex items-center justify-center shadow-md shadow-emerald-500/20 shrink-0">
                                                    <span class="material-symbols-outlined text-base" x-text="modalItemMode === 'edit' ? 'play_circle' : 'check_circle'"></span>
                                                </div>
                                                <div class="min-w-0">
                                                    <p class="text-xs font-bold truncate" x-text="modalItemMode === 'edit' ? `File Video Aktif: ${activeItemForm.videoFilename}` : `Upload Berhasil: ${activeItemForm.videoFilename}`"></p>
                                                    <p class="text-[10px] font-bold text-emerald-600/70 uppercase tracking-wider" x-text="activeItemForm.videoFilesize"></p>
                                                </div>
                                            </div>
                                            
                                            {{-- Prominent Replace Video Button --}}
                                            <button type="button" @click="$refs.videoInput.click()" class="px-4 py-2 rounded-xl bg-white border border-emerald-300 text-emerald-800 hover:bg-emerald-100 font-black text-[11px] uppercase tracking-wider flex items-center gap-1.5 shadow-2xs shrink-0 transition-all active:scale-95">
                                                <span class="material-symbols-outlined text-sm">cloud_upload</span>
                                                Ganti Video
                                            </button>
                                        </div>
                                    </div>
                                </template>
                            </div>

                            {{-- Rangkuman / Deskripsi Materi --}}
                            <div class="space-y-3">
                                <label class="text-[10px] font-black text-zinc-400 uppercase tracking-widest ml-1">Deskripsi & Catatan Teknis</label>
                                <textarea x-model="activeItemForm.summary" rows="5" placeholder="Ketik rangkuman materi, poin-poin bahasan, atau instruksi khusus bagi siswa..." class="w-full p-6 bg-red-50/20 border border-red-100 rounded-3xl focus:ring-4 focus:ring-primary/10 focus:border-primary outline-none transition-all font-medium text-xs text-zinc-700 placeholder-zinc-400 leading-relaxed"></textarea>
                            </div>
                        </div>

                        {{-- Right Column: Title, PDF Attachment & Submit --}}
                        <div class="space-y-6 flex flex-col justify-between">
                            <div class="space-y-6">
                                {{-- Judul Teknis / Video --}}
                                <div class="space-y-3">
                                    <label class="text-[10px] font-black text-zinc-400 uppercase tracking-widest ml-1">Judul Teknis / Video</label>
                                    <input type="text" x-model="activeItemForm.title" placeholder="Masukkan Judul Teknis..." class="w-full px-6 py-5 bg-red-50/20 border border-red-100 rounded-3xl focus:ring-4 focus:ring-primary/10 focus:border-primary outline-none transition-all font-bold text-sm text-zinc-900 placeholder-zinc-400 shadow-2xs">
                                </div>

                                {{-- Dokumen Pendukung / PDF --}}
                                <div>
                                    <label class="text-[10px] font-black text-zinc-400 uppercase tracking-widest block mb-3 ml-1">Dokumen Pendukung / Lampiran</label>
                                    <input x-ref="pdfInput" type="file" class="hidden" @change="handlePdfUpload($event)">
                                    
                                    {{-- Empty State --}}
                                    <template x-if="!activeItemForm.attachments || activeItemForm.attachments.length === 0">
                                        <div @click="$refs.pdfInput.click()"
                                             @dragover.prevent="pdfDragging = true"
                                             @dragleave.prevent="pdfDragging = false"
                                             @drop.prevent="handlePdfDrop($event)"
                                             :class="pdfDragging ? 'border-primary bg-primary/10 scale-[1.02]' : 'border-zinc-200 bg-zinc-50/50 hover:bg-zinc-100/50'"
                                             class="cursor-pointer border-2 border-dashed transition-all rounded-3xl p-8 flex flex-col items-center justify-center text-center group min-h-[160px]">
                                            <span class="material-symbols-outlined text-4xl text-zinc-400 group-hover:scale-110 transition-transform mb-2">attach_file</span>
                                            <p class="text-xs font-bold text-zinc-600 mb-1">Tarik file dokumen/lampiran ke sini</p>
                                            <p class="text-[10px] font-medium text-zinc-400">Word, Excel, PDF, Gambar, atau ZIP archive</p>
                                        </div>
                                    </template>

                                    {{-- Filled Success State (Multi Attachments) --}}
                                    <template x-if="activeItemForm.attachments && activeItemForm.attachments.length > 0">
                                        <div class="space-y-3">
                                            <template x-for="(att, attIdx) in activeItemForm.attachments" :key="attIdx">
                                                <div class="bg-zinc-900 text-white rounded-3xl p-5 flex items-center justify-between shadow-xl border border-zinc-800 gap-4">
                                                    <div class="flex items-center gap-4 min-w-0">
                                                        <div class="w-12 h-12 rounded-2xl bg-red-500/20 text-primary flex items-center justify-center border border-red-500/30 shrink-0">
                                                            <span class="material-symbols-outlined text-2xl">description</span>
                                                        </div>
                                                        <div class="min-w-0">
                                                            <p class="text-xs font-bold truncate mb-0.5" x-text="att.filename"></p>
                                                            <p class="text-[10px] font-bold text-zinc-400 uppercase tracking-wider mb-0" x-text="`(Upload Sukses • ${att.filesize || '2.4 MB'})`"></p>
                                                        </div>
                                                    </div>
                                                    
                                                    {{-- Tombol Aksi: Buka Preview (Hanya PDF), Unduh (Hanya Non-PDF), dan Hapus --}}
                                                    <div class="flex items-center gap-2 shrink-0">
                                                        {{-- Tombol Buka Preview (Hanya untuk PDF) --}}
                                                        <template x-if="att.filename && att.filename.toLowerCase().endsWith('.pdf')">
                                                            <a :href="att.url && att.url !== '#' ? att.url : 'https://www.w3.org/WAI/ER/tests/xhtml/testfiles/resources/pdf/dummy.pdf'" target="_blank" class="px-3.5 py-2 rounded-xl bg-zinc-800 border border-zinc-700 text-zinc-300 hover:text-white hover:bg-zinc-700 hover:border-zinc-600 flex items-center gap-1.5 transition-all shadow-sm font-bold text-[11px] uppercase tracking-wider">
                                                                <span class="material-symbols-outlined text-sm text-primary">visibility</span>
                                                                <span>Buka Preview</span>
                                                            </a>
                                                        </template>

                                                        {{-- Tombol Unduh File (Hanya untuk Non-PDF) --}}
                                                        <template x-if="att.filename && !att.filename.toLowerCase().endsWith('.pdf')">
                                                            <a :href="att.url && att.url !== '#' ? att.url : 'data:text/plain;charset=utf-8,' + encodeURIComponent('Contoh isi file lampiran LMS SIG: ' + att.filename)" :download="att.filename" class="px-3.5 py-2 rounded-xl bg-zinc-800 border border-zinc-700 text-zinc-300 hover:text-white hover:bg-zinc-700 hover:border-zinc-600 flex items-center gap-1.5 transition-all shadow-sm font-bold text-[11px] uppercase tracking-wider">
                                                                <span class="material-symbols-outlined text-sm text-emerald-400">download</span>
                                                                <span>Unduh File</span>
                                                            </a>
                                                        </template>

                                                        {{-- Tombol Hapus Lampiran --}}
                                                        <button type="button" @click="removeAttachment(attIdx)" class="w-10 h-10 rounded-xl bg-zinc-800 border border-zinc-700 text-zinc-400 hover:text-white hover:bg-red-600 hover:border-red-600 flex items-center justify-center transition-all shadow-sm" title="Hapus Lampiran">
                                                            <span class="material-symbols-outlined text-base">delete</span>
                                                        </button>
                                                    </div>
                                                </div>
                                            </template>
                                            <button @click="$refs.pdfInput.click()" class="w-full py-4 border-2 border-dashed border-zinc-200 bg-white hover:bg-zinc-50 text-zinc-600 hover:text-primary hover:border-primary/40 rounded-2xl font-bold text-xs uppercase tracking-widest transition-all flex items-center justify-center gap-2 shadow-2xs mt-2">
                                                <span class="material-symbols-outlined text-base">add_circle</span>
                                                Tambah Lampiran Lain
                                            </button>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Footer Bar --}}
                    <div class="px-6 py-3.5 bg-zinc-50 border-t border-zinc-200 flex justify-between items-center shrink-0">
                        <div class="flex items-center gap-3">
                            <button @click="videoModalOpen = false" class="px-5 py-2.5 rounded-xl border border-zinc-200 text-zinc-600 font-bold text-xs uppercase tracking-widest hover:bg-zinc-100 transition-all active:scale-95 shadow-2xs">BATAL</button>
                            
                            {{-- Tombol Simpan ke Draf Non-Closing --}}
                            <button type="button" @click="saveVideoDraft()" 
                                    class="px-5 py-2.5 rounded-xl border border-zinc-300 bg-white hover:bg-zinc-100 text-zinc-700 font-black text-xs uppercase tracking-widest transition-all active:scale-95 shadow-2xs flex items-center gap-1.5"
                                    :disabled="isSavingDraft">
                                <span class="material-symbols-outlined text-base" :class="isSavingDraft ? 'animate-spin' : ''" x-text="isSavingDraft ? 'sync' : 'save_as'"></span>
                                <span x-text="isSavingDraft ? 'Menyimpan...' : 'Simpan ke Draf'"></span>
                            </button>
                        </div>
                        <div class="flex items-center gap-5">
                            <div class="text-right">
                                <p class="text-[9px] font-black text-primary uppercase tracking-widest mb-0.5">STATUS DRAF</p>
                                <p class="text-xs font-bold text-zinc-700 mb-0" x-text="draftStatusText || (activeItemForm.videoUploaded ? 'Video & Lampiran Disimpan' : 'Belum Ada Video')"></p>
                            </div>
                            <button @click="saveVideoModal()" class="px-6 py-2.5 rounded-xl bg-primary text-white font-black text-xs uppercase tracking-widest hover:bg-primary/90 transition-all active:scale-95 shadow-md shadow-primary/20 flex items-center gap-1.5">
                                <span class="material-symbols-outlined text-base">save</span>
                                SIMPAN VIDEO KE SILABUS
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Modal Add / Edit Quiz (High-Fidelity Vern-Style matching Figma & Audio Specs) --}}
            <div x-show="quizModalOpen" class="fixed inset-0 z-[200] flex items-center justify-center p-4 bg-zinc-900/60 backdrop-blur-sm" x-cloak>
                <div class="bg-white w-full max-w-7xl rounded-[2.5rem] shadow-2xl overflow-hidden flex flex-col border border-zinc-100 my-auto max-h-[90vh]">
                    {{-- Header Bar --}}
                    <div class="px-6 py-4 border-b border-zinc-100 flex justify-between items-center bg-white shrink-0">
                        <div class="space-y-1">
                            <h3 class="text-xl font-black uppercase tracking-tight text-zinc-900 mb-0" x-text="modalItemMode === 'add' ? 'Pembuatan Evaluasi / Kuis Baru' : 'Edit Evaluasi / Kuis'"></h3>
                            <div class="inline-flex items-center gap-2 px-2.5 py-0.5 bg-red-50 border border-red-100 rounded-lg text-primary text-[9px] font-black uppercase tracking-widest shadow-2xs">
                                <span class="material-symbols-outlined text-xs">target</span>
                                <span x-text="`TARGET PENEMPATAN: BAB ${String(activeItemChapterIndex + 1).padStart(2, '0')} - ${chapters[activeItemChapterIndex]?.title || ''}`"></span>
                            </div>
                        </div>
                        <button @click="quizModalOpen = false" class="w-8 h-8 rounded-full bg-zinc-50 border border-zinc-200 text-zinc-400 flex items-center justify-center hover:bg-zinc-100 hover:text-zinc-900 transition-all shadow-sm shrink-0">
                            <span class="material-symbols-outlined text-base">close</span>
                        </button>
                    </div>

                    {{-- Modal Body (1 Row Layout: Pengaturan Kuis di Atas, Question Builder Lebar di Bawah) --}}
                    <div class="p-6 space-y-8 bg-white overflow-y-auto flex-1 min-h-0">
                        {{-- Pengaturan Aturan Kuis (Compact Top Card) --}}
                        <div class="bg-zinc-50 border border-zinc-200/80 rounded-3xl p-6 space-y-4 shadow-2xs">
                            <div class="flex items-center justify-between border-b border-zinc-200/60 pb-3">
                                <p class="text-[10px] font-black text-primary uppercase tracking-widest mb-0">Pengaturan Aturan Kuis</p>
                                <span class="text-[10px] font-bold text-zinc-400">Parameter Dasar Evaluasi</span>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-end">
                                {{-- Judul Kuis Utama (Span 4) --}}
                                <div class="md:col-span-4 space-y-2">
                                    <label class="text-[10px] font-black text-zinc-400 uppercase tracking-widest block ml-1">Judul Kuis Utama</label>
                                    <input type="text" x-model="activeItemForm.title" placeholder="Kuis Evaluasi Terminasi Sensor..." class="w-full px-5 py-3.5 bg-white border border-zinc-200 rounded-2xl focus:ring-4 focus:ring-primary/10 focus:border-primary outline-none transition-all font-bold text-xs text-zinc-900 placeholder-zinc-400 shadow-2xs">
                                </div>

                                {{-- Durasi (Span 3) --}}
                                <div class="md:col-span-3 space-y-2">
                                    <div class="flex items-center justify-between ml-1">
                                        <label class="text-[10px] font-black text-zinc-400 uppercase tracking-widest block">Durasi (Menit)</label>
                                        <label class="inline-flex items-center gap-1 cursor-pointer">
                                            <input type="checkbox" x-model="activeItemForm.isInfinityDuration" class="sr-only peer">
                                            <span class="text-[9px] font-extrabold text-zinc-400 uppercase peer-checked:text-primary">Infinity</span>
                                            <div class="w-6 h-3.5 bg-zinc-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-zinc-300 after:border after:rounded-full after:h-2.5 after:w-2.5 after:transition-all peer-checked:bg-primary relative"></div>
                                        </label>
                                    </div>
                                    <div class="relative flex items-center">
                                        <input type="number" x-model="activeItemForm.durationMinutes" :disabled="activeItemForm.isInfinityDuration" :placeholder="activeItemForm.isInfinityDuration ? '∞' : '15'" class="w-full px-5 py-3.5 bg-white border border-zinc-200 rounded-2xl focus:ring-4 focus:ring-primary/10 focus:border-primary outline-none transition-all font-bold text-xs text-zinc-900 placeholder-zinc-400 disabled:opacity-50 disabled:bg-zinc-100 disabled:border-zinc-200 shadow-2xs">
                                        <span class="material-symbols-outlined absolute right-4 text-sm text-red-300 pointer-events-none" x-text="activeItemForm.isInfinityDuration ? 'all_inclusive' : 'timer'"></span>
                                    </div>
                                </div>

                                {{-- Passing Grade (Span 2) --}}
                                <div class="md:col-span-2 space-y-2">
                                    <label class="text-[10px] font-black text-zinc-400 uppercase tracking-widest block ml-1">Passing Grade (%)</label>
                                    <div class="relative flex items-center">
                                        <input type="number" x-model="activeItemForm.passingGrade" placeholder="75" class="w-full px-5 py-3.5 bg-white border border-zinc-200 rounded-2xl focus:ring-4 focus:ring-primary/10 focus:border-primary outline-none transition-all font-bold text-xs text-zinc-900 placeholder-zinc-400 shadow-2xs">
                                        <span class="material-symbols-outlined absolute right-4 text-sm text-red-300 pointer-events-none">star</span>
                                    </div>
                                </div>

                                {{-- Acak Urutan Pertanyaan & Tampilkan Jawaban Benar (Span 3) --}}
                                <div class="md:col-span-3 flex flex-col gap-3 justify-center">
                                    {{-- Shuffle Mode --}}
                                    <div class="flex items-center justify-between bg-white px-4 py-2.5 border border-zinc-200 rounded-2xl shadow-2xs">
                                        <span class="text-xs font-bold text-zinc-800">Acak Soal</span>
                                        <label class="relative inline-flex items-center cursor-pointer m-0">
                                            <input type="checkbox" x-model="activeItemForm.shuffle" class="sr-only peer">
                                            <div class="w-9 h-5 bg-zinc-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-zinc-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-primary"></div>
                                        </label>
                                    </div>

                                    {{-- Show Correct Answer --}}
                                    <div class="flex items-center justify-between bg-white px-4 py-2.5 border border-zinc-200 rounded-2xl shadow-2xs">
                                        <span class="text-xs font-bold text-zinc-800">Tampilkan Kunci</span>
                                        <label class="relative inline-flex items-center cursor-pointer m-0">
                                            <input type="checkbox" x-model="activeItemForm.showCorrectAnswer" class="sr-only peer">
                                            <div class="w-9 h-5 bg-zinc-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-zinc-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-primary"></div>
                                        </label>
                                    </div>
                                </div>

                                {{-- Deskripsi / Rangkuman Kuis (Span 12) --}}
                                <div class="md:col-span-12 space-y-2 pt-2">
                                    <label class="text-[10px] font-black text-zinc-400 uppercase tracking-widest block ml-1">Deskripsi / Rangkuman Kuis</label>
                                    <textarea x-model="activeItemForm.summary" rows="2" placeholder="Tuliskan rangkuman atau instruksi kuis di sini..." class="w-full px-5 py-3.5 bg-white border border-zinc-200 rounded-2xl focus:ring-4 focus:ring-primary/10 focus:border-primary outline-none transition-all font-bold text-xs text-zinc-900 placeholder-zinc-400 shadow-2xs"></textarea>
                                </div>
                            </div>
                        </div>

                        {{-- Question Builder (Full-Width Row) --}}
                        <div class="w-full space-y-6 flex flex-col justify-between">
                            <div class="space-y-6">
                                {{-- Header & Pagination Navigation --}}
                                <div class="flex items-center justify-between border-b border-zinc-100 pb-4">
                                    <p class="text-[10px] font-black text-primary uppercase tracking-widest mb-0">Question Builder (Canvas Lebar)</p>
                                    <div class="flex items-center gap-2">
                                        <button @click="if(activeQuestionIndex > 0) activeQuestionIndex--" class="w-8 h-8 rounded-xl bg-zinc-100 text-zinc-600 hover:bg-zinc-200 flex items-center justify-center transition-all disabled:opacity-30 disabled:hover:bg-zinc-100" :disabled="activeQuestionIndex === 0">
                                            <span class="material-symbols-outlined text-xs font-bold">arrow_back_ios_new</span>
                                        </button>
                                        <span class="text-xs font-black text-zinc-800 px-2" x-text="`${activeQuestionIndex + 1} dari ${activeItemForm.questionsList.length}`"></span>
                                        <button @click="if(activeQuestionIndex < activeItemForm.questionsList.length - 1) activeQuestionIndex++" class="w-8 h-8 rounded-xl bg-zinc-100 text-zinc-600 hover:bg-zinc-200 flex items-center justify-center transition-all disabled:opacity-30 disabled:hover:bg-zinc-100" :disabled="activeQuestionIndex === activeItemForm.questionsList.length - 1">
                                            <span class="material-symbols-outlined text-xs font-bold">arrow_forward_ios</span>
                                        </button>
                                    </div>
                                </div>

                                {{-- Question Card Container (Quill Editor, Multiple Choice / True-False Switcher, Feedback) --}}
                                <div class="border border-red-100 bg-white rounded-3xl p-8 space-y-6 shadow-sm relative">
                                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-zinc-100 pb-4">
                                        <div class="flex items-center gap-2.5">
                                            <span class="w-5 h-5 rounded-full bg-primary/10 border border-primary text-primary flex items-center justify-center text-[10px] font-black">
                                                <span class="material-symbols-outlined text-xs">radio_button_checked</span>
                                            </span>
                                            <p class="text-xs font-black text-zinc-900 uppercase tracking-wider mb-0" x-text="`Pertanyaan #${activeQuestionIndex + 1} (${activeItemForm.questionsList[activeQuestionIndex].type === 'true_false' ? 'Benar / Salah' : 'Pilihan Ganda'})`"></p>
                                        </div>

                                        {{-- Question Type Tab Switcher --}}
                                        <div class="flex items-center gap-1 bg-zinc-100 p-1.5 rounded-2xl w-fit">
                                            <button @click="activeItemForm.questionsList[activeQuestionIndex].type = 'multiple_choice'; if(activeItemForm.questionsList[activeQuestionIndex].options.length < 2) activeItemForm.questionsList[activeQuestionIndex].options = [{ text: '', image: '' }, { text: '', image: '' }]; activeItemForm.questionsList[activeQuestionIndex].correctOptionIndex = 0;" 
                                                    class="flex items-center gap-1.5 px-4 py-2 rounded-xl font-bold text-[11px] transition-all shadow-2xs"
                                                    :class="activeItemForm.questionsList[activeQuestionIndex].type === 'multiple_choice' ? 'bg-white text-primary shadow' : 'text-zinc-500 hover:text-zinc-800'">
                                                <span class="material-symbols-outlined text-xs">format_list_bulleted</span>
                                                Pilihan Ganda
                                            </button>
                                            <button @click="activeItemForm.questionsList[activeQuestionIndex].type = 'true_false'; activeItemForm.questionsList[activeQuestionIndex].options = [{ text: 'Benar', image: '' }, { text: 'Salah', image: '' }]; activeItemForm.questionsList[activeQuestionIndex].correctOptionIndex = 0;" 
                                                    class="flex items-center gap-1.5 px-4 py-2 rounded-xl font-bold text-[11px] transition-all shadow-2xs"
                                                    :class="activeItemForm.questionsList[activeQuestionIndex].type === 'true_false' ? 'bg-white text-primary shadow' : 'text-zinc-500 hover:text-zinc-800'">
                                                <span class="material-symbols-outlined text-xs">rule</span>
                                                Benar / Salah
                                            </button>
                                        </div>
                                    </div>

                                    {{-- Quill Rich Text Editor Container --}}
                                    <div>
                                        <p class="text-[10px] font-black text-zinc-400 uppercase tracking-widest mb-2 ml-1">Teks Pertanyaan (Mendukung Format & Gambar Inline)</p>
                                        <div class="border border-zinc-200/80 rounded-3xl overflow-visible bg-zinc-50 shadow-2xs mb-2"
                                             x-init="
                                                 let quill = new Quill($refs.quillEditor, {
                                                     theme: 'snow',
                                                     bounds: $refs.quillEditor,
                                                     placeholder: 'Tuliskan pertanyaan evaluasi di sini...',
                                                     modules: {
                                                         toolbar: [
                                                             ['bold', 'italic', 'underline', 'strike'],
                                                             [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                                                             ['link', 'image'],
                                                             ['clean']
                                                         ],
                                                         botomResize: window.QuillResizeModule ? {
                                                             showSize: true,
                                                             toolbar: { alignTools: true, sizeTools: false }
                                                         } : undefined,
                                                         imageResize: window.ImageResize ? {
                                                             displaySize: true,
                                                             modules: ['Resize']
                                                         } : undefined
                                                     }
                                                 });
                                                 window.quillEditorInstance = quill;
                                                 quill.on('text-change', () => {
                                                     activeItemForm.questionsList[activeQuestionIndex].questionText = quill.root.innerHTML;
                                                 });
                                                 function checkAndInjectToolbar() {
                                                     let botomToolbar = null;
                                                     const allEls = Array.from(document.querySelectorAll('*'));
                                                     for (let el of allEls) {
                                                         if (el.textContent && el.textContent.trim() === 'Center') {
                                                             botomToolbar = el.closest('.ql-resize-toolbar') || el.closest('[class*=\'toolbar\']') || el.parentElement.parentElement || el.parentElement;
                                                             break;
                                                         }
                                                     }
                                                     if (botomToolbar) {
                                                         const subGroups = Array.from(botomToolbar.querySelectorAll('span, div'));
                                                         for (let group of subGroups) {
                                                             const txt = group.textContent ? group.textContent.trim() : '';
                                                             if (txt && !txt.includes('Left') && !txt.includes('Center') && !txt.includes('Right')) {
                                                                 group.style.display = 'none';
                                                                 group.remove();
                                                             }
                                                         }
                                                     }
                                                 }
                                                 quill.on('selection-change', (range) => { if (range) setTimeout(checkAndInjectToolbar, 100); });
                                                 quill.root.addEventListener('click', () => { setTimeout(checkAndInjectToolbar, 100); setTimeout(checkAndInjectToolbar, 300); });
                                                 window.addEventListener('click', () => { setTimeout(checkAndInjectToolbar, 50); setTimeout(checkAndInjectToolbar, 200); setTimeout(checkAndInjectToolbar, 500); }, true);
                                                 setInterval(checkAndInjectToolbar, 500);
                                                 const observer = new MutationObserver((mutations) => {
                                                     for (let m of mutations) {
                                                         if (m.addedNodes.length > 0 || m.type === 'attributes') { checkAndInjectToolbar(); break; }
                                                     }
                                                 });
                                                 observer.observe(document.body, { childList: true, subtree: true, attributes: true, attributeFilter: ['style', 'class', 'display'] });
                                                 $watch('activeQuestionIndex', () => {
                                                     if (quill.root.innerHTML !== activeItemForm.questionsList[activeQuestionIndex].questionText) {
                                                         quill.root.innerHTML = activeItemForm.questionsList[activeQuestionIndex].questionText || '';
                                                     }
                                                 });
                                                 $watch('quizModalOpen', (val) => {
                                                     if (val) {
                                                         setTimeout(() => {
                                                             quill.root.innerHTML = activeItemForm.questionsList[activeQuestionIndex].questionText || '';
                                                         }, 100);
                                                     }
                                                 });
                                             ">
                                            <div x-ref="quillEditor" class="min-h-[160px] p-6 text-xs font-bold text-zinc-800 bg-white border-none outline-none"></div>
                                        </div>
                                    </div>

                                    {{-- Pilihan Opsi Jawaban --}}
                                    <div class="space-y-3 pt-6 border-t border-zinc-100">
                                        <p class="text-[10px] font-black text-zinc-400 uppercase tracking-widest mb-2" x-text="activeItemForm.questionsList[activeQuestionIndex].type === 'true_false' ? 'Pilihan Benar / Salah (Pilih Kunci Jawaban di Kiri)' : 'Pilihan Opsi Jawaban (Pilih Kunci Jawaban di Kiri)'"></p>
                                        
                                        <template x-for="(option, optIndex) in activeItemForm.questionsList[activeQuestionIndex].options" :key="optIndex">
                                            <div class="flex items-center gap-3 p-4 rounded-2xl border transition-all"
                                                 :class="activeItemForm.questionsList[activeQuestionIndex].correctOptionIndex === optIndex ? 'border-primary bg-red-50/10 shadow-2xs' : 'border-zinc-200/80 bg-white hover:border-zinc-300'">
                                                
                                                {{-- Tombol Kunci Jawaban Eksplisit --}}
                                                <button type="button" @click="activeItemForm.questionsList[activeQuestionIndex].correctOptionIndex = optIndex" 
                                                        class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl font-black text-[11px] uppercase tracking-wider transition-all shrink-0 shadow-2xs cursor-pointer"
                                                        :class="activeItemForm.questionsList[activeQuestionIndex].correctOptionIndex === optIndex ? 'bg-primary text-white shadow-md shadow-primary/20 border border-primary' : 'bg-zinc-100 text-zinc-400 border border-zinc-200 hover:bg-zinc-200 hover:text-zinc-600'">
                                                    <span class="material-symbols-outlined text-sm font-extrabold" x-text="activeItemForm.questionsList[activeQuestionIndex].correctOptionIndex === optIndex ? 'check_circle' : 'radio_button_unchecked'"></span>
                                                    <span x-text="activeItemForm.questionsList[activeQuestionIndex].correctOptionIndex === optIndex ? 'Kunci Jawaban' : 'Pilih Kunci'"></span>
                                                </button>

                                                {{-- Option Text Input (Disabled if True/False) --}}
                                                <input type="text" x-model="option.text" :disabled="activeItemForm.questionsList[activeQuestionIndex].type === 'true_false'" :placeholder="`Opsi ${String.fromCharCode(65 + optIndex)}...`" class="flex-1 bg-transparent border-none focus:outline-none font-bold text-xs text-zinc-800 placeholder-zinc-300 disabled:opacity-100 disabled:text-zinc-900">

                                                {{-- Option Image Thumbnail Preview (if any) --}}
                                                <template x-if="option.image">
                                                    <div class="relative w-10 h-10 rounded-lg overflow-hidden border border-zinc-200 bg-zinc-950 shrink-0 group/optimg">
                                                        <img :src="option.image" class="w-full h-full object-cover">
                                                        <button @click="if(option.image.startsWith('blob:')) URL.revokeObjectURL(option.image); option.image = ''" class="absolute inset-0 bg-zinc-900/80 text-white flex items-center justify-center opacity-0 group-hover/optimg:opacity-100 transition-opacity">
                                                            <span class="material-symbols-outlined text-[10px] font-bold">close</span>
                                                        </button>
                                                    </div>
                                                </template>

                                                {{-- Upload Option Image Label Button (Only show if Multiple Choice) --}}
                                                <template x-if="activeItemForm.questionsList[activeQuestionIndex].type === 'multiple_choice'">
                                                    <label title="Sisipkan Gambar Opsi" class="w-8 h-8 rounded-xl bg-zinc-50 border border-zinc-200 text-zinc-400 hover:text-primary hover:border-primary/20 flex items-center justify-center transition-all shrink-0 cursor-pointer m-0">
                                                        <span class="material-symbols-outlined text-xs">image</span>
                                                        <input type="file" accept="image/*" class="hidden" @change="const file = $event.target.files[0]; if(file) { option.image = URL.createObjectURL(file); $event.target.value = ''; }">
                                                    </label>
                                                </template>

                                                {{-- Delete Option Button (Only show if Multiple Choice & options > 2) --}}
                                                <template x-if="activeItemForm.questionsList[activeQuestionIndex].type === 'multiple_choice'">
                                                    <button @click="if(activeItemForm.questionsList[activeQuestionIndex].options.length > 2) activeItemForm.questionsList[activeQuestionIndex].options.splice(optIndex, 1)" title="Hapus Opsi" class="w-8 h-8 rounded-xl bg-red-50 border border-red-100 text-red-500 hover:bg-red-100 flex items-center justify-center transition-all shrink-0 disabled:opacity-30 disabled:hover:bg-red-50" :disabled="activeItemForm.questionsList[activeQuestionIndex].options.length <= 2">
                                                        <span class="material-symbols-outlined text-xs">delete</span>
                                                    </button>
                                                </template>
                                            </div>
                                        </template>

                                        {{-- Tambah Opsi Jawaban Baru (Only show if Multiple Choice) --}}
                                        <template x-if="activeItemForm.questionsList[activeQuestionIndex].type === 'multiple_choice'">
                                            <button @click="activeItemForm.questionsList[activeQuestionIndex].options.push({ text: '', image: '' })" class="w-full py-3.5 border border-dashed border-red-200 bg-white hover:bg-red-50/30 text-primary rounded-2xl font-bold text-[11px] transition-all flex items-center justify-center gap-2 shadow-2xs mt-2">
                                                <span class="material-symbols-outlined text-sm font-bold">add_circle</span>
                                                Tambah Opsi Jawaban Baru
                                            </button>
                                        </template>
                                    </div>

                                    {{-- Question Feedback & Randomize Options --}}
                                    <div class="space-y-4 pt-6 border-t border-zinc-100">
                                        <div class="flex items-center justify-between bg-zinc-50 p-4 rounded-2xl border border-zinc-200/80">
                                            <div class="flex items-center gap-2.5">
                                                <span class="material-symbols-outlined text-base text-zinc-500">shuffle</span>
                                                <div>
                                                    <p class="text-xs font-bold text-zinc-800 mb-0">Acak Urutan Opsi Jawaban Ini</p>
                                                    <p class="text-[10px] text-zinc-500 mb-0 font-medium">Opsi akan diacak posisinya saat dikerjakan oleh siswa</p>
                                                </div>
                                            </div>
                                            <label class="relative inline-flex items-center cursor-pointer m-0">
                                                <input type="checkbox" x-model="activeItemForm.questionsList[activeQuestionIndex].randomizeOptions" class="sr-only peer">
                                                <div class="w-11 h-6 bg-zinc-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-zinc-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                                            </label>
                                        </div>

                                        {{-- Toggle Tampilkan Feedback Jawaban Benar --}}
                                        <div class="flex items-center justify-between bg-zinc-50 p-4 rounded-2xl border border-zinc-200/80">
                                            <div class="flex items-center gap-2.5">
                                                <span class="material-symbols-outlined text-base text-zinc-500">rate_review</span>
                                                <div>
                                                    <p class="text-xs font-bold text-zinc-800 mb-0">Tampilkan Feedback Jawaban Benar</p>
                                                    <p class="text-[10px] text-zinc-500 mb-0 font-medium">Berikan penjelasan atau apresiasi saat siswa menjawab benar</p>
                                                </div>
                                            </div>
                                            <label class="relative inline-flex items-center cursor-pointer m-0">
                                                <input type="checkbox" x-model="activeItemForm.questionsList[activeQuestionIndex].hasCorrectFeedback" class="sr-only peer">
                                                <div class="w-11 h-6 bg-zinc-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-zinc-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                                            </label>
                                        </div>

                                        {{-- Area Feedback (Muncul jika hasCorrectFeedback aktif) --}}
                                        <template x-if="activeItemForm.questionsList[activeQuestionIndex].hasCorrectFeedback">
                                            <div class="space-y-4 pt-2">
                                                {{-- Correct Feedback Textarea --}}
                                                <div class="bg-emerald-50/20 border border-emerald-200/60 rounded-2xl p-5 space-y-3">
                                                    <div class="flex items-center gap-2 text-emerald-600 font-bold text-xs uppercase tracking-wider">
                                                        <span class="material-symbols-outlined text-base">check_circle</span>
                                                        Feedback Jawaban Benar
                                                    </div>
                                                    <textarea x-model="activeItemForm.questionsList[activeQuestionIndex].correctFeedback" rows="2" placeholder="Penjelasan/pujian jika siswa menjawab benar..." class="w-full bg-white border border-emerald-200/80 rounded-xl p-3 font-bold text-xs text-zinc-800 placeholder-zinc-400 focus:outline-none focus:border-emerald-500 shadow-2xs"></textarea>
                                                </div>

                                                {{-- Toggle Tampilkan Feedback Jawaban Salah --}}
                                                <div class="flex items-center justify-between bg-zinc-50 p-4 rounded-2xl border border-zinc-200/80">
                                                    <div class="flex items-center gap-2.5">
                                                        <span class="material-symbols-outlined text-base text-zinc-500">rule_folder</span>
                                                        <div>
                                                            <p class="text-xs font-bold text-zinc-800 mb-0">Tampilkan Feedback Jawaban Salah</p>
                                                            <p class="text-[10px] text-zinc-500 mb-0 font-medium">Berikan koreksi atau petunjuk materi saat siswa menjawab salah</p>
                                                        </div>
                                                    </div>
                                                    <label class="relative inline-flex items-center cursor-pointer m-0">
                                                        <input type="checkbox" x-model="activeItemForm.questionsList[activeQuestionIndex].hasIncorrectFeedback" class="sr-only peer">
                                                        <div class="w-11 h-6 bg-zinc-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-zinc-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                                                    </label>
                                                </div>

                                                {{-- Incorrect Feedback Textarea (Muncul jika hasIncorrectFeedback aktif) --}}
                                                <template x-if="activeItemForm.questionsList[activeQuestionIndex].hasIncorrectFeedback">
                                                    <div class="bg-red-50/20 border border-red-200/60 rounded-2xl p-5 space-y-3 animate-fadeIn">
                                                        <div class="flex items-center gap-2 text-red-600 font-bold text-xs uppercase tracking-wider">
                                                            <span class="material-symbols-outlined text-base">cancel</span>
                                                            Feedback Jawaban Salah
                                                        </div>
                                                        <textarea x-model="activeItemForm.questionsList[activeQuestionIndex].incorrectFeedback" rows="2" placeholder="Koreksi/penjelasan jika siswa menjawab salah..." class="w-full bg-white border border-red-200/80 rounded-xl p-3 font-bold text-xs text-zinc-800 placeholder-zinc-400 focus:outline-none focus:border-red-500 shadow-2xs"></textarea>
                                                    </div>
                                                </template>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </div>

                            {{-- Bottom Actions of Question Builder --}}
                            <div class="flex items-center justify-between pt-6 border-t border-zinc-100">
                                <button @click="if(activeItemForm.questionsList.length > 1) { activeItemForm.questionsList.splice(activeQuestionIndex, 1); if(activeQuestionIndex >= activeItemForm.questionsList.length) activeQuestionIndex = activeItemForm.questionsList.length - 1; }" class="flex items-center gap-1.5 px-4 py-3 rounded-2xl border border-red-100 text-red-600 hover:bg-red-50 text-xs font-bold transition-all shadow-2xs disabled:opacity-30 disabled:hover:bg-transparent" :disabled="activeItemForm.questionsList.length <= 1">
                                    <span class="material-symbols-outlined text-sm">delete</span>
                                    Hapus Soal Ini
                                </button>
                                <button @click="activeItemForm.questionsList.push({ type: 'multiple_choice', questionText: '', contentBlocks: [], options: [{ text: '', image: '' }, { text: '', image: '' }, { text: '', image: '' }, { text: '', image: '' }], correctOptionIndex: 0, correctFeedback: '', incorrectFeedback: '', randomizeOptions: true, hasCorrectFeedback: true, hasIncorrectFeedback: false }); activeQuestionIndex = activeItemForm.questionsList.length - 1;" class="px-6 py-4 rounded-2xl bg-zinc-950 text-white hover:bg-zinc-800 text-xs font-black uppercase tracking-widest transition-all shadow-lg flex items-center gap-2 active:scale-95">
                                    <span class="material-symbols-outlined text-base">add_box</span>
                                    <span x-text="`TAMBAH SOAL BERIKUTNYA (SOAL #${activeItemForm.questionsList.length + 1})`"></span>
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Footer Bar --}}
                    <div class="px-6 py-3.5 bg-zinc-50 border-t border-zinc-200 flex justify-between items-center shrink-0">
                        <div class="flex items-center gap-3">
                            <button @click="quizModalOpen = false" class="px-5 py-2.5 rounded-xl border border-zinc-200 text-zinc-600 font-bold text-xs uppercase tracking-widest hover:bg-zinc-100 transition-all active:scale-95 shadow-2xs">BATAL</button>
                            
                            {{-- Tombol Simpan ke Draf Non-Closing --}}
                            <button type="button" @click="saveDraft()" 
                                    class="px-5 py-2.5 rounded-xl border border-zinc-300 bg-white hover:bg-zinc-100 text-zinc-700 font-black text-xs uppercase tracking-widest transition-all active:scale-95 shadow-2xs flex items-center gap-1.5"
                                    :disabled="isSavingDraft">
                                <span class="material-symbols-outlined text-base" :class="isSavingDraft ? 'animate-spin' : ''" x-text="isSavingDraft ? 'sync' : 'save_as'"></span>
                                <span x-text="isSavingDraft ? 'Menyimpan...' : 'Simpan ke Draf'"></span>
                            </button>
                        </div>
                        <div class="flex items-center gap-5">
                            <div class="text-right">
                                <p class="text-[9px] font-black text-primary uppercase tracking-widest mb-0.5">STATUS DRAF</p>
                                <p class="text-xs font-bold text-zinc-700 mb-0" x-text="draftStatusText || `${activeItemForm.questionsList.length} Pertanyaan Disimpan`"></p>
                            </div>
                            <button @click="saveQuizModal()" class="px-6 py-2.5 rounded-xl bg-primary text-white font-black text-xs uppercase tracking-widest hover:bg-primary/90 transition-all active:scale-95 shadow-md shadow-primary/20 flex items-center gap-1.5">
                                <span class="material-symbols-outlined text-base">save</span>
                                SIMPAN KUIS KE SILABUS
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Nudge Toast Notification (Pengingat Proaktif 60 Detik) --}}
            <div x-show="showNudgeToast" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 translate-y-4"
                 class="fixed bottom-8 right-8 z-[250] max-w-md bg-white rounded-3xl p-6 shadow-2xl border border-red-100 flex items-start gap-4" x-cloak>
                <div class="w-12 h-12 rounded-2xl bg-red-50 text-primary border border-red-100 flex items-center justify-center shrink-0 shadow-inner">
                    <span class="material-symbols-outlined text-2xl animate-bounce">tips_and_updates</span>
                </div>
                <div class="space-y-2 flex-1 min-w-0">
                    <div class="flex items-center justify-between">
                        <h4 class="text-xs font-black text-zinc-900 uppercase tracking-wider">Tips Keamanan Data Kuis</h4>
                        <button @click="showNudgeToast = false" class="text-zinc-400 hover:text-zinc-600 transition-colors">
                            <span class="material-symbols-outlined text-sm">close</span>
                        </button>
                    </div>
                    <p class="text-[11px] font-semibold text-zinc-600 leading-relaxed mb-0">
                        Jangan lupa menekan tombol <strong class="text-primary">"Simpan ke Draf"</strong> di bawah secara berkala. Hal ini untuk mencegah hasil ketikan evaluasi Anda hilang jika koneksi terputus atau sesi login berakhir.
                    </p>
                    <div class="pt-2 flex items-center gap-3">
                        <button type="button" @click="saveDraft(); showNudgeToast = false;" class="px-4 py-2 rounded-xl bg-primary text-white text-[10px] font-black uppercase tracking-widest hover:bg-primary/90 transition-all shadow-md shadow-primary/20 active:scale-95">
                            Simpan Draf Sekarang
                        </button>
                        <button type="button" @click="showNudgeToast = false" class="px-4 py-2 rounded-xl bg-zinc-100 text-zinc-600 text-[10px] font-black uppercase tracking-widest hover:bg-zinc-200 transition-all active:scale-95">
                            Mengerti
                        </button>
                    </div>
                </div>
            </div>

            {{-- Modal Detail Basic (High-Fidelity Vern-Style) --}}
            <div x-show="detailModalOpen" class="fixed inset-0 z-[200] flex items-center justify-center p-4 bg-zinc-900/60 backdrop-blur-sm" x-cloak>
                <div class="bg-white w-full max-w-5xl rounded-[2.5rem] shadow-2xl overflow-hidden flex flex-col border border-zinc-100 my-auto max-h-[90vh]">
                    {{-- Header Bar --}}
                    <div class="px-6 py-4 border-b border-zinc-100 flex justify-between items-start bg-white shrink-0">
                        <div class="space-y-1">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-xl flex items-center justify-center font-bold text-xs shadow-inner shrink-0"
                                     :class="activeItemDetail.type === 'video' ? 'bg-red-50 text-primary border border-red-100' : 'bg-amber-50 text-amber-600 border border-amber-100'">
                                    <span class="material-symbols-outlined text-base" x-text="activeItemDetail.type === 'video' ? 'play_circle' : 'quiz'"></span>
                                </div>
                                <h3 class="text-xl font-black uppercase tracking-tight text-zinc-900 mb-0" x-text="activeItemDetail.title"></h3>
                            </div>
                            <div class="flex items-center gap-2.5 pt-0.5">
                                <span class="px-2.5 py-0.5 rounded-lg text-[9px] font-black uppercase tracking-widest border shadow-2xs"
                                      :class="activeItemDetail.type === 'video' ? 'bg-red-50 text-primary border-red-200' : 'bg-amber-50 text-amber-600 border-amber-200'"
                                      x-text="activeItemDetail.type === 'video' ? 'Materi Video' : 'Kuis Evaluasi'"></span>
                                <span class="text-[11px] font-bold text-zinc-400" x-text="activeItemDetail.type === 'video' ? (activeItemDetail.meta ? activeItemDetail.meta.split(' • ')[0] : 'Video MP4') : activeItemDetail.meta"></span>
                            </div>
                        </div>
                        <button @click="detailModalOpen = false" class="w-8 h-8 rounded-full bg-zinc-50 border border-zinc-200 text-zinc-400 flex items-center justify-center hover:bg-zinc-100 hover:text-zinc-900 transition-all shadow-sm shrink-0">
                            <span class="material-symbols-outlined text-base">close</span>
                        </button>
                    </div>

                    {{-- Modal Body --}}
                    <div class="p-6 space-y-6 bg-white overflow-y-auto max-h-[80vh]">
                        {{-- Spesifik Video Info --}}
                        <template x-if="activeItemDetail.type === 'video'">
                            <div class="space-y-6">
                                {{-- Kotak Pemutar Video --}}
                                <div class="space-y-3">
                                    <label class="text-[10px] font-black text-zinc-400 uppercase tracking-widest block ml-1">Video Pemutaran</label>
                                    <div class="rounded-3xl overflow-hidden bg-zinc-950 shadow-xl border border-zinc-800 flex items-center justify-center min-h-[240px] relative">
                                        <template x-if="activeItemDetail.videoPreviewUrl && !activeItemDetail.videoPreviewUrl.startsWith('#')">
                                            <video :src="activeItemDetail.videoPreviewUrl" controls class="w-full h-full object-contain max-h-[320px] bg-zinc-950"></video>
                                        </template>
                                        <template x-if="!activeItemDetail.videoPreviewUrl || activeItemDetail.videoPreviewUrl.startsWith('#')">
                                            <div class="text-center p-8 space-y-3">
                                                <div class="w-16 h-16 rounded-full bg-zinc-900 text-zinc-600 flex items-center justify-center mx-auto border border-zinc-800">
                                                    <span class="material-symbols-outlined text-3xl">videocam_off</span>
                                                </div>
                                                <p class="text-xs font-bold text-zinc-400">Video belum diunggah atau menggunakan tautan eksternal</p>
                                            </div>
                                        </template>
                                    </div>
                                </div>

                                {{-- Deskripsi / Rangkuman (Diletakkan di bawah video sesuai instruksi) --}}
                                <div class="space-y-3">
                                    <label class="text-[10px] font-black text-zinc-400 uppercase tracking-widest block ml-1">Deskripsi / Rangkuman Materi</label>
                                    <div class="p-6 bg-zinc-50 border border-zinc-200/80 rounded-3xl text-xs font-medium text-zinc-700 leading-relaxed shadow-inner" x-text="activeItemDetail.summary"></div>
                                </div>

                                {{-- Spesifikasi Video (Hanya Ukuran & Durasi, tanpa nama file dan tanpa URL sumber) --}}
                                <div class="space-y-3">
                                    <label class="text-[10px] font-black text-zinc-400 uppercase tracking-widest block ml-1">Spesifikasi Video</label>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div class="bg-zinc-50 border border-zinc-200/80 rounded-2xl p-5 shadow-2xs">
                                            <p class="text-[10px] font-black text-zinc-400 uppercase tracking-widest mb-1">Ukuran File</p>
                                            <p class="text-xs font-bold text-zinc-900" x-text="activeItemDetail.videoFilesize || '45 MB'"></p>
                                        </div>
                                        <div class="bg-zinc-50 border border-zinc-200/80 rounded-2xl p-5 shadow-2xs">
                                            <p class="text-[10px] font-black text-zinc-400 uppercase tracking-widest mb-1">Durasi Pemutaran</p>
                                            <p class="text-xs font-bold text-zinc-900" x-text="activeItemDetail.videoDuration ? `${activeItemDetail.videoDuration} Menit` : '12:45 Menit'"></p>
                                        </div>
                                    </div>
                                </div>

                                {{-- Dokumen Pendukung / Lampiran (Preview khusus PDF, selain PDF langsung Unduh) --}}
                                <template x-if="activeItemDetail.attachments && activeItemDetail.attachments.length > 0">
                                    <div class="space-y-3">
                                        <label class="text-[10px] font-black text-zinc-400 uppercase tracking-widest block ml-1">Dokumen Pendukung / Lampiran</label>
                                        <div class="space-y-3">
                                            <template x-for="(att, attIdx) in activeItemDetail.attachments" :key="attIdx">
                                                <div class="bg-zinc-900 text-white rounded-3xl p-5 flex items-center justify-between shadow-xl border border-zinc-800 gap-4">
                                                    <div class="flex items-center gap-4 min-w-0">
                                                        <div class="w-12 h-12 rounded-2xl bg-red-500/20 text-primary flex items-center justify-center border border-red-500/30 shrink-0">
                                                            <span class="material-symbols-outlined text-2xl" x-text="(att.filename || '').toLowerCase().endsWith('.pdf') ? 'picture_as_pdf' : 'folder_zip'"></span>
                                                        </div>
                                                        <div class="min-w-0">
                                                            <p class="text-xs font-bold truncate mb-0.5" x-text="att.filename"></p>
                                                            <p class="text-[10px] font-medium text-zinc-400 mb-0" x-text="`Ukuran: ${att.filesize || '2.4 MB'} • ${(att.filename || '').toLowerCase().endsWith('.pdf') ? 'Tersedia untuk di-preview' : 'Tersedia untuk diunduh'}`"></p>
                                                        </div>
                                                    </div>
                                                    <a :href="att.url && att.url !== '#' ? att.url : `https://sig.academy/storage/attachments/${att.filename || 'dokumen.pdf'}`" 
                                                       :target="(att.filename || '').toLowerCase().endsWith('.pdf') ? '_blank' : '_self'"
                                                       :download="!(att.filename || '').toLowerCase().endsWith('.pdf') ? (att.filename || 'download') : false"
                                                       class="px-4 py-2 rounded-xl bg-zinc-800 hover:bg-zinc-700 text-zinc-200 text-[11px] font-bold border border-zinc-700 transition-all shrink-0 shadow-sm flex items-center gap-1.5">
                                                        <span class="material-symbols-outlined text-sm" x-text="(att.filename || '').toLowerCase().endsWith('.pdf') ? 'open_in_new' : 'download'"></span>
                                                        <span x-text="(att.filename || '').toLowerCase().endsWith('.pdf') ? 'Preview PDF' : 'Unduh File'"></span>
                                                    </a>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </template>

                        {{-- Spesifik Kuis Info --}}
                        <template x-if="activeItemDetail.type === 'quiz'">
                            <div class="space-y-6">
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                    <div class="bg-amber-50/30 border border-amber-200/80 rounded-3xl p-5 flex flex-col justify-center shadow-sm">
                                        <div class="w-10 h-10 rounded-2xl bg-amber-500 text-white flex items-center justify-center shadow-md shadow-amber-500/20 mb-3">
                                            <span class="material-symbols-outlined text-xl">format_list_numbered</span>
                                        </div>
                                        <p class="text-[10px] font-black text-amber-600 uppercase tracking-widest mb-0.5">Jumlah Soal</p>
                                        <p class="text-base font-black text-zinc-900 mb-0" x-text="`${activeItemDetail.questionsList ? activeItemDetail.questionsList.length : activeItemDetail.questions} Butir Soal`"></p>
                                    </div>
                                    <div class="bg-emerald-50/30 border border-emerald-200/80 rounded-3xl p-5 flex flex-col justify-center shadow-sm">
                                        <div class="w-10 h-10 rounded-2xl bg-emerald-500 text-white flex items-center justify-center shadow-md shadow-emerald-500/20 mb-3">
                                            <span class="material-symbols-outlined text-xl">verified</span>
                                        </div>
                                        <p class="text-[10px] font-black text-emerald-600 uppercase tracking-widest mb-0.5">Passing Grade</p>
                                        <p class="text-base font-black text-zinc-900 mb-0" x-text="`${activeItemDetail.passingGrade || 75}% Minimum`"></p>
                                    </div>
                                    <div class="bg-blue-50/30 border border-blue-200/80 rounded-3xl p-5 flex flex-col justify-center shadow-sm">
                                        <div class="w-10 h-10 rounded-2xl bg-blue-500 text-white flex items-center justify-center shadow-md shadow-blue-500/20 mb-3">
                                            <span class="material-symbols-outlined text-xl">timer</span>
                                        </div>
                                        <p class="text-[10px] font-black text-blue-600 uppercase tracking-widest mb-0.5">Durasi Waktu</p>
                                        <p class="text-base font-black text-zinc-900 mb-0" x-text="activeItemDetail.isInfinityDuration ? 'Tanpa Batas' : `${activeItemDetail.durationMinutes || 15} Menit`"></p>
                                    </div>
                                    <div class="bg-purple-50/30 border border-purple-200/80 rounded-3xl p-5 flex flex-col justify-center shadow-sm">
                                        <div class="w-10 h-10 rounded-2xl bg-purple-500 text-white flex items-center justify-center shadow-md shadow-purple-500/20 mb-3">
                                            <span class="material-symbols-outlined text-xl">tune</span>
                                        </div>
                                        <p class="text-[10px] font-black text-purple-600 uppercase tracking-widest mb-1">Pengaturan Kuis</p>
                                        <div class="space-y-1 text-xs font-bold text-zinc-800">
                                            <div class="flex items-center justify-between border-b border-purple-100/50 pb-1">
                                                <span class="text-zinc-500 font-medium">Acak Soal:</span>
                                                <span x-text="activeItemDetail.shuffle ? 'Ya (Diacak)' : 'Tidak (Urut)'"></span>
                                            </div>
                                            <div class="flex items-center justify-between pt-0.5">
                                                <span class="text-zinc-500 font-medium">Tampilkan Kunci:</span>
                                                <span x-text="activeItemDetail.showCorrectAnswer ? 'Ya (Buka)' : 'Tidak (Tutup)'"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Deskripsi / Rangkuman Kuis --}}
                                <div class="space-y-3">
                                    <label class="text-[10px] font-black text-zinc-400 uppercase tracking-widest block ml-1">Deskripsi / Rangkuman Kuis</label>
                                    <div class="p-6 bg-zinc-50 border border-zinc-200/80 rounded-3xl text-xs font-medium text-zinc-700 leading-relaxed shadow-inner" x-text="activeItemDetail.summary"></div>
                                </div>

                                {{-- Daftar Butir Soal & Kunci Jawaban --}}
                                <template x-if="activeItemDetail.questionsList && activeItemDetail.questionsList.length > 0">
                                    <div class="space-y-4 pt-4 border-t border-zinc-100">
                                        <h4 class="text-xs font-black uppercase tracking-widest text-zinc-400 mb-3 flex items-center gap-2">
                                            <span class="material-symbols-outlined text-base">quiz</span> 
                                            Daftar Pertanyaan & Kunci Jawaban
                                        </h4>
                                        <div class="space-y-6">
                                            <template x-for="(question, qIdx) in activeItemDetail.questionsList" :key="qIdx">
                                                <div class="p-6 bg-zinc-50/50 border border-zinc-200/80 rounded-3xl space-y-5 shadow-sm">
                                                    <div class="flex items-center justify-between gap-4">
                                                        <div class="flex items-center gap-2">
                                                            <span class="px-3 py-1 bg-amber-100 text-amber-800 text-[10px] font-black uppercase tracking-widest rounded-lg" x-text="`Soal #${qIdx + 1}`"></span>
                                                            <span class="px-2.5 py-1 bg-purple-100 text-purple-800 text-[10px] font-black uppercase tracking-widest rounded-lg" x-text="question.randomizeOptions ? 'Acak Opsi: Ya' : 'Acak Opsi: Tidak'"></span>
                                                        </div>
                                                        <span class="text-[10px] font-bold text-zinc-400 uppercase tracking-wider" x-text="question.type === 'multiple_choice' ? 'Pilihan Ganda' : 'Tipe Lainnya'"></span>
                                                    </div>
                                                    {{-- Teks Pertanyaan --}}
                                                    <div class="text-xs font-bold text-zinc-900 leading-relaxed prose prose-sm max-w-none" x-html="question.questionText"></div>
                                                    
                                                    {{-- Blok Gambar Jika Ada --}}
                                                    <template x-if="question.contentBlocks && question.contentBlocks.length > 0">
                                                        <div class="grid grid-cols-2 gap-2 my-2">
                                                            <template x-for="(block, bIdx) in question.contentBlocks" :key="bIdx">
                                                                <template x-if="block.type === 'image'">
                                                                    <img :src="block.url" class="rounded-xl max-h-40 object-cover border border-zinc-200" alt="Question Image" />
                                                                </template>
                                                            </template>
                                                        </div>
                                                    </template>

                                                    {{-- Pilihan Jawaban --}}
                                                    <div class="space-y-2">
                                                        <p class="text-[10px] font-black text-zinc-400 uppercase tracking-widest mb-1">Pilihan Jawaban:</p>
                                                        <template x-for="(opt, optIdx) in question.options" :key="optIdx">
                                                            <div class="flex items-center gap-3 p-3 rounded-xl border text-xs font-bold transition-all"
                                                                 :class="optIdx === question.correctOptionIndex ? 'bg-emerald-50 border-emerald-300 text-emerald-900 shadow-2xs' : 'bg-white border-zinc-200 text-zinc-600'">
                                                                <span class="material-symbols-outlined text-base shrink-0" 
                                                                      :class="optIdx === question.correctOptionIndex ? 'text-emerald-600' : 'text-zinc-300'"
                                                                      x-text="optIdx === question.correctOptionIndex ? 'check_circle' : 'radio_button_unchecked'"></span>
                                                                <div class="flex-1 flex items-center gap-3 min-w-0">
                                                                    <template x-if="opt.image">
                                                                        <img :src="opt.image" class="w-10 h-10 rounded-lg object-cover border border-zinc-200 shrink-0" alt="Option Image" />
                                                                    </template>
                                                                    <span class="truncate" x-text="opt.text"></span>
                                                                </div>
                                                                <template x-if="optIdx === question.correctOptionIndex">
                                                                    <span class="px-2 py-0.5 bg-emerald-200/50 text-emerald-800 text-[9px] font-black uppercase tracking-widest rounded shrink-0">Kunci Jawaban</span>
                                                                </template>
                                                            </div>
                                                        </template>
                                                    </div>

                                                    {{-- Feedback Penjelasan --}}
                                                    <template x-if="(question.hasCorrectFeedback && question.correctFeedback) || (question.hasIncorrectFeedback && question.incorrectFeedback)">
                                                        <div class="mt-4 p-5 bg-white border border-zinc-200 rounded-2xl space-y-3 text-[11px] shadow-2xs">
                                                            <template x-if="question.hasCorrectFeedback && question.correctFeedback">
                                                                <div>
                                                                    <span class="font-black text-emerald-600 uppercase tracking-wider block mb-1">Feedback Jawaban Benar:</span>
                                                                    <p class="text-zinc-600 font-medium mb-0 leading-relaxed" x-text="question.correctFeedback"></p>
                                                                </div>
                                                            </template>
                                                            <template x-if="question.hasIncorrectFeedback && question.incorrectFeedback">
                                                                <div :class="(question.hasCorrectFeedback && question.correctFeedback) ? 'pt-3 border-t border-zinc-100' : ''">
                                                                    <span class="font-black text-red-600 uppercase tracking-wider block mb-1">Feedback Jawaban Salah:</span>
                                                                    <p class="text-zinc-600 font-medium mb-0 leading-relaxed" x-text="question.incorrectFeedback"></p>
                                                                </div>
                                                            </template>
                                                        </div>
                                                    </template>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </template>
</div>
@endsection

@section('scripts')
<script>
function curriculumBuilder() {
    return {
        chapters: {!! json_encode($blueprint->curriculum_structure ?? [
            [
                'id' => 'chapter-1',
                'title' => 'DASAR FFT',
                'summary' => 'Fokus pada pemahaman fundamental konversi domain waktu ke frekuensi. Menjelaskan parameter resolusi, windowing, dan aliasing yang kritikal bagi akurasi diagnosa awal teknisi di lapangan.',
                'items' => [
                    ['id' => 'item-1', 'title' => '1.1 Prinsip Fundamental Getaran', 'type' => 'video', 'meta' => 'Video MP4 • 45MB', 'url' => '#'],
                    [
                        'id' => 'item-2', 
                        'title' => '1.2 Kuis Evaluasi Dasar', 
                        'type' => 'quiz', 
                        'meta' => 'Kuis • 10 Pertanyaan (15 Menit)', 
                        'questions' => 10,
                        'durationMinutes' => 15,
                        'isInfinityDuration' => false,
                        'passingGrade' => 75,
                        'shuffle' => true,
                        'showCorrectAnswer' => true,
                        'summary' => 'Kuis evaluasi pemahaman mendalam terkait materi bab ini dengan sistem pengacakan otomatis LMS.',
                        'questionsList' => [
                            [
                                'type' => 'multiple_choice',
                                'questionText' => '<p>Perhatikan grafik anomali vibrasi pada main drive kiln berikut ini. Berapakah batas normal vibrasi velocity saat operasi beban penuh menurut standar ISO 10816-3?</p>',
                                'contentBlocks' => [
                                    ['type' => 'image', 'url' => 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?auto=format&fit=crop&w=800&q=80']
                                ],
                                'options' => [
                                    ['text' => '4.5 mm/s', 'image' => ''],
                                    ['text' => '7.1 mm/s', 'image' => ''],
                                    ['text' => '12.0 mm/s', 'image' => '']
                                ],
                                'correctOptionIndex' => 1,
                                'correctFeedback' => 'Jawaban Anda tepat! Batas normal vibrasi velocity pada kondisi beban penuh untuk mesin grup 1 kelas berat adalah 7.1 mm/s sesuai standar ISO 10816-3.',
                                'incorrectFeedback' => 'Jawaban Anda kurang tepat. Jawaban yang benar adalah 7.1 mm/s. Mengapa bisa begitu? Menurut tabel standar ISO 10816-3 untuk mesin grup 1 kelas berat dengan pondasi kaku, batas zona hijau (normal operasi beban penuh) adalah 7.1 mm/s. Nilai 4.5 mm/s adalah batas untuk mesin kecil, sedangkan 12.0 mm/s sudah masuk dalam kategori alarm/kritis (zona C/D) yang memerlukan tindakan perbaikan.',
                                'randomizeOptions' => true,
                                'hasCorrectFeedback' => true,
                                'hasIncorrectFeedback' => true
                            ],
                            [
                                'type' => 'multiple_choice',
                                'questionText' => '<p>Apa fungsi utama dari penerapan analisis spektrum Fast Fourier Transform (FFT) pada pemantauan motor fan kiln?</p>',
                                'contentBlocks' => [],
                                'options' => [
                                    ['text' => 'Mengubah sinyal getaran dari domain waktu ke domain frekuensi untuk mengidentifikasi sumber spesifik kerusakan mesin', 'image' => ''],
                                    ['text' => 'Menurunkan temperatur pelumas pada bearing casing secara otomatis', 'image' => ''],
                                    ['text' => 'Meningkatkan kecepatan putaran impeler ID Fan melebihi kapasitas desain', 'image' => '']
                                ],
                                'correctOptionIndex' => 0,
                                'correctFeedback' => 'Benar sekali! Analisis FFT berfungsi mengonversi sinyal domain waktu ke frekuensi, sehingga teknisi dapat membedakan unbalance, misalignment, atau kerusakan bearing berdasarkan puncak frekuensinya.',
                                'incorrectFeedback' => 'Jawaban Anda kurang tepat. Jawaban yang benar adalah mengubah sinyal getaran dari domain waktu ke domain frekuensi untuk mengidentifikasi sumber spesifik kerusakan mesin. Mengapa bisa begitu? Algoritma FFT murni merupakan metode pengolahan sinyal matematis untuk keperluan diagnosa spektrum getaran, bukan alat mekanis atau sistem kontrol otomatis. Oleh karena itu, FFT tidak dapat memodifikasi parameter fisik di lapangan seperti menurunkan suhu pelumas ataupun menaikkan kecepatan putar impeler.',
                                'randomizeOptions' => true,
                                'hasCorrectFeedback' => true,
                                'hasIncorrectFeedback' => true
                            ],
                            [
                                'type' => 'multiple_choice',
                                'questionText' => '<p>Jenis sensor vibrasi manakah yang paling tepat dan sensitif digunakan untuk mengukur getaran frekuensi tinggi pada rolling element bearing?</p>',
                                'contentBlocks' => [],
                                'options' => [
                                    ['text' => 'Proximity Eddy Current Probe', 'image' => ''],
                                    ['text' => 'Piezoelectric Accelerometer', 'image' => ''],
                                    ['text' => 'Seismic Velocity Transducer', 'image' => '']
                                ],
                                'correctOptionIndex' => 1,
                                'correctFeedback' => 'Tepat! Piezoelectric Accelerometer memiliki respon frekuensi yang sangat tinggi (hingga melebihi 10 kHz), menjadikannya pilihan utama untuk mendeteksi frekuensi cacat bearing (BPFO, BPFI, BSF).',
                                'incorrectFeedback' => 'Jawaban Anda kurang tepat. Jawaban yang benar adalah Piezoelectric Accelerometer. Mengapa bisa begitu? Cacat pada rolling element bearing menghasilkan sinyal impak pada frekuensi yang sangat tinggi (di atas 1 kHz hingga 20 kHz). Piezoelectric Accelerometer memiliki respon frekuensi alami yang sangat lebar untuk menangkap sinyal tersebut. Di sisi lain, Proximity Probe didesain untuk frekuensi rendah (pengukuran displacement poros turbin), dan Seismic Velocity Transducer mengalami penurunan sensitivitas secara drastis pada frekuensi di atas 1000 Hz.',
                                'randomizeOptions' => true,
                                'hasCorrectFeedback' => true,
                                'hasIncorrectFeedback' => true
                            ],
                            [
                                'type' => 'multiple_choice',
                                'questionText' => '<p>Indikasi utama terjadinya fenomena unbalance pada impeler ID Fan kiln berdasarkan spektrum getaran FFT adalah...</p>',
                                'contentBlocks' => [],
                                'options' => [
                                    ['text' => 'Munculnya puncak dominan pada frekuensi 1X RPM (1X running speed) dengan amplitudo tinggi arah radial', 'image' => ''],
                                    ['text' => 'Munculnya sub-harmonik pada frekuensi 0.5X RPM', 'image' => ''],
                                    ['text' => 'Puncak harmonik yang merata dari 1X hingga 10X RPM', 'image' => '']
                                ],
                                'correctOptionIndex' => 0,
                                'correctFeedback' => 'Jawaban Anda benar. Unbalance selalu menghasilkan gaya sentrifugal yang bermanifestasi sebagai getaran sinusoidal murni pada frekuensi 1X putaran poros (1X RPM) di arah radial.',
                                'incorrectFeedback' => 'Jawaban Anda kurang tepat. Jawaban yang benar adalah munculnya puncak dominan pada frekuensi 1X RPM (1X running speed) dengan amplitudo tinggi arah radial. Mengapa bisa begitu? Ketidakseimbangan massa (unbalance) selalu menghasilkan gaya sentrifugal searah putaran poros (1X RPM). Jika muncul frekuensi sub-harmonik 0.5X RPM, hal tersebut merupakan karakteristik dari instabilitas pelumas (oil whirl/whip) pada journal bearing. Sedangkan jika muncul puncak harmonik merata dari 1X hingga 10X RPM, itu merupakan indikasi dari kelonggaran mekanis (looseness), bukan unbalance.',
                                'randomizeOptions' => true,
                                'hasCorrectFeedback' => true,
                                'hasIncorrectFeedback' => true
                            ],
                            [
                                'type' => 'multiple_choice',
                                'questionText' => '<p>Parameter getaran utama yang diukur oleh proximity probe pada turbin generator pabrik semen adalah...</p>',
                                'contentBlocks' => [],
                                'options' => [
                                    ['text' => 'Akselerasi (g) dari casing mesin', 'image' => ''],
                                    ['text' => 'Kecepatan getaran (mm/s) pada pondasi', 'image' => ''],
                                    ['text' => 'Perpindahan relatif (displacement dalam mikron) antara poros berputar dan journal bearing', 'image' => '']
                                ],
                                'correctOptionIndex' => 2,
                                'correctFeedback' => 'Sangat tepat! Proximity probe mengukur displacement (jarak relatif) poros terhadap bantalan, yang sangat krusial untuk memantau ketebalan film pelumas dan pergerakan poros turbin.',
                                'incorrectFeedback' => 'Jawaban Anda kurang tepat. Jawaban yang benar adalah perpindahan relatif (displacement dalam mikron) antara poros berputar dan journal bearing. Mengapa bisa begitu? Proximity probe merupakan sensor non-kontak berbasis arus eddy yang dirancang khusus untuk mengukur celah (gap) dan pergerakan fisik poros secara langsung (displacement). Sensor ini tidak mengukur getaran pada casing atau pondasi luar mesin yang umumnya menggunakan besaran akselerasi (g) untuk frekuensi tinggi atau kecepatan (mm/s) untuk frekuensi menengah.',
                                'randomizeOptions' => true,
                                'hasCorrectFeedback' => true,
                                'hasIncorrectFeedback' => true
                            ],
                            [
                                'type' => 'multiple_choice',
                                'questionText' => '<p>Langkah awal yang wajib dilakukan teknisi sebelum memasang sensor akselerometer dengan metode magnetic base pada area bearing adalah...</p>',
                                'contentBlocks' => [],
                                'options' => [
                                    ['text' => 'Mengoleskan pelumas gemuk (grease) dalam jumlah sangat banyak di seluruh permukaan sensor', 'image' => ''],
                                    ['text' => 'Membersihkan permukaan ukur dari kotoran, kerak semen, atau cat mengelupas agar kontak magnet menempel sempurna', 'image' => ''],
                                    ['text' => 'Memanaskan sensor. Menggunakan heat gun hingga menyamai suhu casing', 'image' => '']
                                ],
                                'correctOptionIndex' => 1,
                                'correctFeedback' => 'Benar! Kontak mekanis yang solid sangat penting. Kotoran atau kerak semen akan menciptakan celah udara (air gap) yang meredam transmisi frekuensi tinggi dan menurunkan frekuensi resonansi mounting.',
                                'incorrectFeedback' => 'Jawaban Anda kurang tepat. Jawaban yang benar adalah membersihkan permukaan ukur dari kotoran, kerak semen, atau cat mengelupas agar kontak magnet menempel sempurna. Mengapa bisa begitu? Transmisi sinyal getaran yang akurat membutuhkan kontak mekanis langsung yang solid antara magnet dan casing mesin. Kotoran atau kerak semen akan menimbulkan celah udara (air gap) yang meredam sinyal frekuensi tinggi. Adapun mengoleskan pelumas berlebih justru dapat membuat sensor tidak stabil, dan memanaskan sensor dengan heat gun sangat dilarang karena dapat merusak komponen kristal piezoelektrik di dalamnya.',
                                'randomizeOptions' => true,
                                'hasCorrectFeedback' => true,
                                'hasIncorrectFeedback' => true
                            ],
                            [
                                'type' => 'multiple_choice',
                                'questionText' => '<p>Penyebab utama munculnya frekuensi dominan 2X RPM pada spektrum getaran motor pompa hidrolik raw mill disertai getaran aksial yang tinggi adalah...</p>',
                                'contentBlocks' => [],
                                'options' => [
                                    ['text' => 'Misalignment (ketidaksejajaran poros) antara motor dan pompa', 'image' => ''],
                                    ['text' => 'Kavitasi pada sudu pompa hidrolik', 'image' => ''],
                                    ['text' => 'Kekurangan oli pelumas pada tangki reservoir', 'image' => '']
                                ],
                                'correctOptionIndex' => 0,
                                'correctFeedback' => 'Tepat sekali! Angular misalignment secara khas menghasilkan getaran aksial yang kuat pada frekuensi 1X dan 2X RPM akibat gaya lentur kopling yang terjadi dua kali per putaran.',
                                'incorrectFeedback' => 'Jawaban Anda kurang tepat. Jawaban yang benar adalah misalignment (ketidaksejajaran poros) antara motor dan pompa. Mengapa bisa begitu? Angular misalignment memicu gaya tarik-mendorong pada kopling yang terjadi tepat dua kali dalam satu putaran poros, sehingga menghasilkan lonjakan karakteristik pada frekuensi 2X RPM dengan komponen aksial yang tinggi. Sebaliknya, kavitasi pada pompa menghasilkan spektrum getaran acak berupa gundukan noise di frekuensi tinggi, dan kekurangan oli pelumas umumnya memicu peningkatan suhu atau keausan tanpa memunculkan puncak diskrit spesifik di 2X RPM.',
                                'randomizeOptions' => true,
                                'hasCorrectFeedback' => true,
                                'hasIncorrectFeedback' => true
                            ],
                            [
                                'type' => 'multiple_choice',
                                'questionText' => '<p>Berapakah standar toleransi temperatur operasi maksimal pada bearing support casing rotary kiln sebelum memicu alarm interlock di central control room (CCR)?</p>',
                                'contentBlocks' => [],
                                'options' => [
                                    ['text' => '65°C', 'image' => ''],
                                    ['text' => '85°C', 'image' => ''],
                                    ['text' => '120°C', 'image' => '']
                                ],
                                'correctOptionIndex' => 1,
                                'correctFeedback' => 'Benar. Sesuai standar operasional pemeliharaan SIG, suhu bearing di atas 85°C mengindikasikan potensi kegagalan pelumasan atau beban berlebih yang memerlukan tindakan inspeksi segera.',
                                'incorrectFeedback' => 'Jawaban Anda kurang tepat. Jawaban yang benar adalah 85°C. Mengapa bisa begitu? Berdasarkan pedoman standar operasional SIG untuk mesin putar kritis, suhu 85°C ditetapkan sebagai batas ambang atas (alarm interlock) untuk memperingatkan operator sebelum terjadi kerusakan bantalan. Suhu 65°C merupakan temperatur kerja normal yang sepenuhnya aman, sedangkan jika mencapai 120°C, mesin sudah berada dalam kondisi darurat dan kerusakan fatal (seperti melelehnya material babbitt) dipastikan sudah terjadi.',
                                'randomizeOptions' => true,
                                'hasCorrectFeedback' => true,
                                'hasIncorrectFeedback' => true
                            ],
                            [
                                'type' => 'multiple_choice',
                                'questionText' => '<p>Bagaimanakah pengaruh fenomena soft foot (kaki motor tidak menapak rata) terhadap pembacaan vibrasi motor drive belt conveyor clinker?</p>',
                                'contentBlocks' => [],
                                'options' => [
                                    ['text' => 'Meningkatkan amplitudo getaran pada frekuensi 1X dan 2X frekuensi jala-jala listrik (line frequency 50Hz / 100Hz) akibat distorsi air gap motor', 'image' => ''],
                                    ['text' => 'Menghilangkan seluruh getaran mekanis pada poros', 'image' => ''],
                                    ['text' => 'Menyebabkan sabuk conveyor tergelincir (slip) secara langsung', 'image' => '']
                                ],
                                'correctOptionIndex' => 0,
                                'correctFeedback' => 'Luar biasa! Soft foot menyebabkan distorsi pada frame motor saat baut dikencangkan, yang memicu ketidakseimbangan medan magnet (dynamic/static air gap eccentricity), menghasilkan getaran pada 2X line frequency (100 Hz).',
                                'incorrectFeedback' => 'Jawaban Anda kurang tepat. Jawaban yang benar adalah meningkatkan amplitudo getaran pada frekuensi 1X dan 2X frekuensi jala-jala listrik (line frequency 50Hz / 100Hz) akibat distorsi air gap motor. Mengapa bisa begitu? Ketika baut pengencang ditarik pada kaki motor yang tidak rata (soft foot), terjadi distorsi pada frame stator motor. Distorsi ini merusak kesimetrisan celah udara (air gap eccentricity) antara stator dan rotor, memicu ketidakseimbangan tarikan magnetis pada frekuensi kelistrikan (50Hz/100Hz). Fenomena ini tidak akan menghilangkan getaran mekanis yang ada, dan juga tidak memiliki hubungan mekanis langsung dengan masalah tergelincirnya (slip) sabuk conveyor.',
                                'randomizeOptions' => true,
                                'hasCorrectFeedback' => true,
                                'hasIncorrectFeedback' => true
                            ],
                            [
                                'type' => 'multiple_choice',
                                'questionText' => '<p>Metode analisis getaran manakah yang paling efektif untuk mendeteksi kerusakan awal pada roda gigi (gear mesh frequency) di dalam gearbox ball mill?</p>',
                                'contentBlocks' => [],
                                'options' => [
                                    ['text' => 'Analisis Time Waveform dan Demodulasi (Enveloping / PeakValue)', 'image' => ''],
                                    ['text' => 'Pengukuran Overall Velocity murni tanpa filter', 'image' => ''],
                                    ['text' => 'Pengecekan visual pada indikator level oli eksternal', 'image' => '']
                                ],
                                'correctOptionIndex' => 0,
                                'correctFeedback' => 'Sangat tepat! Teknik demodulasi (enveloping) menyaring frekuensi rendah dan memperkuat sinyal impak frekuensi tinggi dari cacat roda gigi, sehingga cacat awal dapat terdeteksi jauh sebelum amplitudo overall meningkat.',
                                'incorrectFeedback' => 'Jawaban Anda kurang tepat. Jawaban yang benar adalah Analisis Time Waveform dan Demodulasi (Enveloping / PeakValue). Mengapa bisa begitu? Cacat mikroskopis awal pada gigi roda gigi menghasilkan energi impak yang sangat kecil dan berfrekuensi tinggi. Teknik demodulasi (enveloping) secara khusus menyaring frekuensi rendah dan mengekstrak sinyal impak tersebut. Sebaliknya, pengukuran Overall Velocity murni tanpa filter tidak mampu mendeteksi impak kecil ini karena angkanya didominasi oleh getaran rotasi besar dari ball mill. Adapun pengecekan visual pada indikator oli eksternal hanya menunjukkan volume pelumas, bukan kondisi keausan roda gigi di bagian dalam.',
                                'randomizeOptions' => true,
                                'hasCorrectFeedback' => true,
                                'hasIncorrectFeedback' => true
                            ]
                        ]
                    ],
                    ['id' => 'item-3', 'title' => '1.3 Instalasi Sensor dan Proximity', 'type' => 'video', 'meta' => 'Video MP4 • 32MB', 'url' => '#']
                ]
            ],
            [
                'id' => 'chapter-2',
                'title' => 'STUDI KASUS KILN',
                'summary' => 'Analisa anomali temperatur dan getaran pada unit tanur semen (Kiln).',
                'items' => []
            ]
        ]) !!},

        renderChapters: true,

        {{-- Modal States --}}
        isSavingDraft: false,
        draftStatusText: '',
        showNudgeToast: false,
        nudgeTimer: null,

        chapterModalOpen: false,
        modalChapterMode: 'add',
        activeChapterIndex: null,
        activeChapterForm: { title: '', summary: '' },

        videoModalOpen: false,
        quizModalOpen: false,
        detailModalOpen: false,
        activeItemDetail: {},
        modalItemMode: 'add',
        activeItemChapterIndex: null,
        activeItemIndex: null,
        videoDragging: false,
        pdfDragging: false,
        isUploadingVideo: false,
        videoUploadProgress: 0,
        activeQuestionIndex: 0,
        activeItemForm: { 
            title: '', 
            meta: '', 
            url: '', 
            questions: 10,
            summary: '',
            videoUploaded: false,
            videoFilename: '',
            videoFilesize: '',
            videoDuration: '',
            videoPreviewUrl: '',
            pdfUploaded: false,
            pdfFilename: '',
            attachments: [],
            durationMinutes: 15,
            isInfinityDuration: false,
            passingGrade: 75,
            shuffle: true,
            showCorrectAnswer: true,
            questionsList: [
                {
                    type: 'multiple_choice',
                    questionText: '<p>Perhatikan grafik anomali vibrasi pada main drive kiln berikut ini. Berapakah batas normal vibrasi velocity saat operasi beban penuh?</p>',
                    contentBlocks: [
                        { type: 'image', url: 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?auto=format&fit=crop&w=800&q=80' }
                    ],
                    options: [
                        { text: '4.5 mm/s', image: '' },
                        { text: '7.1 mm/s', image: '' },
                        { text: '12.0 mm/s', image: '' }
                    ],
                    correctOptionIndex: 1,
                    correctFeedback: 'Jawaban Anda tepat! Batas normal vibrasi velocity pada kondisi beban penuh adalah 7.1 mm/s sesuai standar ISO 10816-3.',
                    incorrectFeedback: 'Jawaban Anda kurang tepat. Jawaban yang benar adalah 7.1 mm/s. Mengapa bisa begitu? Menurut tabel standar ISO 10816-3 untuk mesin grup 1 kelas berat dengan pondasi kaku, batas zona hijau (normal operasi beban penuh) adalah 7.1 mm/s. Nilai 4.5 mm/s adalah batas untuk mesin kecil, sedangkan 12.0 mm/s sudah masuk dalam kategori alarm/kritis (zona C/D) yang memerlukan tindakan perbaikan.',
                    randomizeOptions: true,
                    hasCorrectFeedback: true,
                    hasIncorrectFeedback: true
                }
            ]
        },

        init() {
            this.initSortable();
            this.$watch('quizModalOpen', value => {
                if (value) {
                    this.startNudgeTimer();
                } else {
                    this.clearNudgeTimer();
                }
            });
        },

        startNudgeTimer() {
            this.clearNudgeTimer();
            this.nudgeTimer = setTimeout(() => {
                if (this.quizModalOpen) {
                    this.showNudgeToast = true;
                }
            }, 60000); // 60 detik
        },

        clearNudgeTimer() {
            if (this.nudgeTimer) {
                clearTimeout(this.nudgeTimer);
                this.nudgeTimer = null;
            }
            this.showNudgeToast = false;
        },

        initSortable() {
            this.$nextTick(() => {
                {{-- Init Chapters Sortable --}}
                const chapterContainer = document.getElementById('chapters-container');
                if (chapterContainer && !chapterContainer._sortableInstance) {
                    chapterContainer._sortableInstance = new Sortable(chapterContainer, {
                        draggable: '.chapter-card',
                        handle: '.chapter-drag-handle',
                        filter: 'input, textarea, a, button, p, h3',
                        preventOnFilter: false,
                        animation: 200,
                        ghostClass: 'opacity-50',
                        forceFallback: true,
                        fallbackOnBody: true,
                        fallbackClass: 'sortable-drag-fallback',
                        scroll: true,
                        scrollSensitivity: 100,
                        scrollSpeed: 25,
                        bubbleScroll: true,
                        onStart: (evt) => {
                            {{-- Merekam pointer memori fisik tetangga kanan TEPAT SEBELUM Sortable merubah DOM --}}
                            evt.item._oldNextSibling = evt.item.nextSibling;
                        },
                        onEnd: (evt) => {
                            const oldIndex = evt.oldDraggableIndex !== undefined ? evt.oldDraggableIndex : evt.oldIndex;
                            const newIndex = evt.newDraggableIndex !== undefined ? evt.newDraggableIndex : evt.newIndex;
                            
                            if (newIndex === oldIndex) return;

                            {{-- 1. Revert physical DOM change made by SortableJS secara flawless & akurat 100% --}}
                            const parent = evt.from;
                            const item = evt.item;
                            parent.insertBefore(item, item._oldNextSibling);

                            {{-- 2. Let AlpineJS handle the DOM reordering via data reactivity --}}
                            let chapters = [...Alpine.raw(this.chapters)];
                            const movedItem = chapters.splice(oldIndex, 1)[0];
                            chapters.splice(newIndex, 0, movedItem);
                            
                            this.chapters = chapters;
                        }
                    });
                }

                {{-- Init Items Sortable for each chapter --}}
                this.initItemsSortable();
            });
        },

        initItemsSortable() {
            this.chapters.forEach((chapter) => {
                const el = document.getElementById(`items-container-${chapter.id}`);
                if (el && !el._sortableInstance) {
                    el._sortableInstance = new Sortable(el, {
                        group: 'nested',
                        draggable: '.item-card',
                        handle: '.item-drag-handle',
                        filter: 'input, textarea, a, button, p, h3',
                        preventOnFilter: false,
                        animation: 150,
                        forceFallback: true,
                        fallbackOnBody: true,
                        fallbackClass: 'sortable-drag-fallback',
                        scroll: true,
                        scrollSensitivity: 100,
                        scrollSpeed: 25,
                        bubbleScroll: true,
                        swapThreshold: 0.65,
                        ghostClass: 'opacity-50',
                        onStart: (evt) => {
                            {{-- Merekam pointer memori fisik tetangga kanan TEPAT SEBELUM Sortable merubah DOM --}}
                            evt.item._oldNextSibling = evt.item.nextSibling;
                        },
                        onEnd: (evt) => {
                            const oldIndex = evt.oldDraggableIndex !== undefined ? evt.oldDraggableIndex : evt.oldIndex;
                            const newIndex = evt.newDraggableIndex !== undefined ? evt.newDraggableIndex : evt.newIndex;
                            
                            const fromEl = evt.from;
                            const toEl = evt.to;

                            {{-- 1. Revert physical DOM change made by SortableJS secara flawless & akurat 100% --}}
                            const item = evt.item;
                            fromEl.insertBefore(item, item._oldNextSibling);

                            {{-- 2. Let AlpineJS handle the DOM reordering via data reactivity --}}
                            const fromChapterId = fromEl.id.replace('items-container-', '');
                            const toChapterId = toEl.id.replace('items-container-', '');
                            
                            const fromChapter = this.chapters.find(c => c.id === fromChapterId);
                            const toChapter = this.chapters.find(c => c.id === toChapterId);

                            if (fromChapter && toChapter) {
                                let fromItems = [...Alpine.raw(fromChapter.items)];
                                let toItems = fromChapterId === toChapterId ? fromItems : [...Alpine.raw(toChapter.items)];

                                const movedItem = fromItems.splice(oldIndex, 1)[0];
                                toItems.splice(newIndex, 0, movedItem);

                                fromChapter.items = fromItems;
                                if (fromChapterId !== toChapterId) {
                                    toChapter.items = toItems;
                                }

                                this.chapters = [...this.chapters];
                            }
                        }
                    });
                }
            });
        },

        {{-- Chapter Modal Handlers --}}
        openAddChapterModal() {
            this.modalChapterMode = 'add';
            this.activeChapterForm = { title: '', summary: '' };
            this.chapterModalOpen = true;
        },

        openEditChapterModal(index) {
            this.modalChapterMode = 'edit';
            this.activeChapterIndex = index;
            this.activeChapterForm = { 
                title: this.chapters[index].title, 
                summary: this.chapters[index].summary 
            };
            this.chapterModalOpen = true;
        },

        saveChapterModal() {
            if (!this.activeChapterForm.title.trim()) {
                Alert.warning('Peringatan', 'Judul bab tidak boleh kosong.');
                return;
            }

            if (this.modalChapterMode === 'add') {
                const newChapter = {
                    id: 'chapter-' + Date.now(),
                    title: this.activeChapterForm.title,
                    summary: this.activeChapterForm.summary,
                    items: []
                };
                this.chapters.push(newChapter);
                this.$nextTick(() => { this.initItemsSortable(); });
                window.dispatchEvent(new CustomEvent('toast', { detail: { title: 'Berhasil', message: 'Bab baru berhasil ditambahkan.', type: 'success' } }));
            } else {
                this.chapters[this.activeChapterIndex].title = this.activeChapterForm.title;
                this.chapters[this.activeChapterIndex].summary = this.activeChapterForm.summary;
                window.dispatchEvent(new CustomEvent('toast', { detail: { title: 'Berhasil', message: 'Informasi bab berhasil diperbarui.', type: 'success' } }));
            }

            this.chapterModalOpen = false;
        },

        deleteChapter(index) {
            Alert.confirm('Hapus Bab?', 'Seluruh video dan kuis di dalam bab ini akan ikut terhapus.')
            .then((result) => {
                if (result.isConfirmed) {
                    this.chapters.splice(index, 1);
                    window.dispatchEvent(new CustomEvent('toast', { detail: { title: 'Berhasil', message: 'Bab berhasil dihapus.', type: 'success' } }));
                }
            });
        },

        {{-- Item Modal Handlers --}}
        openAddVideoModal(chapterIndex) {
            this.modalItemMode = 'add';
            this.activeItemChapterIndex = chapterIndex;
            this.isUploadingVideo = false;
            this.videoUploadProgress = 0;
            this.activeItemForm = { 
                title: '', 
                meta: '', 
                url: '', 
                questions: 10,
                summary: '',
                videoUploaded: false,
                videoFilename: '',
                videoFilesize: '',
                videoDuration: '',
                videoPreviewUrl: '',
                pdfUploaded: false,
                pdfFilename: '',
                attachments: []
            };
            this.videoModalOpen = true;
            this.checkVideoDraft(chapterIndex);
        },

        checkQuizDraft(chapterIndex) {
            const chapterId = this.chapters[chapterIndex]?.id || 'chapter-1';
            axios.get(`{{ route('sme.quiz.draft.get', $blueprint->id) }}?chapter_id=${chapterId}`)
            .then(res => {
                if (res.data.success && res.data.has_draft) {
                    Alert.confirm('Draf Kuis Ditemukan', `Sistem mendeteksi adanya draf kuis yang belum tersimpan (disimpan pada ${res.data.draft.last_saved_at}). Apakah Anda ingin memulihkan data draf tersebut?`)
                    .then((result) => {
                        if (result.isConfirmed) {
                            this.activeItemForm = res.data.draft.payload;
                            this.draftStatusText = `✅ Draf dipulihkan (${res.data.draft.last_saved_at})`;
                            this.$nextTick(() => {
                                if (window.quillEditorInstance) {
                                    window.quillEditorInstance.root.innerHTML = this.activeItemForm.questionsList[this.activeQuestionIndex]?.questionText || '';
                                }
                            });
                            window.dispatchEvent(new CustomEvent('toast', { detail: { title: 'Draf Dipulihkan', message: 'Data kuis berhasil dipulihkan dari draf terakhir.', type: 'success' } }));
                        }
                    });
                }
            })
            .catch(err => console.error('Gagal mengecek draf kuis:', err));
        },

        openAddQuizModal(chapterIndex) {
            this.modalItemMode = 'add';
            this.activeItemChapterIndex = chapterIndex;
            this.activeQuestionIndex = 0;
            this.activeItemForm = { 
                title: 'Kuis Evaluasi Terminasi Sensor', 
                questions: 10,
                summary: 'Kuis evaluasi pemahaman mendalam terkait materi bab ini dengan sistem pengacakan otomatis LMS.',
                videoUploaded: false,
                videoFilename: '',
                videoFilesize: '',
                videoDuration: '',
                videoPreviewUrl: '',
                pdfUploaded: false,
                pdfFilename: '',
                durationMinutes: 15,
                isInfinityDuration: false,
                passingGrade: 75,
                shuffle: true,
                showCorrectAnswer: true,
                questionsList: [
                    {
                        type: 'multiple_choice',
                        questionText: '<p>Perhatikan grafik anomali vibrasi pada main drive kiln berikut ini. Berapakah batas normal vibrasi velocity saat operasi beban penuh?</p>',
                        contentBlocks: [
                            { type: 'image', url: 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?auto=format&fit=crop&w=800&q=80' }
                        ],
                        options: [
                            { text: '4.5 mm/s', image: '' },
                            { text: '7.1 mm/s', image: '' },
                            { text: '12.0 mm/s', image: '' }
                        ],
                        correctOptionIndex: 1,
                        correctFeedback: 'Jawaban Anda tepat! Batas normal vibrasi velocity pada kondisi beban penuh adalah 7.1 mm/s sesuai standar ISO 10816-3.',
                        incorrectFeedback: 'Jawaban Anda kurang tepat. Jawaban yang benar adalah 7.1 mm/s. Mengapa bisa begitu? Menurut tabel standar ISO 10816-3 untuk mesin grup 1 kelas berat dengan pondasi kaku, batas zona hijau (normal operasi beban penuh) adalah 7.1 mm/s. Nilai 4.5 mm/s adalah batas untuk mesin kecil, sedangkan 12.0 mm/s sudah masuk dalam kategori alarm/kritis (zona C/D) yang memerlukan tindakan perbaikan.',
                        randomizeOptions: true,
                        hasCorrectFeedback: true,
                        hasIncorrectFeedback: true
                    }
                ]
            };
            this.quizModalOpen = true;
            this.checkQuizDraft(chapterIndex);
        },

        openEditItemModal(chapterIndex, itemIndex) {
            this.modalItemMode = 'edit';
            this.activeItemChapterIndex = chapterIndex;
            this.activeItemIndex = itemIndex;
            const item = this.chapters[chapterIndex].items[itemIndex];
            
            const isVideo = item.type === 'video';
            const defaultVideoUrl = (item.url && item.url !== '#') ? item.url : 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerBlazes.mp4';
            const defaultVideoFilename = item.videoFilename || (item.url && item.url !== '#' ? item.url.split('/').pop() : (item.title.includes('Instalasi') ? '1.3_Instalasi_Sensor_Proximity.mp4' : '1.1_Prinsip_Fundamental_Getaran.mp4'));
            const defaultPdfFilename = item.pdfFilename || (item.title.includes('Instalasi') ? 'Panduan_Instalasi_Sensor_Proximity.pdf' : 'Panduan_Kalibrasi_Vibrasi.pdf');
            const defaultPdfFilesize = item.title.includes('Instalasi') ? '3.8 MB' : '2.4 MB';
            
            this.isUploadingVideo = false;
            this.videoUploadProgress = 0;
            this.activeQuestionIndex = 0;
            this.activeItemForm = { 
                title: item.title.replace(/^\d+\.\d+\s*/, ''), 
                meta: item.meta || (isVideo ? 'Video MP4 • 45 MB' : 'Kuis • 10 Pertanyaan'), 
                url: defaultVideoUrl, 
                questions: item.questions || 10,
                summary: item.summary || 'Fokus pada analisis deteksi anomali pada dinding kiln. Materi mencakup vibrasi non-standar pada bearing utama dan prosedur kalibrasi termal sensor inframerah.',
                videoUploaded: isVideo,
                videoFilename: isVideo ? defaultVideoFilename : '',
                videoFilesize: item.videoFilesize || '45 MB',
                videoDuration: item.videoDuration || '12:45',
                videoPreviewUrl: item.videoPreviewUrl || defaultVideoUrl,
                pdfUploaded: item.attachments && item.attachments.length > 0 ? true : true,
                pdfFilename: defaultPdfFilename,
                attachments: item.attachments && item.attachments.length > 0 ? JSON.parse(JSON.stringify(item.attachments)) : [{ filename: defaultPdfFilename, filesize: defaultPdfFilesize, url: '#' }],
                durationMinutes: item.durationMinutes || 15,
                isInfinityDuration: item.isInfinityDuration || false,
                passingGrade: item.passingGrade || 75,
                shuffle: item.shuffle !== undefined ? item.shuffle : true,
                showCorrectAnswer: item.showCorrectAnswer !== undefined ? item.showCorrectAnswer : true,
                questionsList: (item.questionsList || [
                    {
                        type: 'multiple_choice',
                        questionText: '<p>Perhatikan grafik anomali vibrasi pada main drive kiln berikut ini. Berapakah batas normal vibrasi velocity saat operasi beban penuh?</p>',
                        contentBlocks: [
                            { type: 'image', url: 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?auto=format&fit=crop&w=800&q=80' }
                        ],
                        options: [
                            { text: '4.5 mm/s', image: '' },
                            { text: '7.1 mm/s', image: '' },
                            { text: '12.0 mm/s', image: '' }
                        ],
                        correctOptionIndex: 1,
                        correctFeedback: 'Jawaban Anda tepat! Batas normal vibrasi velocity pada kondisi beban penuh adalah 7.1 mm/s sesuai standar ISO 10816-3.',
                        incorrectFeedback: 'Jawaban Anda kurang tepat. Jawaban yang benar adalah 7.1 mm/s. Mengapa bisa begitu? Menurut tabel standar ISO 10816-3 untuk mesin grup 1 kelas berat dengan pondasi kaku, batas zona hijau (normal operasi beban penuh) adalah 7.1 mm/s. Nilai 4.5 mm/s adalah batas untuk mesin kecil, sedangkan 12.0 mm/s sudah masuk dalam kategori alarm/kritis (zona C/D) yang memerlukan tindakan perbaikan.',
                        randomizeOptions: true,
                        hasCorrectFeedback: true,
                        hasIncorrectFeedback: true
                    }
                ]).map(q => ({
                    ...q,
                    hasCorrectFeedback: q.hasCorrectFeedback !== undefined ? q.hasCorrectFeedback : true,
                    hasIncorrectFeedback: q.hasIncorrectFeedback !== undefined ? q.hasIncorrectFeedback : (q.incorrectFeedback ? true : false)
                }))
            };

            if (item.type === 'video') {
                this.videoModalOpen = true;
                this.checkVideoDraft(chapterIndex);
            } else {
                this.quizModalOpen = true;
                this.checkQuizDraft(chapterIndex);
            }
        },

        openDetailModal(chapterIndex, itemIndex) {
            this.activeItemChapterIndex = chapterIndex;
            this.activeItemIndex = itemIndex;
            const item = this.chapters[chapterIndex].items[itemIndex];
            
            const isVideo = item.type === 'video';
            const defaultVideoUrl = item.url || 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerBlazes.mp4';
            const defaultVideoFilename = item.videoFilename || (item.url ? item.url.split('/').pop() : 'video_materi_utama.mp4');
            
            this.activeItemDetail = { 
                type: item.type,
                title: item.title.replace(/^\d+\.\d+\s*/, ''), 
                meta: item.meta || (isVideo ? 'Video MP4 • 45 MB' : 'Kuis • 10 Pertanyaan'), 
                url: defaultVideoUrl, 
                questions: item.questions || (item.questionsList ? item.questionsList.length : 10),
                summary: item.summary || (isVideo ? 'Fokus pada analisis deteksi anomali pada dinding kiln. Materi mencakup vibrasi non-standar pada bearing utama dan prosedur kalibrasi termal sensor inframerah.' : 'Kuis evaluasi pemahaman mendalam terkait materi bab ini dengan sistem pengacakan otomatis LMS.'),
                videoFilename: isVideo ? defaultVideoFilename : '',
                videoFilesize: item.videoFilesize || '45 MB',
                videoDuration: item.videoDuration || '12:45',
                videoPreviewUrl: item.videoPreviewUrl || defaultVideoUrl,
                pdfUploaded: item.attachments && item.attachments.length > 0 ? true : (item.pdfFilename ? true : false),
                pdfFilename: item.pdfFilename || 'Panduan_Kalibrasi.pdf',
                attachments: item.attachments ? JSON.parse(JSON.stringify(item.attachments)) : (item.pdfFilename ? [{ filename: item.pdfFilename, filesize: '2.4 MB', url: '#' }] : []),
                durationMinutes: item.durationMinutes || 15,
                isInfinityDuration: item.isInfinityDuration || false,
                passingGrade: item.passingGrade || 75,
                shuffle: item.shuffle !== undefined ? item.shuffle : true,
                showCorrectAnswer: item.showCorrectAnswer !== undefined ? item.showCorrectAnswer : true,
                questionsList: item.questionsList ? JSON.parse(JSON.stringify(item.questionsList)) : [
                    {
                        type: 'multiple_choice',
                        questionText: '<p>Perhatikan grafik anomali vibrasi pada main drive kiln berikut ini. Berapakah batas normal vibrasi velocity saat operasi beban penuh menurut standar ISO 10816-3?</p>',
                        contentBlocks: [
                            { type: 'image', url: 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?auto=format&fit=crop&w=800&q=80' }
                        ],
                        options: [
                            { text: '4.5 mm/s', image: '' },
                            { text: '7.1 mm/s', image: '' },
                            { text: '12.0 mm/s', image: '' }
                        ],
                        correctOptionIndex: 1,
                        correctFeedback: 'Jawaban Anda tepat! Batas normal vibrasi velocity pada kondisi beban penuh untuk mesin grup 1 kelas berat adalah 7.1 mm/s sesuai standar ISO 10816-3.',
                        incorrectFeedback: 'Jawaban Anda kurang tepat. Jawaban yang benar adalah 7.1 mm/s. Mengapa bisa begitu? Menurut tabel standar ISO 10816-3 untuk mesin grup 1 kelas berat dengan pondasi kaku, batas zona hijau (normal operasi beban penuh) adalah 7.1 mm/s. Nilai 4.5 mm/s adalah batas untuk mesin kecil, sedangkan 12.0 mm/s sudah masuk dalam kategori alarm/kritis (zona C/D) yang memerlukan tindakan perbaikan.',
                        randomizeOptions: true,
                        hasCorrectFeedback: true,
                        hasIncorrectFeedback: true
                    },
                    {
                        type: 'multiple_choice',
                        questionText: '<p>Apa fungsi utama dari penerapan analisis spektrum Fast Fourier Transform (FFT) pada pemantauan motor fan kiln?</p>',
                        contentBlocks: [],
                        options: [
                            { text: 'Mengubah sinyal getaran dari domain waktu ke domain frekuensi untuk mengidentifikasi sumber spesifik kerusakan mesin', image: '' },
                            { text: 'Menurunkan temperatur pelumas pada bearing casing secara otomatis', image: '' },
                            { text: 'Meningkatkan kecepatan putaran impeler ID Fan melebihi kapasitas desain', image: '' }
                        ],
                        correctOptionIndex: 0,
                        correctFeedback: 'Benar sekali! Analisis FFT berfungsi mengonversi sinyal domain waktu ke frekuensi, sehingga teknisi dapat membedakan unbalance, misalignment, atau kerusakan bearing berdasarkan puncak frekuensinya.',
                        incorrectFeedback: 'Jawaban Anda kurang tepat. Jawaban yang benar adalah mengubah sinyal getaran dari domain waktu ke domain frekuensi untuk mengidentifikasi sumber spesifik kerusakan mesin. Mengapa bisa begitu? Algoritma FFT murni merupakan metode pengolahan sinyal matematis untuk keperluan diagnosa spektrum getaran, bukan alat mekanis atau sistem kontrol otomatis. Oleh karena itu, FFT tidak dapat memodifikasi parameter fisik di lapangan seperti menurunkan suhu pelumas ataupun menaikkan kecepatan putar impeler.',
                        randomizeOptions: true,
                        hasCorrectFeedback: true,
                        hasIncorrectFeedback: true
                    },
                    {
                        type: 'multiple_choice',
                        questionText: '<p>Jenis sensor vibrasi manakah yang paling tepat dan sensitif digunakan untuk mengukur getaran frekuensi tinggi pada rolling element bearing?</p>',
                        contentBlocks: [],
                        options: [
                            { text: 'Proximity Eddy Current Probe', image: '' },
                            { text: 'Piezoelectric Accelerometer', image: '' },
                            { text: 'Seismic Velocity Transducer', image: '' }
                        ],
                        correctOptionIndex: 1,
                        correctFeedback: 'Tepat! Piezoelectric Accelerometer memiliki respon frekuensi yang sangat tinggi (hingga melebihi 10 kHz), menjadikannya pilihan utama untuk mendeteksi frekuensi cacat bearing (BPFO, BPFI, BSF).',
                        incorrectFeedback: 'Jawaban Anda kurang tepat. Jawaban yang benar adalah Piezoelectric Accelerometer. Mengapa bisa begitu? Cacat pada rolling element bearing menghasilkan sinyal impak pada frekuensi yang sangat tinggi (di atas 1 kHz hingga 20 kHz). Piezoelectric Accelerometer memiliki respon frekuensi alami yang sangat lebar untuk menangkap sinyal tersebut. Di sisi lain, Proximity Probe didesain untuk frekuensi rendah (pengukuran displacement poros turbin), dan Seismic Velocity Transducer mengalami penurunan sensitivitas secara drastis pada frekuensi di atas 1000 Hz.',
                        randomizeOptions: true,
                        hasCorrectFeedback: true,
                        hasIncorrectFeedback: true
                    },
                    {
                        type: 'multiple_choice',
                        questionText: '<p>Indikasi utama terjadinya fenomena unbalance pada impeler ID Fan kiln berdasarkan spektrum getaran FFT adalah...</p>',
                        contentBlocks: [],
                        options: [
                            { text: 'Munculnya puncak dominan pada frekuensi 1X RPM (1X running speed) dengan amplitudo tinggi arah radial', image: '' },
                            { text: 'Munculnya sub-harmonik pada frekuensi 0.5X RPM', image: '' },
                            { text: 'Puncak harmonik yang merata dari 1X hingga 10X RPM', image: '' }
                        ],
                        correctOptionIndex: 0,
                        correctFeedback: 'Jawaban Anda benar. Unbalance selalu menghasilkan gaya sentrifugal yang bermanifestasi sebagai getaran sinusoidal murni pada frekuensi 1X putaran poros (1X RPM) di arah radial.',
                        incorrectFeedback: 'Jawaban Anda kurang tepat. Jawaban yang benar adalah munculnya puncak dominan pada frekuensi 1X RPM (1X running speed) dengan amplitudo tinggi arah radial. Mengapa bisa begitu? Ketidakseimbangan massa (unbalance) selalu menghasilkan gaya sentrifugal searah putaran poros (1X RPM). Jika muncul frekuensi sub-harmonik 0.5X RPM, hal tersebut merupakan karakteristik dari instabilitas pelumas (oil whirl/whip) pada journal bearing. Sedangkan jika muncul puncak harmonik merata dari 1X hingga 10X RPM, itu merupakan indikasi dari kelonggaran mekanis (looseness), bukan unbalance.',
                        randomizeOptions: true,
                        hasCorrectFeedback: true,
                        hasIncorrectFeedback: true
                    },
                    {
                        type: 'multiple_choice',
                        questionText: '<p>Parameter getaran utama yang diukur oleh proximity probe pada turbin generator pabrik semen adalah...</p>',
                        contentBlocks: [],
                        options: [
                            { text: 'Akselerasi (g) dari casing mesin', image: '' },
                            { text: 'Kecepatan getaran (mm/s) pada pondasi', image: '' },
                            { text: 'Perpindahan relatif (displacement dalam mikron) antara poros berputar dan journal bearing', image: '' }
                        ],
                        correctOptionIndex: 2,
                        correctFeedback: 'Sangat tepat! Proximity probe mengukur displacement (jarak relatif) poros terhadap bantalan, yang sangat krusial untuk memantau ketebalan film pelumas dan pergerakan poros turbin.',
                        incorrectFeedback: 'Jawaban Anda kurang tepat. Jawaban yang benar adalah perpindahan relatif (displacement dalam mikron) antara poros berputar dan journal bearing. Mengapa bisa begitu? Proximity probe merupakan sensor non-kontak berbasis arus eddy yang dirancang khusus untuk mengukur celah (gap) dan pergerakan fisik poros secara langsung (displacement). Sensor ini tidak mengukur getaran pada casing atau pondasi luar mesin yang umumnya menggunakan besaran akselerasi (g) untuk frekuensi tinggi atau kecepatan (mm/s) untuk frekuensi menengah.',
                        randomizeOptions: true,
                        hasCorrectFeedback: true,
                        hasIncorrectFeedback: true
                    },
                    {
                        type: 'multiple_choice',
                        questionText: '<p>Langkah awal yang wajib dilakukan teknisi sebelum memasang sensor akselerometer dengan metode magnetic base pada area bearing adalah...</p>',
                        contentBlocks: [],
                        options: [
                            { text: 'Mengoleskan pelumas gemuk (grease) dalam jumlah sangat banyak di seluruh permukaan sensor', image: '' },
                            { text: 'Membersihkan permukaan ukur dari kotoran, kerak semen, atau cat mengelupas agar kontak magnet menempel sempurna', image: '' },
                            { text: 'Memanaskan sensor menggunakan heat gun hingga menyamai suhu casing', image: '' }
                        ],
                        correctOptionIndex: 1,
                        correctFeedback: 'Benar! Kontak mekanis yang solid sangat penting. Kotoran atau kerak semen akan menciptakan celah udara (air gap) yang meredam transmisi frekuensi tinggi dan menurunkan frekuensi resonansi mounting.',
                        incorrectFeedback: 'Jawaban Anda kurang tepat. Jawaban yang benar adalah membersihkan permukaan ukur dari kotoran, kerak semen, atau cat mengelupas agar kontak magnet menempel sempurna. Mengapa bisa begitu? Transmisi sinyal getaran yang akurat membutuhkan kontak mekanis langsung yang solid antara magnet dan casing mesin. Kotoran atau kerak semen akan menimbulkan celah udara (air gap) yang meredam sinyal frekuensi tinggi. Adapun mengoleskan pelumas berlebih justru dapat membuat sensor tidak stabil, dan memanaskan sensor dengan heat gun sangat dilarang karena dapat merusak komponen kristal piezoelektrik di dalamnya.',
                        randomizeOptions: true,
                        hasCorrectFeedback: true,
                        hasIncorrectFeedback: true
                    },
                    {
                        type: 'multiple_choice',
                        questionText: '<p>Penyebab utama munculnya frekuensi dominan 2X RPM pada spektrum getaran motor pompa hidrolik raw mill disertai getaran aksial yang tinggi adalah...</p>',
                        contentBlocks: [],
                        options: [
                            { text: 'Misalignment (ketidaksejajaran poros) antara motor dan pompa', image: '' },
                            { text: 'Kavitasi pada sudu pompa hidrolik', image: '' },
                            { text: 'Kekurangan oli pelumas pada tangki reservoir', image: '' }
                        ],
                        correctOptionIndex: 0,
                        correctFeedback: 'Tepat sekali! Angular misalignment secara khas menghasilkan getaran aksial yang kuat pada frekuensi 1X dan 2X RPM akibat gaya lentur kopling yang terjadi dua kali per putaran.',
                        incorrectFeedback: 'Jawaban Anda kurang tepat. Jawaban yang benar adalah misalignment (ketidaksejajaran poros) antara motor dan pompa. Mengapa bisa begitu? Angular misalignment memicu gaya tarik-mendorong pada kopling yang terjadi tepat dua kali dalam satu putaran poros, sehingga menghasilkan lonjakan karakteristik pada frekuensi 2X RPM dengan komponen aksial yang tinggi. Sebaliknya, kavitasi pada pompa menghasilkan spektrum getaran acak berupa gundukan noise di frekuensi tinggi, dan kekurangan oli pelumas umumnya memicu peningkatan suhu atau keausan tanpa memunculkan puncak diskrit spesifik di 2X RPM.',
                        randomizeOptions: true,
                        hasCorrectFeedback: true,
                        hasIncorrectFeedback: true
                    },
                    {
                        type: 'multiple_choice',
                        questionText: '<p>Berapakah standar toleransi temperatur operasi maksimal pada bearing support casing rotary kiln sebelum memicu alarm interlock di central control room (CCR)?</p>',
                        contentBlocks: [],
                        options: [
                            { text: '65°C', image: '' },
                            { text: '85°C', image: '' },
                            { text: '120°C', image: '' }
                        ],
                        correctOptionIndex: 1,
                        correctFeedback: 'Benar. Sesuai standar operasional pemeliharaan SIG, suhu bearing di atas 85°C mengindikasikan potensi kegagalan pelumasan atau beban berlebih yang memerlukan tindakan inspeksi segera.',
                        incorrectFeedback: 'Jawaban Anda kurang tepat. Jawaban yang benar adalah 85°C. Mengapa bisa begitu? Berdasarkan pedoman standar operasional SIG untuk mesin putar kritis, suhu 85°C ditetapkan sebagai batas ambang atas (alarm interlock) untuk memperingatkan operator sebelum terjadi kerusakan bantalan. Suhu 65°C merupakan temperatur kerja normal yang sepenuhnya aman, sedangkan jika mencapai 120°C, mesin sudah berada dalam kondisi darurat dan kerusakan fatal (seperti melelehnya material babbitt) dipastikan sudah terjadi.',
                        randomizeOptions: true,
                        hasCorrectFeedback: true,
                        hasIncorrectFeedback: true
                    },
                    {
                        type: 'multiple_choice',
                        questionText: '<p>Bagaimanakah pengaruh fenomena soft foot (kaki motor tidak menapak rata) terhadap pembacaan vibrasi motor drive belt conveyor clinker?</p>',
                        contentBlocks: [],
                        options: [
                            { text: 'Meningkatkan amplitudo getaran pada frekuensi 1X dan 2X frekuensi jala-jala listrik (line frequency 50Hz / 100Hz) akibat distorsi air gap motor', image: '' },
                            { text: 'Menghilangkan seluruh getaran mekanis pada poros', image: '' },
                            { text: 'Menyebabkan sabuk conveyor tergelincir (slip) secara langsung', image: '' }
                        ],
                        correctOptionIndex: 0,
                        correctFeedback: 'Luar biasa! Soft foot menyebabkan distorsi pada frame motor saat baut dikencangkan, yang memicu ketidakseimbangan medan magnet (dynamic/static air gap eccentricity), menghasilkan getaran pada 2X line frequency (100 Hz).',
                        incorrectFeedback: 'Jawaban Anda kurang tepat. Jawaban yang benar adalah meningkatkan amplitudo getaran pada frekuensi 1X dan 2X frekuensi jala-jala listrik (line frequency 50Hz / 100Hz) akibat distorsi air gap motor. Mengapa bisa begitu? Ketika baut pengencang ditarik pada kaki motor yang tidak rata (soft foot), terjadi distorsi pada frame stator motor. Distorsi ini merusak kesimetrisan celah udara (air gap eccentricity) antara stator dan rotor, memicu ketidakseimbangan tarikan magnetis pada frekuensi kelistrikan (50Hz/100Hz). Fenomena ini tidak akan menghilangkan getaran mekanis yang ada, dan juga tidak memiliki hubungan mekanis langsung dengan masalah tergelincirnya (slip) sabuk conveyor.',
                        randomizeOptions: true,
                        hasCorrectFeedback: true,
                        hasIncorrectFeedback: true
                    },
                    {
                        type: 'multiple_choice',
                        questionText: '<p>Metode analisis getaran manakah yang paling efektif untuk mendeteksi kerusakan awal pada roda gigi (gear mesh frequency) di dalam gearbox ball mill?</p>',
                        contentBlocks: [],
                        options: [
                            { text: 'Analisis Time Waveform dan Demodulasi (Enveloping / PeakValue)', image: '' },
                            { text: 'Pengukuran Overall Velocity murni tanpa filter', image: '' },
                            { text: 'Pengecekan visual pada indikator level oli eksternal', image: '' }
                        ],
                        correctOptionIndex: 0,
                        correctFeedback: 'Sangat tepat! Teknik demodulasi (enveloping) menyaring frekuensi rendah dan memperkuat sinyal impak frekuensi tinggi dari cacat roda gigi, sehingga cacat awal dapat terdeteksi jauh sebelum amplitudo overall meningkat.',
                        incorrectFeedback: 'Jawaban Anda kurang tepat. Jawaban yang benar adalah Analisis Time Waveform dan Demodulasi (Enveloping / PeakValue). Mengapa bisa begitu? Cacat mikroskopis awal pada gigi roda gigi menghasilkan energi impak yang sangat kecil dan berfrekuensi tinggi. Teknik demodulasi (enveloping) secara khusus menyaring frekuensi rendah dan mengekstrak sinyal impak tersebut. Sebaliknya, pengukuran Overall Velocity murni tanpa filter tidak mampu mendeteksi impak kecil ini karena angkanya didominasi oleh getaran rotasi besar dari ball mill. Adapun pengecekan visual pada indikator oli eksternal hanya menunjukkan volume pelumas, bukan kondisi keausan roda gigi di bagian dalam.',
                        randomizeOptions: true,
                        hasCorrectFeedback: true,
                        hasIncorrectFeedback: true
                    }
                ]
            };

            this.detailModalOpen = true;
        },

        handleVideoUpload(event) {
            const file = event.target.files[0];
            if (file) {
                this.processVideoFile(file);
            }
        },

        handleVideoDrop(event) {
            this.videoDragging = false;
            const file = event.dataTransfer.files[0];
            if (file) {
                this.processVideoFile(file);
            }
        },

        processVideoFile(file) {
            if (!file.type.startsWith('video/')) {
                Alert.warning('File Tidak Valid', 'Sistem menolak file ini. Silakan unggah file video (MP4/WebM/dll), bukan gambar atau dokumen.');
                return;
            }

            this.isUploadingVideo = true;
            this.videoUploadProgress = 0;

            const interval = setInterval(() => {
                if (this.videoUploadProgress < 90) {
                    this.videoUploadProgress += Math.floor(Math.random() * 20) + 10;
                    if (this.videoUploadProgress > 90) this.videoUploadProgress = 90;
                } else {
                    clearInterval(interval);
                    this.videoUploadProgress = 100;
                    setTimeout(() => {
                        this.isUploadingVideo = false;
                        this.activeItemForm.videoUploaded = true;
                        this.activeItemForm.videoFilename = file.name;
                        const sizeMB = (file.size / (1024 * 1024)).toFixed(1);
                        this.activeItemForm.videoFilesize = sizeMB > 1 ? `${sizeMB} MB` : `${(file.size / 1024).toFixed(0)} KB`;
                        
                        if (this.activeItemForm.videoPreviewUrl && this.activeItemForm.videoPreviewUrl.startsWith('blob:')) {
                            URL.revokeObjectURL(this.activeItemForm.videoPreviewUrl);
                        }
                        this.activeItemForm.videoPreviewUrl = URL.createObjectURL(file);
                        this.activeItemForm.videoDuration = '00:00';

                        this.activeItemForm.meta = `Video MP4 • ${this.activeItemForm.videoDuration} • ${this.activeItemForm.videoFilesize}`;
                        this.activeItemForm.url = `https://sig.academy/video/${file.name}`;
                        
                        if (!this.activeItemForm.title) {
                            this.activeItemForm.title = file.name.replace(/\.[^/.]+$/, "").replace(/[_\-]/g, " ");
                        }
                        if (!this.activeItemForm.summary) {
                            this.activeItemForm.summary = 'Fokus pada analisis deteksi anomali pada dinding kiln. Materi mencakup vibrasi non-standar pada bearing utama dan prosedur kalibrasi termal sensor inframerah.';
                        }
                        window.dispatchEvent(new CustomEvent('toast', { detail: { title: 'Upload Berhasil', message: `File ${file.name} berhasil diproses.`, type: 'success' } }));
                    }, 500);
                }
            }, 300);
        },

        handlePdfUpload(event) {
            const file = event.target.files[0];
            if (file) {
                this.processPdfFile(file);
            }
        },

        handlePdfDrop(event) {
            this.pdfDragging = false;
            const file = event.dataTransfer.files[0];
            if (file) {
                this.processPdfFile(file);
            }
        },

        processPdfFile(file) {
            if (!file) return;

            this.activeItemForm.pdfUploaded = true;
            this.activeItemForm.pdfFilename = file.name;
            if (!this.activeItemForm.attachments) {
                this.activeItemForm.attachments = [];
            }
            const sizeMB = (file.size / (1024 * 1024)).toFixed(1);
            const filesizeStr = sizeMB > 1 ? `${sizeMB} MB` : `${(file.size / 1024).toFixed(0)} KB`;
            this.activeItemForm.attachments.push({
                filename: file.name,
                filesize: filesizeStr,
                url: URL.createObjectURL(file)
            });
            window.dispatchEvent(new CustomEvent('toast', { detail: { title: 'Lampiran Ditambahkan', message: `File ${file.name} berhasil dilampirkan.`, type: 'success' } }));
        },

        removeAttachment(index) {
            this.activeItemForm.attachments.splice(index, 1);
            if (this.activeItemForm.attachments.length === 0) {
                this.activeItemForm.pdfUploaded = false;
                this.activeItemForm.pdfFilename = '';
            } else {
                this.activeItemForm.pdfFilename = this.activeItemForm.attachments[0].filename;
            }
        },

        simulateVideoUpload() {
            this.isUploadingVideo = true;
            this.videoUploadProgress = 0;

            const interval = setInterval(() => {
                if (this.videoUploadProgress < 90) {
                    this.videoUploadProgress += Math.floor(Math.random() * 25) + 15;
                    if (this.videoUploadProgress > 90) this.videoUploadProgress = 90;
                } else {
                    clearInterval(interval);
                    this.videoUploadProgress = 100;
                    setTimeout(() => {
                        this.isUploadingVideo = false;
                        this.activeItemForm.videoUploaded = true;
                        this.activeItemForm.videoFilename = '4.1_Analisa_Anomali_Vibrasi.mp4';
                        this.activeItemForm.videoFilesize = '412 MB';
                        this.activeItemForm.videoDuration = '12:45';
                        this.activeItemForm.meta = `Video MP4 • 12:45 • 412 MB`;
                        if (!this.activeItemForm.title) {
                            this.activeItemForm.title = 'Analisa Anomali Vibrasi Kiln';
                        }
                        if (!this.activeItemForm.summary) {
                            this.activeItemForm.summary = 'Fokus pada analisis deteksi anomali pada dinding kiln. Materi mencakup vibrasi non-standar pada bearing utama dan prosedur kalibrasi termal sensor inframerah.';
                        }
                        window.dispatchEvent(new CustomEvent('toast', { detail: { title: 'Upload Selesai', message: 'Video MP4 berhasil diunggah dan diproses server.', type: 'success' } }));
                    }, 500);
                }
            }, 300);
        },

        simulatePdfUpload() {
            this.activeItemForm.pdfUploaded = true;
            this.activeItemForm.pdfFilename = 'Panduan_Kalibrasi.pdf';
            if (!this.activeItemForm.attachments) {
                this.activeItemForm.attachments = [];
            }
            const simFilename = 'Panduan_Kalibrasi_' + (this.activeItemForm.attachments.length + 1) + '.pdf';
            this.activeItemForm.attachments.push({
                filename: simFilename,
                filesize: '2.4 MB',
                url: `https://sig.academy/storage/attachments/${simFilename}`
            });
            window.dispatchEvent(new CustomEvent('toast', { detail: { title: 'Lampiran Ditambahkan', message: 'Dokumen PDF berhasil dilampirkan.', type: 'success' } }));
        },

        saveVideoModal() {
            if (!this.activeItemForm.title.trim()) {
                Alert.warning('Peringatan', 'Judul video tidak boleh empty.');
                return;
            }

            if (this.modalItemMode === 'add') {
                const newItem = {
                    id: 'item-' + Date.now(),
                    title: this.activeItemForm.title,
                    type: 'video',
                    meta: this.activeItemForm.meta || 'Video MP4',
                    url: this.activeItemForm.url,
                    videoFilename: this.activeItemForm.videoFilename,
                    videoFilesize: this.activeItemForm.videoFilesize,
                    videoDuration: this.activeItemForm.videoDuration,
                    videoPreviewUrl: this.activeItemForm.videoPreviewUrl,
                    summary: this.activeItemForm.summary,
                    pdfFilename: this.activeItemForm.pdfFilename,
                    attachments: JSON.parse(JSON.stringify(this.activeItemForm.attachments || []))
                };
                this.chapters[this.activeItemChapterIndex].items.push(newItem);
                window.dispatchEvent(new CustomEvent('toast', { detail: { title: 'Berhasil', message: 'Video berhasil ditambahkan.', type: 'success' } }));
            } else {
                const item = this.chapters[this.activeItemChapterIndex].items[this.activeItemIndex];
                item.title = this.activeItemForm.title;
                item.meta = this.activeItemForm.meta;
                item.url = this.activeItemForm.url;
                item.videoFilename = this.activeItemForm.videoFilename;
                item.videoFilesize = this.activeItemForm.videoFilesize;
                item.videoDuration = this.activeItemForm.videoDuration;
                item.videoPreviewUrl = this.activeItemForm.videoPreviewUrl;
                item.summary = this.activeItemForm.summary;
                item.pdfFilename = this.activeItemForm.pdfFilename;
                item.attachments = JSON.parse(JSON.stringify(this.activeItemForm.attachments || []));
                window.dispatchEvent(new CustomEvent('toast', { detail: { title: 'Berhasil', message: 'Video berhasil diperbarui.', type: 'success' } }));
            }

            this.videoModalOpen = false;
        },

        saveQuizModal() {
            if (!this.activeItemForm.title.trim()) {
                Alert.warning('Peringatan', 'Judul kuis tidak boleh kosong.');
                return;
            }

            const totalQuestions = this.activeItemForm.questionsList.length;

            if (this.modalItemMode === 'add') {
                const newItem = {
                    id: 'item-' + Date.now(),
                    title: this.activeItemForm.title,
                    type: 'quiz',
                    meta: `Kuis • ${totalQuestions} Pertanyaan (${this.activeItemForm.isInfinityDuration ? 'Tanpa Batas Waktu' : this.activeItemForm.durationMinutes + ' Menit'})`,
                    summary: this.activeItemForm.summary,
                    questions: totalQuestions,
                    durationMinutes: this.activeItemForm.durationMinutes,
                    isInfinityDuration: this.activeItemForm.isInfinityDuration,
                    passingGrade: this.activeItemForm.passingGrade,
                    shuffle: this.activeItemForm.shuffle,
                    showCorrectAnswer: this.activeItemForm.showCorrectAnswer,
                    questionsList: JSON.parse(JSON.stringify(this.activeItemForm.questionsList))
                };
                this.chapters[this.activeItemChapterIndex].items.push(newItem);
                window.dispatchEvent(new CustomEvent('toast', { detail: { title: 'Berhasil', message: 'Kuis evaluasi berhasil ditambahkan.', type: 'success' } }));
            } else {
                const item = this.chapters[this.activeItemChapterIndex].items[this.activeItemIndex];
                item.title = this.activeItemForm.title;
                item.summary = this.activeItemForm.summary;
                item.questions = totalQuestions;
                item.meta = `Kuis • ${totalQuestions} Pertanyaan (${this.activeItemForm.isInfinityDuration ? 'Tanpa Batas Waktu' : this.activeItemForm.durationMinutes + ' Menit'})`;
                item.durationMinutes = this.activeItemForm.durationMinutes;
                item.isInfinityDuration = this.activeItemForm.isInfinityDuration;
                item.passingGrade = this.activeItemForm.passingGrade;
                item.shuffle = this.activeItemForm.shuffle;
                item.showCorrectAnswer = this.activeItemForm.showCorrectAnswer;
                item.questionsList = JSON.parse(JSON.stringify(this.activeItemForm.questionsList));
                window.dispatchEvent(new CustomEvent('toast', { detail: { title: 'Berhasil', message: 'Kuis evaluasi berhasil diperbarui.', type: 'success' } }));
            }

            this.quizModalOpen = false;
        },

        deleteItem(chapterIndex, itemIndex) {
            Alert.confirm('Hapus Item?', 'Item ini akan dihapus dari struktur kurikulum.')
            .then((result) => {
                if (result.isConfirmed) {
                    this.chapters[chapterIndex].items.splice(itemIndex, 1);
                    window.dispatchEvent(new CustomEvent('toast', { detail: { title: 'Berhasil', message: 'Item berhasil dihapus.', type: 'success' } }));
                }
            });
        },

        {{-- Backend Actions --}}
        saveDraft() {
            if (this.quizModalOpen) {
                this.saveQuizDraft();
                return;
            }
            if (this.videoModalOpen) {
                this.saveVideoDraft();
                return;
            }

            axios.post('{{ route('sme.masterclass.draft', $blueprint->id) }}', {
                curriculum_structure: this.chapters
            })
            .then(res => {
                if (res.data.success) {
                    window.dispatchEvent(new CustomEvent('toast', { detail: { title: 'Tersimpan!', message: res.data.message, type: 'success' } }));
                }
            })
            .catch(err => {
                console.error(err);
                Alert.error('Gagal', 'Terjadi kesalahan saat menyimpan draft kurikulum.');
            });
        },

        checkVideoDraft(chapterIndex) {
            const chapterId = (this.chapters[chapterIndex]?.id || 'chapter-1') + '_video';
            axios.get(`{{ route('sme.video.draft.get', $blueprint->id) }}?chapter_id=${chapterId}`)
            .then(res => {
                if (res.data.success && res.data.has_draft) {
                    Alert.confirm('Draf Video Ditemukan', `Sistem mendeteksi adanya draf materi video yang belum tersimpan (disimpan pada ${res.data.draft.last_saved_at}). Apakah Anda ingin memulihkan data draf tersebut?`)
                    .then((result) => {
                        if (result.isConfirmed) {
                            this.activeItemForm = res.data.draft.payload;
                            this.draftStatusText = `✅ Draf dipulihkan (${res.data.draft.last_saved_at})`;
                            window.dispatchEvent(new CustomEvent('toast', { detail: { title: 'Draf Dipulihkan', message: 'Data materi video berhasil dipulihkan dari draf terakhir.', type: 'success' } }));
                        }
                    });
                }
            })
            .catch(err => console.error('Gagal mengecek draf video:', err));
        },

        saveVideoDraft() {
            if (this.isSavingDraft) return;
            this.isSavingDraft = true;
            this.draftStatusText = 'Menyimpan draf...';

            const chapterId = (this.chapters[this.activeItemChapterIndex]?.id || 'chapter-1') + '_video';

            axios.post('{{ route('sme.video.draft.save', $blueprint->id) }}', {
                chapter_id: chapterId,
                payload: this.activeItemForm
            })
            .then(res => {
                if (res.data.success) {
                    this.draftStatusText = `✅ Draf diamankan (${res.data.last_saved_at})`;
                    window.dispatchEvent(new CustomEvent('toast', { detail: { title: 'Draf Video Disimpan', message: res.data.message, type: 'success' } }));
                }
            })
            .catch(err => {
                console.error(err);
                this.draftStatusText = '❌ Gagal menyimpan draf';
                Alert.error('Gagal', 'Terjadi kesalahan saat menyimpan draf video ke cloud.');
            })
            .finally(() => {
                this.isSavingDraft = false;
            });
        },

        saveQuizDraft() {
            if (this.isSavingDraft) return;
            this.isSavingDraft = true;
            this.draftStatusText = 'Menyimpan draf...';

            if (window.quillEditorInstance) {
                this.activeItemForm.questionsList[this.activeQuestionIndex].questionText = window.quillEditorInstance.root.innerHTML;
            }

            const chapterId = this.chapters[this.activeItemChapterIndex]?.id || 'chapter-1';

            axios.post('{{ route('sme.quiz.draft.save', $blueprint->id) }}', {
                chapter_id: chapterId,
                payload: this.activeItemForm
            })
            .then(res => {
                if (res.data.success) {
                    this.draftStatusText = `✅ Draf diamankan (${res.data.last_saved_at})`;
                    window.dispatchEvent(new CustomEvent('toast', { detail: { title: 'Draf Kuis Disimpan', message: res.data.message, type: 'success' } }));
                }
            })
            .catch(err => {
                console.error(err);
                this.draftStatusText = '❌ Gagal menyimpan draf';
                Alert.error('Gagal', 'Terjadi kesalahan saat menyimpan draf kuis ke cloud.');
            })
            .finally(() => {
                this.isSavingDraft = false;
            });
        },

        submitFinal() {
            Alert.confirm('Submit Final Kurikulum?', 'Kurikulum ini akan dikirim ke Learning Administrator untuk ditinjau pada Pagar Kedua sebelum dipublikasikan ke siswa.')
            .then((result) => {
                if (result.isConfirmed) {
                    axios.post('{{ route('sme.masterclass.submit', $blueprint->id) }}', {
                        curriculum_structure: this.chapters
                    })
                    .then(res => {
                        if (res.data.success) {
                            Alert.success('Berhasil Disubmit!', res.data.message)
                            .then(() => {
                                window.location.href = res.data.redirect;
                            });
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        Alert.error('Gagal', 'Terjadi kesalahan saat mengirim kurikulum final.');
                    });
                }
            });
        }
    };
}
</script>
<script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@botom/quill-resize-module@latest/dist/quill-resize-module.js"></script>
<script src="https://cdn.jsdelivr.net/npm/quill-image-resize-module@3.0.0/image-resize.min.js"></script>
<script>
    if (window.Quill) {
        if (window.QuillResizeModule) {
            Quill.register("modules/botomResize", window.QuillResizeModule);
        }
        if (window.ImageResize) {
            Quill.register("modules/imageResize", window.ImageResize.default || window.ImageResize);
        }
    }
</script>
@endsection