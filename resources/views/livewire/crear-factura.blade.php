
<div>
    {{-- Formulario --}}
    <form wire:submit.prevent="createInvoice" class="space-y-6">

        <div>
            <label class="text-lg font-semibold mb-2">Rango de numeración </label>

            <div class="grid grid-cols-3 gap-4">
                <select wire:model="numbering_range_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-black">
                    <option value="">Seleccione un rango</option>
                    <option value="8">Factura de Venta (SETP)</option>
                    <option value="9">Nota Crédito (NC)</option>
                    <option value="10">Nota Débito (ND)</option>
                </select>
            </div>
            @error('numbering_range_id') <span class="text-red-500">Campo Obligatorio</span> @enderror
        </div>

        <hr class="my-4 border-t border-gray-200" />


        {{-- Cliente --}}
<div>
    <h3 class="text-lg font-semibold mb-2">Datos de Cliente</h3>

    <div class="p-4 bg-slate-50 rounded-lg mb-4 border border-gray-200">

        <div class="p-4 bg-slate-100 rounded-lg mb-4 border border-gray-200">
            <div class="grid grid-cols-3 gap-4">
                {{-- Part 1 --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700">Tipo de documento *</label>
                    <select wire:model="customer.identification_document_id" wire:change="checkDV" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-black">
                        <option value="">Seleccionar</option>
                        <option value="1">Registro civil</option>
                        <option value="2">Tarjeta de identidad</option>
                        <option value="3">Cédula de ciudadanía</option>
                        <option value="4">Tarjeta de extranjería (Colombia)</option>
                        <option value="5">Cédula de extranjería (Otro país)</option>
                        <option value="6">NIT</option>
                        <option value="7">Pasaporte</option>
                        <option value="8">Documento de identificación extranjero</option>
                        <option value="9">PEP</option>
                        <option value="10">NIT otro país</option>
                        <option value="11">NUIP</option>
                    </select>
                    @error('customer.identification_document_id') <span class="text-red-500">Campo Obligatorio</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Numero Identificación *</label>
                    <input type="number" wire:model="customer.identification" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-black" />
                    @error('customer.identification') <span class="text-red-500">Campo Obligatorio</span> @enderror
                </div>

                @if ($showDV)
                    <div>
                        <label class="block text-sm font-medium text-gray-700">DV *</label>
                        <input type="text" wire:model="customer.dv" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-black" />
                        @error('customer.dv') <span class="text-red-500">Campo Obligatorio</span> @enderror
                    </div>
                @endif
                {{-- /Part 1 --}}
            </div>
        </div>

        <div class="p-4 bg-slate-100 rounded-lg mb-4 border border-gray-200">
            <div class="grid grid-cols-3 gap-4">
                {{-- Part 2 --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700">Tipo de Organización *</label>
                    <select wire:model="customer.legal_organization_id" wire:change="checkCompanyAndNames" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-black">
                        <option value="">Seleccionar</option>
                        <option value="1">Persona Jurídica</option>
                        <option value="2">Persona Natural</option>
                    </select>
                    @error('customer.legal_organization_id') <span class="text-red-500">Campo Obligatorio</span> @enderror
                </div>

                @if ($showCompany)
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nombre de Compañia *</label>
                        <input type="text" wire:model="customer.company" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-black" />
                        @error('customer.company') <span class="text-red-500">Campo Obligatorio</span> @enderror
                    </div>
                @endif

                @if ($showNames)
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nombre Completo *</label>
                        <input type="text" wire:model="customer.names" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-black" />
                        @error('customer.names') <span class="text-red-500">Campo Obligatorio</span> @enderror
                    </div>
                @endif

                <div>
                    <label class="block text-sm font-medium text-gray-700">Tipo de Tributo *</label>
                    <select wire:model="customer.tribute_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-black">
                        <option value="">Seleccionar</option>
                        <option value="18">IVA</option>
                        <option value="21">No aplica</option>
                    </select>
                    @error('customer.tribute_id') <span class="text-red-500">Campo Obligatorio</span> @enderror
                </div>
                {{-- /Part 2 --}}
            </div>
        </div>

        <div class="p-4 bg-slate-100 rounded-lg mb-4 border border-gray-200">
            <div class="grid grid-cols-3 gap-4">

                {{-- Part 3 --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700">Dirección</label>
                    <input type="text" wire:model="customer.address" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-black" />
                    @error('customer.address') <span class="text-red-500">Campo Obligatorio</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" wire:model="customer.email" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-black" />
                    @error('customer.email') <span class="text-red-500">Campo Obligatorio</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Teléfono</label>
                    <input type="number" wire:model="customer.phone" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-black"/>
                    @error('customer.phone') <span class="text-red-500">Campo Obligatorio</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Municipio</label>
                    <select wire:model="customer.municipality_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-black">
                        <option value="">Selecciona</option>
                        @foreach ($municipalities as $municipality)
                            <option value="{{$municipality['id']}}">{{$municipality['name']}}</option>
                        @endforeach
                    </select>
                    @error('customer.municipality_id') <span class="text-red-500">Campo Obligatorio</span> @enderror
                </div>
                {{-- /Part 3 --}}
            </div>
        </div>

    </div>
</div>


            {{-- Datos Generales --}}
<div>
    <h3 class="text-lg font-semibold mb-2 text-white">Datos Generales</h3>
    <div class="p-4 bg-slate-50 rounded-lg mb-4 border border-gray-200">
        <div class="grid grid-cols-3 gap-4">

            <div>
                <label class="block text-sm font-medium text-gray-700">Código de referencia</label>
                <input disabled type="text" wire:model="reference_code" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm bg-gray-100 text-black" />
                @error('reference_code') <span class="text-red-500">Campo Obligatorio</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Método de pago</label>
                <select wire:model="payment_form" wire:change="checkPaymentDueDate" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-black">
                  <option value="">Seleccionar</option>
                  <option value="1">Pago de Contado</option>
                  <option value="2">Pago a crédito</option>
                </select>
                @error('payment_form') <span class="text-red-500">Campo Obligatorio</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Medio de Pago</label>
                <select wire:model="payment_method_code" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-black">
                    <option value="">Seleccionar</option>
                    <option value="10">Efectivo</option>
                    <option value="42">Consignación</option>
                    <option value="20">Cheque</option>
                    <option value="47">Transferencia</option>
                    <option value="71">Bonos</option>
                    <option value="72">Vales</option>
                    <option value="1">Medio de pago no definido</option>
                    <option value="49">Tarjeta Débito</option>
                    <option value="48">Tarjeta Crédito</option>
                    <option value="ZZZ">Otro</option>
                </select>
                @error('payment_method_code') <span class="text-red-500">Campo Obligatorio</span> @enderror
            </div>

            @if ($showPaymentDueDate)
                <div>
                    <label class="block text-sm font-medium text-gray-700">Fecha de vencimiento de la factura</label>
                    <input type="date" wire:model="payment_due_date" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-black" />
                    @error('payment_due_date') <span class="text-red-500">Campo Obligatorio</span> @enderror
                </div>
            @endif

        </div>
        <div class="mt-2">
            <label class="block text-sm font-medium text-gray-700">Observación</label>
            <textarea wire:model="observation" rows="2" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-black"></textarea>
            @error('observation') <span class="text-red-500">Campo Obligatorio</span> @enderror
        </div>
    </div>
</div>


{{-- Productos --}}
<div>
    <h3 class="text-lg font-semibold mb-2 text-white">Productos</h3>
    <div class="p-4 bg-slate-50 rounded-lg mb-4 border border-gray-200">

        @foreach ($items as $index => $item)
            <div class="p-4 bg-slate-50 rounded-lg mb-4 border border-gray-200">
                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nombre *</label>
                        <input type="text" wire:model="items.{{ $index }}.name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-black bg-slate-200" />
                        @error("items.$index.name") <span class="text-red-500">Campo Obligatorio</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Cantidad *</label>
                        <input type="number" wire:model="items.{{ $index }}.quantity" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-black bg-slate-200" />
                        @error("items.$index.quantity") <span class="text-red-500">Campo Obligatorio</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Precio *</label>
                        <input type="number" step="1000" wire:model="items.{{ $index }}.price" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-black bg-slate-200" />
                        @error("items.$index.price") <span class="text-red-500">Campo Obligatorio</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tasa de impuestos (%) *</label>
                        <input type="number" wire:model="items.{{ $index }}.tax_rate" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-black bg-slate-200" />
                        @error("items.$index.tax_rate") <span class="text-red-500">Campo Obligatorio</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tasa de Descuento (%) *</label>
                        <input type="number" wire:model="items.{{ $index }}.discount_rate" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-black bg-slate-200" />
                        @error("items.$index.discount_rate") <span class="text-red-500">Campo Obligatorio</span> @enderror
                    </div>
                </div>
                <button type="button" wire:click.prevent="removeItem({{ $index }})" class="bg-red-500 hover:bg-red-700 text-white font-bold mt-4 py-2 px-4 rounded">Eliminar Producto</button>
            </div>
        @endforeach
        <button type="button" wire:click.prevent="addItem" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Agregar Producto</button>
    </div>
</div>

            @if ($notification)
    <div class="relative z-50" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <!-- Fondo Oscuro -->
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity" aria-hidden="true"></div>

        <div class="fixed inset-0 flex items-center justify-center z-50">
            <div class="relative bg-white rounded-xl shadow-2xl p-6 max-w-md w-full">
                <!-- Encabezado -->
                <div class="flex items-center space-x-3 border-b pb-4">
                    <div class="w-12 h-12 flex items-center justify-center rounded-full bg-green-100">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8 text-green-600">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900">Factura Generada</h3>
                </div>

                <!-- Contenido -->
                <div class="mt-4 text-gray-700 text-sm">
                    <p>{{ $notification['message'] }}</p>
                    @if (array_key_exists('invoiceNumber', $notification))
                        <p class="mt-2 font-semibold">Número de Factura: <span class="text-gray-800">{{ $notification['invoiceNumber'] }}</span></p>
                        <p class="font-semibold">Código de Referencia: <span class="text-gray-800">{{ $notification['invoiceReferenceCode'] }}</span></p>
                    @endif
                </div>

                <!-- Botones -->
                <div class="mt-6 flex justify-end space-x-3">
                    <button type="button" wire:click="closeModal" class="px-4 py-2 text-sm font-semibold text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 transition">
                        Cerrar
                    </button>
                    @if (array_key_exists('invoiceNumber', $notification))
                        <button wire:click="downloadPdf('{{ $notification['invoiceNumber'] }}')" class="px-4 py-2 text-sm font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition">
                            Descargar QR
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endif
       

{{-- Botón de envío --}}
<div>
    <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 flex items-center justify-center rounded-lg w-full transition-all duration-300">
        <!-- Mostrar solo el texto cuando no se está cargando -->
        <span wire:loading.remove wire:target="createInvoice">
            Crear Factura
        </span>
    </button>
</div>

        </form>

        {{-- Estado de Carga --}}
<div wire:loading wire:target="createInvoice">
    <div class="fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center z-50">
        <div class="bg-white p-6 rounded-lg shadow-lg flex flex-col items-center">
            <div class="relative w-24 h-24">
                <div class="absolute inset-0 border-4 border-gray-300 rounded animate-spin-slow">
                    <div class="w-full h-full bg-gradient-to-r from-blue-500 to-green-500 animate-pulse rounded"></div>
                </div>
            </div>
            <p class="mt-4 text-gray-700 font-semibold">Generando factura...</p>
        </div>
    </div>
</div>


