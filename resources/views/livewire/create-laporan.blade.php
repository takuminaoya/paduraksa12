<div>

    <style>
        /* custom css disini */
    </style>

     <form wire:submit="create">
        {{ $this->form }}
        
        <x-filament::button class="w-full mt-5" type="submit">
            <x-tabler-plus />
            Laporkan
        </x-filament::button>
    </form>
    
    <x-filament-actions::modals />
</div>
