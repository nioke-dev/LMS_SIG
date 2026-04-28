@props(['existingFiles' => []])

<div x-data="{ 
    files: @js($existingFiles),
    dragging: false,
    handleFiles(event) {
        let incomingFiles = [];
        if (event.type === 'drop') {
            incomingFiles = Array.from(event.dataTransfer.files);
        } else {
            incomingFiles = Array.from(event.target.files);
        }

        const newFiles = incomingFiles.map(f => ({
            id: Date.now() + Math.random(),
            name: f.name,
            size: (f.size / 1024 / 1024).toFixed(2) + ' MB',
            raw: f
        }));
        this.files.push(...newFiles);
        if (event.target.type === 'file') {
            event.target.value = '';
        }
    },
    removeFile(index) {
        this.files.splice(index, 1);
    }
}" class="mb-8">
    <div class="flex items-center gap-4 mb-4">
        <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center text-primary">
            <span class="material-symbols-outlined">upload_file</span>
        </div>
        <h3 class="text-sm font-black text-on-surface tracking-tight uppercase">Dokumen Pendukung</h3>
    </div>

    <div class="relative group"
         @dragover.prevent="dragging = true"
         @dragleave.prevent="dragging = false"
         @drop.prevent="dragging = false; handleFiles($event)">
        
        <input type="file" multiple @change="handleFiles" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
        
        <div class="border-2 border-dashed rounded-[2rem] p-10 flex flex-col items-center transition-all duration-300 pointer-events-none"
             :class="dragging ? 'border-primary bg-primary/5 scale-[0.99]' : 'border-zinc-200 bg-zinc-50 group-hover:border-primary/50'">
            <div class="w-16 h-16 rounded-full bg-white shadow-sm flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                <span class="material-symbols-outlined text-3xl text-primary">cloud_upload</span>
            </div>
            <p class="text-sm font-bold text-on-surface mb-1">Tarik file ke sini atau klik untuk upload</p>
            <p class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest">PDF, Excel, atau JPG (Maks. 5MB)</p>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mt-4" x-show="files.length > 0">
        <template x-for="(file, index) in files" :key="file.id">
            <div class="flex items-center justify-between p-3 bg-white border border-zinc-100 rounded-2xl shadow-sm">
                <div class="flex items-center gap-3 overflow-hidden">
                    <div class="w-10 h-10 rounded-xl bg-zinc-50 flex items-center justify-center text-zinc-400 shrink-0">
                        <span class="material-symbols-outlined">description</span>
                    </div>
                    <div class="min-w-0">
                        <p class="text-xs font-bold text-on-surface truncate" x-text="file.name"></p>
                        <p class="text-[10px] font-medium text-zinc-400" x-text="file.size"></p>
                    </div>
                </div>
                <button type="button" @click="removeFile(index)" class="text-zinc-300 hover:text-red-500 transition-colors px-2">
                    <span class="material-symbols-outlined text-lg">delete</span>
                </button>
            </div>
        </template>
    </div>
</div>
