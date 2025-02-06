<div>
    <x-filament::section>
        <x-slot name="heading">
            Lista de Facturas
        </x-slot>
        <div class="grid grid-cols-3 gap-4 mb-4">
    <div>
        <label class="block text-sm font-medium text-gray-700 w-full">Numero de Factura</label>
        <div class="relative flex items-center">
            <input type="text" wire:model.live="filters.number" wire:change="loadInvoicesWithFilters" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm pl-8 pr-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-black" />
            <svg class="absolute right-3 top-1/2 transform -translate-y-1/2 h-5 w-5 text-gray-500" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
            </svg>
        </div>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 w-full">Numero de Referencia</label>
        <div class="relative flex items-center">
            <input type="text" wire:model.live="filters.reference_code" wire:change="loadInvoicesWithFilters" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm pl-8 pr-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-black" />
            <svg class="absolute right-3 top-1/2 transform -translate-y-1/2 h-5 w-5 text-gray-500" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
            </svg>
        </div>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 w-full">Identificacion</label>
        <div class="relative flex items-center">
            <input type="text" wire:model.live="filters.identification" wire:change="loadInvoicesWithFilters" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm pl-8 pr-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-black" />
            <svg class="absolute right-3 top-1/2 transform -translate-y-1/2 h-5 w-5 text-gray-500" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
            </svg>
        </div>
    </div>
</div>
   

        <div class="overflow-x-auto">
            <table class="table-auto w-full border border-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2 border-b text-left text-black">Factura</th>
                        <th class="px-4 py-2 border-b text-left text-black">Cliente</th>
                        <th class="px-4 py-2 border-b text-left text-black">Total</th>
                        <th class="px-4 py-2 border-b text-left text-black">Estado</th>
                        <th class="px-4 py-2 border-b text-left text-black">Fecha</th>
                        <th class="px-4 py-2 border-b text-left text-black">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($facturas['data'] as $factura)
                        <tr>
                            <td class="px-4 py-2 border-b">{{ $factura['number'] }}</td>
                            <td class="px-4 py-2 border-b">{{ $factura['api_client_name'] }}</td>
                            <td class="px-4 py-2 border-b">${{ number_format($factura['total'], 2) }}</td>
                            <td class="px-4 py-2 border-b">
                                @if ($factura['status'] === 1)
                                    <span class="text-green-500">Activo</span>
                                @else
                                    <span class="text-red-500">Inactivo</span>
                                @endif
                            </td>
                            <td class="px-4 py-2 border-b">{{ $factura['created_at'] }}</td>
                            <td class="px-4 py-2 border-b">
                                <x-filament::button wire:click="downloadPdf('{{ $factura['number'] }}')" size="xs" icon="heroicon-c-document-arrow-down" color="danger">
                                    PDF
                                </x-filament::button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-2 text-center text-gray-500">No se encontraron facturas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            <nav class="flex items-center justify-between">
                <div class="hidden sm:flex sm:items-center sm:justify-between">
                    <span class="text-sm text-gray-700">
                        Mostrando {{ $facturas['pagination']['from'] }} a {{ $facturas['pagination']['to'] }} de {{ $facturas['pagination']['total'] }} resultados
                    </span>

                    <div>
                        {{-- Botón fijo para la página 1 --}}
                        @if ($facturas['pagination']['current_page'] > 4)
                            <button wire:click="goToPage(1)" class="px-4 py-2 mx-1 text-sm {{ $facturas['pagination']['current_page'] == 1 ? 'bg-blue-500 text-white' : 'bg-gray-100 text-gray-700' }} rounded-md shadow-sm">
                                1
                            </button>
                            <span class="px-2 text-gray-500">...</span>
                        @endif

                        @foreach ($facturas['pagination']['links'] as $link)
                            @if ((($link['url']) && array_key_exists('page', $link)))
                                <button wire:click="goToPage({{ $link['page'] }})" class="px-4 py-2 mx-1 text-sm {{ $link['active'] ? 'bg-blue-500 text-white' : 'bg-gray-100 text-gray-700' }} rounded-md shadow-sm">
                                    {!! $link['label'] !!}
                                </button>
                            @endif
                        @endforeach
                    </div>
                </div>
            </nav>
        </div>
    </x-filament::section>

    {{-- <x-filament::pagination :paginator="$facturas" /> --}}
</div>
