<x-filament-panels::page>
    <form wire:submit="save">
        {{ $this->form }}
        
        <div class="mt-6">
            {{ $this->saveAction }}
        </div>
    </form>
    
    <x-filament-actions::modals />
</x-filament-panels::page>
