    <div class="header">
        <table>
            <tr>
                @if(isset($settings['logo_path']) && $settings['logo_path'])
                    @php
                        // Coba ambil dari storage
                        $logo = storage_path('app/public/' . $settings['logo_path']);
                        if(!file_exists($logo)) {
                            $logo = public_path($settings['logo_path']);
                        }
                    @endphp
                    @if(file_exists($logo))
                        <td width="10%">
                            <img src="{{ $logo }}" class="logo" alt="Logo">
                        </td>
                    @endif
                @endif
                <td style="text-align: center;">
                    <h1 class="company-name">{{ $settings['nama_peternakan'] ?? 'NAMA PETERNAKAN' }}</h1>
                    <p class="company-info">
                        {{ $settings['alamat'] ?? 'Alamat Peternakan' }}<br>
                        Telp: {{ $settings['no_telp'] ?? '-' }} | Email: {{ $settings['email'] ?? '-' }}
                    </p>
                </td>
                <td width="10%"></td> <!-- Spacer for centering -->
            </tr>
        </table>
    </div>
