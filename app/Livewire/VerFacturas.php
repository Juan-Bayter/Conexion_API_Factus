<?php

namespace App\Livewire;

use GuzzleHttp\Client;
use Livewire\Component;
use Livewire\WithPagination;
use App\Services\AuthFactusService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use GuzzleHttp\Exception\RequestException;

class VerFacturas extends Component
{
    use WithPagination;

    public $facturas;
    public $filters = [
        'identification' => null,
        'number' => null,
        'reference_code' => null,
    ];

    public function mount()
    {
        $this->loadInvoices();
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

    public function loadInvoicesWithFilters()
    {
        $this->loadInvoices();
    }

    public function goToPage($page)
    {
        // Verifica que el número de página esté dentro del rango válido
        if ($page >= 1 && $page <= $this->facturas['pagination']['last_page']) {
            $this->loadInvoices($page);
        }
    }

    public function loadInvoices($page = 1)
    {
        $authFactusService = new AuthFactusService;

        $url = 'https://api-sandbox.factus.com.co/v1/bills';

        // Construir los parámetros de filtro dinámicamente
        $queryParams = [];

        $queryParams['page'] = $page;

        foreach ($this->filters as $key => $value) {
            if (!empty($value)) {
                $queryParams["filter[$key]"] = $value;
            }
        }

        $accessToken = $authFactusService->getValidToken();

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . $accessToken,
            ])->get($url, $queryParams);

            $this->facturas = $response->json()['data'] ?? [];
        } catch (\Exception $e) {
            session()->flash('error', 'Error al cargar las facturas: ' . $e->getMessage());
            $this->facturas = [];
        }
    }

    public function render()
    {
        return view('livewire.ver-facturas');
    }
}
