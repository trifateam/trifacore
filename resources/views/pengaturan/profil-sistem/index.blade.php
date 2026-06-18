@extends('layouts.app')

@section('content')
    <x-page-header title="Pengaturan Profil Sistem">
        <x-breadcrumb :items="[
            ['label' => 'Dashboard', 'url' => route('dashboard')],
            ['label' => 'Pengaturan'],
            ['label' => 'Profil Sistem']
        ]" />
    </x-page-header>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2">
            <x-card title="Konfigurasi Sistem">
                <form action="{{ route('pengaturan.profil-sistem.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <x-form-section title="Identitas Peternakan">
                        <div class="mb-4">
                            <x-input name="nama_peternakan" id="nama_peternakan" label="Nama Peternakan/Instansi" :value="old('nama_peternakan', setting('nama_peternakan'))" required maxlength="100" />
                        </div>
                        
                        <div class="mb-4">
                            <x-textarea name="alamat" id="alamat" label="Alamat Lengkap" required maxlength="500" rows="3">{{ old('alamat', setting('alamat')) }}</x-textarea>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <x-input name="no_telp" id="no_telp" label="No. Telp Peternakan" :value="old('no_telp', setting('no_telp'))" required />
                            <x-input name="email" id="email" type="email" label="Email Peternakan (Opsional)" :value="old('email', setting('email'))" />
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Logo Peternakan (Opsional)</label>
                            
                            <div class="flex items-center space-x-4">
                                @if(setting('logo_path') && Storage::disk('public')->exists(setting('logo_path')))
                                    <div class="relative w-20 h-20 rounded border border-gray-200 dark:border-gray-700 overflow-hidden flex-shrink-0 bg-gray-50 dark:bg-gray-700/50" id="current-logo-container">
                                        <img src="{{ Storage::url(setting('logo_path')) }}" alt="Logo" class="w-full h-full object-contain" id="preview-image">
                                    </div>
                                @else
                                    <div class="relative w-20 h-20 rounded border border-gray-200 dark:border-gray-700 flex items-center justify-center bg-gray-50 dark:bg-gray-700/50 flex-shrink-0" id="current-logo-container">
                                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        <img src="" alt="Logo" class="w-full h-full object-contain hidden absolute inset-0" id="preview-image">
                                    </div>
                                @endif
                                
                                <div class="flex-1">
                                    <input type="file" name="logo" id="logo-input" class="w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-blue-50 dark:bg-blue-900/30 file:text-blue-700 dark:text-blue-400 hover:file:bg-blue-100 dark:bg-blue-900/50" accept="image/jpeg,image/png">
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Format: JPG, PNG. Maksimal: 2MB.</p>
                                    
                                    <div class="mt-2 flex items-center">
                                        <input type="checkbox" name="remove_logo" id="remove_logo" value="1" class="rounded border-gray-300 dark:border-gray-600 text-blue-600 dark:text-blue-500 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                        <label for="remove_logo" class="ml-2 text-sm text-red-600 dark:text-red-500">Hapus Logo Saat Ini</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </x-form-section>

                    <x-form-section title="Informasi Pemilik & Manajemen">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <x-input name="nama_pemilik" label="Nama Pemilik" :value="old('nama_pemilik', setting('nama_pemilik'))" maxlength="100" />
                            <x-select name="jabatan_pemilik" label="Jabatan Pemilik">
                                <option value="">-- Pilih Jabatan --</option>
                                @foreach(['Owner', 'Manajer', 'Direktur', 'Komisaris'] as $jabatan)
                                    <option value="{{ $jabatan }}" @selected(old('jabatan_pemilik', setting('jabatan_pemilik')) == $jabatan)>{{ $jabatan }}</option>
                                @endforeach
                            </x-select>
                        </div>

                        <div class="mb-4">
                            <x-textarea name="visi_misi" label="Visi & Misi" maxlength="1000" rows="4">{{ old('visi_misi', setting('visi_misi')) }}</x-textarea>
                        </div>
                    </x-form-section>

                    <div class="mt-6 flex justify-end">
                        <x-button type="submit" variant="primary">
                            Simpan Profil Sistem
                        </x-button>
                    </div>
                </form>
            </x-card>
        </div>

        <div>
            <x-card title="Preview Kop Surat PDF">
                <div class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                    Pratinjau bagaimana identitas peternakan akan ditampilkan pada header dokumen cetak laporan PDF Anda.
                </div>
                
                <div class="border border-gray-300 dark:border-gray-600 p-4 bg-white dark:bg-gray-800 rounded flex items-center justify-between" style="font-family: Arial, sans-serif; min-height: 120px;">
                    @php
                        $previewLogo = null;
                        if(setting('logo_path') && Storage::disk('public')->exists(setting('logo_path'))) {
                            $previewLogo = Storage::url(setting('logo_path'));
                        }
                    @endphp
                    
                    <div style="width: 20%;" class="flex justify-center">
                        @if($previewLogo)
                            <img src="{{ $previewLogo }}" alt="Logo" style="max-width: 80px; max-height: 80px;" class="object-contain" id="pdf-preview-logo">
                        @else
                            <div style="width: 80px; height: 80px;" id="pdf-preview-logo-placeholder"></div>
                        @endif
                    </div>
                    
                    <div class="text-center" style="width: 60%;">
                        <h1 class="font-bold text-lg m-0 uppercase text-gray-800 dark:text-gray-200" id="pdf-preview-name">{{ setting('nama_peternakan') ?? 'NAMA PETERNAKAN' }}</h1>
                        <p class="text-xs m-0 text-gray-600 dark:text-gray-400 leading-snug mt-1" id="pdf-preview-address">
                            {!! nl2br(e(setting('alamat'))) ?? 'Alamat Peternakan' !!}
                        </p>
                        <p class="text-xs m-0 text-gray-600 dark:text-gray-400 leading-snug mt-1">
                            Telp: <span id="pdf-preview-phone">{{ setting('no_telp') ?? '-' }}</span> | Email: <span id="pdf-preview-email">{{ setting('email') ?? '-' }}</span>
                        </p>
                    </div>
                    
                    <div style="width: 20%;"></div>
                </div>
            </x-card>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const logoInput = document.getElementById('logo-input');
            const previewImage = document.getElementById('preview-image');
            const pdfPreviewLogo = document.getElementById('pdf-preview-logo');
            
            // Text Preview Elements
            const nameInput = document.getElementById('nama_peternakan');
            const addressInput = document.getElementById('alamat');
            const phoneInput = document.getElementById('no_telp');
            const emailInput = document.getElementById('email');
            
            const namePreview = document.getElementById('pdf-preview-name');
            const addressPreview = document.getElementById('pdf-preview-address');
            const phonePreview = document.getElementById('pdf-preview-phone');
            const emailPreview = document.getElementById('pdf-preview-email');

            // Image Upload Preview
            if (logoInput) {
                logoInput.addEventListener('change', function() {
                    const file = this.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            // Update form preview
                            previewImage.src = e.target.result;
                            previewImage.classList.remove('hidden');
                            
                            // Remove placeholder icon if exists
                            const svg = document.querySelector('#current-logo-container svg');
                            if(svg) svg.classList.add('hidden');
                            
                            // Update PDF preview
                            if(pdfPreviewLogo) {
                                pdfPreviewLogo.src = e.target.result;
                            } else {
                                // If there was no logo before, create one
                                const placeholder = document.getElementById('pdf-preview-logo-placeholder');
                                if(placeholder) {
                                    const img = document.createElement('img');
                                    img.src = e.target.result;
                                    img.alt = 'Logo';
                                    img.style.maxWidth = '80px';
                                    img.style.maxHeight = '80px';
                                    img.className = 'object-contain';
                                    img.id = 'pdf-preview-logo';
                                    placeholder.parentNode.replaceChild(img, placeholder);
                                }
                            }
                        }
                        reader.readAsDataURL(file);
                    }
                });
            }
            
            // Text Preview Live Update
            const setupLivePreview = (input, previewEl, defaultValue) => {
                if(input && previewEl) {
                    input.addEventListener('input', function() {
                        let val = this.value;
                        if(this.tagName.toLowerCase() === 'textarea') {
                            val = val.replace(/\n/g, '<br>');
                            previewEl.innerHTML = val || defaultValue;
                        } else {
                            previewEl.textContent = val || defaultValue;
                        }
                    });
                }
            };
            
            setupLivePreview(nameInput, namePreview, 'NAMA PETERNAKAN');
            setupLivePreview(addressInput, addressPreview, 'Alamat Peternakan');
            setupLivePreview(phoneInput, phonePreview, '-');
            setupLivePreview(emailInput, emailPreview, '-');
        });
    </script>
@endsection
