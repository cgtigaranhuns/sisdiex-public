<div>

    <x-filament::modal id="myModal">
       
    </x-filament::modal>

    <x-filament::button type="button" @click="$dispatch('open-modal', {id: 'myModal'})">Período</x-filament::button>

</div>