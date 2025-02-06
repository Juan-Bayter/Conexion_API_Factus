<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use App\Services\AuthFactusService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class CrearFactura extends Component
{
    public $municipalities = [];
    public $numbering_range_id;
    public $reference_code;
    public $observation;
    public $payment_form;
    public $payment_due_date;
    public $payment_method_code;
    public $billing_period = [
        'start_date' => '',
        'start_time' => '',
        'end_date' => '',
        'end_time' => '',
    ];
    public $customer = [
        'identification' => '',
        'dv' => '',
        'company' => '',
        'trade_name' => '',
        'names' => '',
        'address' => '',
        'email' => '',
        'phone' => '',
        'legal_organization_id' => '',
        'tribute_id' => '',
        'identification_document_id' => '',
        'municipality_id' => '',
    ];
    public $items = [];

    public $itemTemplate = [
        'code_reference' => '',
        'name' => '',
        'quantity' => 1,
        'discount_rate' => 0,
        'price' => 0,
        'tax_rate' => '19.00',
        'unit_measure_id' => '70',
        'standard_code_id' => '1',
        'is_excluded' => 0,
        'tribute_id' => '1',
        'withholding_taxes' => [],
    ];

    public $showDV = false;
    public $showCompany = false;
    public $showNames = false;
    public $showPaymentDueDate = false;
    public $notification = null;

    public function checkPaymentDueDate()
    {
        $this->showPaymentDueDate = $this->payment_form == '2';
    }

    public function checkCompanyAndNames()
    {
        $this->showCompany = $this->customer['legal_organization_id'] == '1';
        $this->showNames = $this->customer['legal_organization_id'] == '2';
    }

    public function checkDV()
    {
        $this->showDV = $this->customer['identification_document_id'] == '6';
    }

    public function addItem()
    {
        $this->itemTemplate['code_reference'] = Str::random(5);
        $this->items[] = $this->itemTemplate;
    }

    public function removeItem($index)
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
    }

    public function validateInvoice()
    {
        $rules = [
            'numbering_range_id' => ['required'],
            'reference_code' => ['required', 'string', 'max:255'],
            'customer.identification_document_id' => ['required'],
            'customer.identification' => ['required', 'integer'],
            'customer.legal_organization_id' => ['required'],
            'customer.tribute_id' => ['required'],
            'items.*.code_reference' => ['required', 'string', 'max:255'],
            'items.*.name' => ['required', 'string', 'max:255'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'items.*.price' => ['required', 'numeric', 'min:0'],
            'items.*.tax_rate' => ['required', 'numeric', 'min:0', 'max:100'],
            'items.*.discount_rate' => ['required', 'numeric', 'min:0', 'max:100'],
        ];

        // Validación condicional si la forma de pago es a crédito
        if ($this->payment_form == 2) {
            $rules['payment_due_date'] = ['required', 'date'];
        }

        // Validación del DV si el tipo de identificación es NIT (ID 6)
        if ($this->customer['identification_document_id'] == 6) {
            $rules['customer.dv'] = ['required', 'string', 'max:10'];
        } else {
            $rules['customer.dv'] = ['nullable'];
        }

        // Validación de campos condicionales para el cliente
        if ($this->customer['legal_organization_id'] == 1) { // Persona jurídica
            $rules['customer.company'] = ['required', 'string', 'max:255'];
        } else {
            $rules['customer.company'] = ['nullable'];
        }

        // Ejecutamos la validación
        $this->validate($rules);
    }

    public function downloadPdf($facturaNumber)
    {
        $authFactusService = new AuthFactusService;

        $url = 'https://api-sandbox.factus.com.co/v1/bills/download-pdf/' . $facturaNumber;
        $accessToken = $authFactusService->getValidToken();

        // Realizar la solicitud a la API con los parámetros de paginación
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $accessToken,
        ])->get($url)->json();

        if ($response['status'] === 'OK') {

            $fileName = $response['data']['file_name'] . '.pdf';
            $pdfContent = base64_decode($response['data']['pdf_base_64_encoded']);

            $filePath = 'facturas/' . $fileName;

            // Guardar el archivo en el sistema de almacenamiento
            Storage::put($filePath, $pdfContent);

            return Storage::download($filePath);
        }
    }

    public function closeModal()
    {
        $this->notification = null;
    }

    public function getTokenFromAuthService()
    {
        $authFactusService = new AuthFactusService;
        $accessToken = $authFactusService->getValidToken();

        return $accessToken;
    }

    public function getMunicipalities()
    {
        $urlMunicipalities = 'https://api-sandbox.factus.com.co/v1/municipalities';
        $accessToken = $this->getTokenFromAuthService();

        // Realizar la solicitud a la API con los parámetros de paginación
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $accessToken,
        ])->get($urlMunicipalities)->json();

        if ($response['status'] === 'OK') {

            $this->municipalities = $response['data'];

            return $response['data'];
        }
    }

    public function setInitialData()
    {
        $data = [
            "numbering_range_id" => 8,
            "reference_code" => "51cZ32M9MGG3",
            "observation" => "",
            "payment_form" => "1",
            "payment_due_date" => "2024-12-30",
            "payment_method_code" => "10",
            "billing_period" => [
                "start_date" => "2025-01-1",
                "start_time" => "00:00:00",
                "end_date" => "2026-06-03",
                "end_time" => "23:59:59"
            ],
            "customer" => [
                "identification" => "104254651",
                "dv" => "3",
                "company" => "",
                "trade_name" => "",
                "names" => "Moisés Corcho",
                "address" => "calle 1 # 2-68",
                "email" => "moises@enigmasas.com",
                "phone" => "1234567890",
                "legal_organization_id" => "2",
                "tribute_id" => "21",
                "identification_document_id" => "3",
                "municipality_id" => "512"
            ],
            "items" => [
                [
                    "code_reference" => "12345",
                    "name" => "producto de prueba",
                    "quantity" => 1,
                    "discount_rate" => 20,
                    "price" => 50000,
                    "tax_rate" => "19.00",
                    "unit_measure_id" => 70,
                    "standard_code_id" => 1,
                    "is_excluded" => 0,
                    "tribute_id" => 1,
                    "withholding_taxes" => [
                        [
                            "code" => "06",
                            "withholding_tax_rate" => "7.00"
                        ],
                        [
                            "code" => "05",
                            "withholding_tax_rate" => "15.00"
                        ]
                    ]
                ],
                [
                    "code_reference" => "54321",
                    "name" => "producto de prueba 2",
                    "quantity" => 1,
                    "discount_rate" => 0,
                    "price" => 50000,
                    "tax_rate" => "5.00",
                    "unit_measure_id" => 70,
                    "standard_code_id" => 1,
                    "is_excluded" => 0,
                    "tribute_id" => 1,
                    "withholding_taxes" => []
                ]
            ]
        ];

        $this->numbering_range_id = $data['numbering_range_id'] ?? null;
        $this->reference_code = $data['reference_code'] ?? null;
        $this->observation = $data['observation'] ?? null;
        $this->payment_form = $data['payment_form'] ?? null;
        $this->payment_due_date = $data['payment_due_date'] ?? null;
        $this->payment_method_code = $data['payment_method_code'] ?? null;
        $this->billing_period = $data['billing_period'] ?? $this->billing_period;
        $this->customer = $data['customer'] ?? $this->customer;
        $this->items = $data['items'] ?? [];
    }

    public function createInvoice()
    {
        $this->validateInvoice();

        $payload = [
            'numbering_range_id' => $this->numbering_range_id,
            'reference_code' => $this->reference_code,
            'observation' => $this->observation,
            'payment_form' => $this->payment_form,
            'payment_due_date' => $this->payment_due_date,
            'payment_method_code' => $this->payment_method_code,
            'billing_period' => $this->billing_period,
            'customer' => $this->customer,
            'items' => $this->items,
        ];

        $accessToken = $this->getTokenFromAuthService();

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . $accessToken,
            ])->post('https://api-sandbox.factus.com.co/v1/bills/validate', $payload);


            $responseData = $response->json();

            if ($response->successful()) {
                $this->notification = [
                    'type' => 'success',
                    'message' => 'Factura creada con éxito.',
                    'invoiceNumber' => $responseData['data']['bill']['number'],
                    'invoiceReferenceCode' => $responseData['data']['bill']['reference_code']
                ];
            } else {
                $this->notification = [
                    'type' => 'error',
                    'message' => 'Error al crear la factura: ' . $responseData
                ];
            }
        } catch (\Exception $e) {

            $this->notification = [
                'type' => 'error',
                'message' => 'Excepción al crear la factura: ' . $e->getMessage()
            ];
        }
    }

    public function mount()
    {
        //Just For Test
        $this->setInitialData();

        $this->getMunicipalities();

        // Inicializar con un producto vacío
        $this->reference_code = Str::random(12);
    }

    public function render()
    {
        return view('livewire.crear-factura');
    }
}
