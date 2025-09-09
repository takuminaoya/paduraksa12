<div>
    @php
        dd($record);
    @endphp

    @foreach ($record->lampiran as $item)
        {{ $item }}
    @endforeach
</div>
