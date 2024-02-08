<?php

namespace App\Http\Controllers;

use App\Http\Requests\TyreCheckInRequest;
use App\Models\Tyre;
use App\Repositories\TyreRepository;
use App\Services\ClientTyreService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TyreController extends Controller
{
    protected ClientTyreService $clientTyreService;
    protected TyreRepository $tyreRepository;

    public function __construct(ClientTyreService $clientTyreService, TyreRepository $tyreRepository)
    {
        $this->clientTyreService = $clientTyreService;
        $this->tyreRepository = $tyreRepository;
    }

    public function checkIn(TyreCheckinRequest $request): JsonResponse
    {
        try {
            $client = $this->clientTyreService->checkIn($request->validated());
            return response()->json(['message' => 'Date salvate cu succes', 'client' => $client]);
        } catch (\Exception $e) {
            Log::error('Error in checkIn: ' . $e->getMessage(), ['exception' => $e]);
            return response()->json(['message' => 'Eroare salvare date'], 500);
        }
    }

    public function index(Request $request)
    {
        $tyres = $this->tyreRepository->searchTyres($request->search);
        return response()->json($tyres);
    }

    public function checkout(Request $request, Tyre $tyre)
    {
        try {
            $this->tyreRepository->checkoutTyre($tyre);
            return response()->json(['message' => 'Checkout realizat cu succes!']);
        } catch (\Exception $e) {
            Log::error('Error in checkout: ' . $e->getMessage(), ['exception' => $e]);
            return response()->json(['message' => 'Eroare la checkout'], 500);
        }
    }

    public function generateCheckoutDocument(Request $request, Tyre $tyre): \Illuminate\Http\Response
    {

        $pdf = PDF::loadView('pdf.checkout_document', $tyre->toArray());
        return $pdf->download('checkout_document.pdf');
    }
}

