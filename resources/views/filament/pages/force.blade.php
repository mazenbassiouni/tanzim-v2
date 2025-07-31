<x-filament-panels::page>
    <div class="fi-ta-ctn divide-y divide-gray-200 overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:divide-white/10 dark:bg-gray-900 dark:ring-white/10">
        <div class="fi-ta-content relative divide-y divide-gray-200 overflow-x-auto dark:divide-white/10 dark:border-t-white/10 !border-t-0">
            <table class="fi-ta-table w-full table-auto divide-y divide-gray-200 text-center dark:divide-white/5" style="table-layout: fixed; text-align: center;">
                <thead class="divide-y divide-gray-200 dark:divide-white/5">
                    <tr class="bg-gray-50 dark:bg-white/5">
                        @php
                            $columns = ['الوحدة', 'قوة', 'ضباط', 'أفراد', 'ضباط الصف', 'جنود']
                        @endphp
                        @foreach ($columns as $i => $col)
                            <th class="fi-ta-header-cell px-3 py-3.5 sm:first-of-type:ps-6 sm:last-of-type:pe-6" style=";">
                                <span @class([
                                        'group flex w-full items-center gap-x-1 whitespace-nowrap',
                                        'justify-center' => $i !== 0,
                                        'justify-start' => $i === 0,
                                ])>
                                    <span class="fi-ta-header-cell-label text-sm font-semibold text-gray-950 dark:text-white">
                                        {{ $col }}
                                    </span>
                                </span>
                            </th>
                        @endforeach
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-200 whitespace-nowrap dark:divide-white/5">
                    @foreach ($tamam as $key => $unit)    
                        <tr class="fi-ta-row [@media(hover:hover)]:transition [@media(hover:hover)]:duration-75 hover:bg-gray-50 dark:hover:bg-white/5 {{ !$loop->last ?: 'bg-gray-50 dark:bg-white/5' }}" wire:key="RtBxpzEYkAoYKnaK5Tdv.table.records.817">
                            <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3 fi-table-cell-military-num" wire:key="RtBxpzEYkAoYKnaK5Tdv.table.record.817.column.military_num">
                                <div class="fi-ta-col-wrp">
                                    <div class="flex w-full disabled:pointer-events-none justify-start text-start">
                                        <div class="fi-ta-text grid w-full gap-y-1 px-3 py-4">
                                            <div class="flex justify-start">
                                                <div class="flex max-w-max" style="">
                                                    <div class="fi-ta-text-item inline-flex items-start gap-1.5  ">
                                                        <span class="fi-ta-text-item-label text-sm leading-6 text-gray-950 dark:text-white  " style="">
                                                            {{ $key }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3 fi-table-cell-military-num" wire:key="RtBxpzEYkAoYKnaK5Tdv.table.record.817.column.military_num">
                                <div class="fi-ta-col-wrp">
                                    <div class="flex w-full disabled:pointer-events-none justify-center text-center">
                                        <div class="fi-ta-text grid w-full gap-y-1 px-3 py-4">
                                            <div class="flex justify-center">
                                                <div class="flex max-w-max" style="">
                                                    <div class="fi-ta-text-item inline-flex items-center gap-1.5  ">
                                                        <span class="fi-ta-text-item-label text-sm leading-6 text-gray-950 dark:text-white" style="">
                                                            {{ $unit['officers'] + $unit['subOfficers'] + $unit['soldiers'] }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3 fi-table-cell-military-num" wire:key="RtBxpzEYkAoYKnaK5Tdv.table.record.817.column.military_num">
                                <div class="fi-ta-col-wrp">
                                    <div class="flex w-full disabled:pointer-events-none justify-start text-start">
                                        <div class="fi-ta-text grid w-full gap-y-1 px-3 py-4">
                                            <div class="flex justify-center">
                                                <div class="flex max-w-max" style="">
                                                    <div class="fi-ta-text-item inline-flex items-center gap-1.5  ">
                                                        <span class="fi-ta-text-item-label text-sm leading-6 text-gray-950 dark:text-white  " style="">
                                                            {{ $unit['officers'] }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3 fi-table-cell-military-num" wire:key="RtBxpzEYkAoYKnaK5Tdv.table.record.817.column.military_num">
                                <div class="fi-ta-col-wrp">
                                    <div class="flex w-full disabled:pointer-events-none justify-start text-start">
                                        <div class="fi-ta-text grid w-full gap-y-1 px-3 py-4">
                                            <div class="flex justify-center">
                                                <div class="flex max-w-max" style="">
                                                    <div class="fi-ta-text-item inline-flex items-center gap-1.5  ">
                                                        <span class="fi-ta-text-item-label text-sm leading-6 text-gray-950 dark:text-white  " style="">
                                                            {{ $unit['subOfficers'] + $unit['soldiers'] }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3 fi-table-cell-military-num" wire:key="RtBxpzEYkAoYKnaK5Tdv.table.record.817.column.military_num">
                                <div class="fi-ta-col-wrp">
                                    <div class="flex w-full disabled:pointer-events-none justify-start text-start">
                                        <div class="fi-ta-text grid w-full gap-y-1 px-3 py-4">
                                            <div class="flex justify-center">
                                                <div class="flex max-w-max" style="">
                                                    <div class="fi-ta-text-item inline-flex items-center gap-1.5  ">
                                                        <span class="fi-ta-text-item-label text-sm leading-6 text-gray-950 dark:text-white  " style="">
                                                            {{ $unit['subOfficers'] }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3 fi-table-cell-military-num" wire:key="RtBxpzEYkAoYKnaK5Tdv.table.record.817.column.military_num">
                                <div class="fi-ta-col-wrp">
                                    <div class="flex w-full disabled:pointer-events-none justify-start text-start">
                                        <div class="fi-ta-text grid w-full gap-y-1 px-3 py-4">
                                            <div class="flex justify-center">
                                                <div class="flex max-w-max" style="">
                                                    <div class="fi-ta-text-item inline-flex items-center gap-1.5  ">
                                                        <span class="fi-ta-text-item-label text-sm leading-6 text-gray-950 dark:text-white  " style="">
                                                            {{ $unit['soldiers'] }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="grid flex-1 auto-cols-fr gap-y-8" x-data="{ activeTab: 'outsideMissions' }">
        <x-filament::tabs label="Content tabs">
            <x-filament::tabs.item alpine-active="activeTab === 'outsideMissions'" x-on:click="activeTab = 'outsideMissions'">
                مأمورية عمل
                <x-slot name="badge">{{ $this->peopleQuery(59)->count() }}</x-slot>
            </x-filament::tabs.item>
    
            <x-filament::tabs.item alpine-active="activeTab === 'ousideAttached'" x-on:click="activeTab = 'ousideAttached'">
                إلحاق خارج الوحدة
                <x-slot name="badge">{{ $this->peopleQuery(21)->count() }}</x-slot>
            </x-filament::tabs.item>
    
            <x-filament::tabs.item alpine-active="activeTab === 'insideMissions'" x-on:click="activeTab = 'insideMissions'">
                مأمورية عمل طرفنا
                <x-slot name="badge">{{ $this->peopleQuery(60, false)->count() }}</x-slot>
            </x-filament::tabs.item>
    
            <x-filament::tabs.item alpine-active="activeTab === 'insideAttached'" x-on:click="activeTab = 'insideAttached'">
                إلحاق على الوحدة
                <x-slot name="badge">{{ $this->peopleQuery(20)->count() }}</x-slot>
            </x-filament::tabs.item>
        </x-filament::tabs>
    
        <x-filament::section 
            icon="heroicon-o-arrow-left-start-on-rectangle"
            x-show="activeTab === 'outsideMissions'"
            x-transition:enter="transition ease-out duration-1000"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
        >
            <x-slot name="heading">
                مأمورية عمل
            </x-slot>
    
            <livewire:tables.outside-missions />
        </x-filament::section>

        <x-filament::section 
            icon="heroicon-o-arrow-left-start-on-rectangle"
            x-show="activeTab === 'ousideAttached'"
            x-transition:enter="transition ease-out duration-1000"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
        >
            <x-slot name="heading">
                إلحاق خارج الوحدة
            </x-slot>
    
            <livewire:tables.outside-attached />
        </x-filament::section>

        <x-filament::section 
            icon="heroicon-o-arrow-left-end-on-rectangle"
            x-show="activeTab === 'insideMissions'"
            x-transition:enter="transition ease-out duration-1000"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
        >
            <x-slot name="heading">
                مأمورية عمل طرفنا
            </x-slot>
    
            <livewire:tables.inside-missions />
        </x-filament::section>
    
        <x-filament::section 
            icon="heroicon-o-arrow-left-end-on-rectangle"
            x-show="activeTab === 'insideAttached'"
            x-transition:enter="transition ease-out duration-1000"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
        >
            <x-slot name="heading">
                إلحاق على الوحدة
            </x-slot>
    
            <livewire:tables.inside-attached />
        </x-filament::section>
    </div>
</x-filament-panels::page>
