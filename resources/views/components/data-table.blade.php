@props(['headers', 'createRoute' => null, 'createLabel' => 'Tambah Data', 'title' => 'Data'])

<div class="card table-card">
    <div class="card-header">
        <h5 class="mb-0 fw-bold" style="font-size: 1rem;">{{ $title }}</h5>
        @if($createRoute)
            <a href="{{ $createRoute }}" class="btn btn-sm btn-primary">
                + {{ $createLabel }}
            </a>
        @endif
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    @foreach($headers as $header)
                        <th>{{ $header }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                {{ $slot }}
            </tbody>
        </table>
    </div>
</div>
