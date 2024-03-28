<?php

namespace App\Http\Controllers;

use App\Exports\TyreDataExport;
use App\Http\Requests\TyreCheckInRequest;
use App\Http\Requests\TyreUpdateRequest;
use App\Http\Services\ClientTyreService;
use App\Http\Services\TyreService;
use App\Models\Tyre;
use App\Repositories\TyreRepository;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;


class TyreController extends Controller
{
    protected ClientTyreService $clientTyreService;
    protected TyreRepository $tyreRepository;

    protected TyreService $tyreService;

    public function __construct(
        ClientTyreService $clientTyreService,
        TyreRepository $tyreRepository,
        TyreService $tyreService
    ) {
        $this->clientTyreService = $clientTyreService;
        $this->tyreRepository = $tyreRepository;
        $this->tyreService = $tyreService;
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

    public function index(Request $request): JsonResponse
    {
        $tyres = $this->tyreRepository->searchTyres($request->search);
        return response()->json($tyres);
    }

    public function checkout(Request $request, Tyre $tyre): JsonResponse
    {
        try {
            $this->tyreRepository->checkoutTyre($tyre);
            return response()->json(['message' => 'Checkout realizat cu succes!']);
        } catch (\Exception $e) {
            Log::error('Error in checkout: ' . $e->getMessage(), ['exception' => $e]);
            return response()->json(['message' => 'Eroare la checkout'], 500);
        }
    }

    public function generateCheckoutDocument(Request $request, Tyre $tyre): Response
    {
        $pdf = PDF::loadView('pdf.checkout_document', ['tyre' => $tyre]);
        return $pdf->download('checkout_document.pdf');
    }

    public function generateCheckinDocument(Request $request, Tyre $tyre): Response
    {
        $pdf = PDF::loadView('pdf.checkin_document', ['tyre' => $tyre]);
        return $pdf->download('checkin_document.pdf');
    }

    public function update(TyreUpdateRequest $request, Tyre $tyre): JsonResponse
    {
        $validated = $request->validated();
        try {
            $this->tyreService->update($tyre, $validated);

            return response()->json(['message' => 'Date modificare cu succes']);
        } catch (\Exception $e) {
            Log::error('Error in update: ' . $e->getMessage(), ['exception' => $e]);
            return response()->json(['message' => 'Eroare modificare date'], 500);
        }
    }
    public function getTyreAgeStats(Request $request): JsonResponse
    {
        $stats = $this->tyreService->getTyreAgeStats();
        return response()->json($stats);
    }

    public function export(Request $request): BinaryFileResponse
    {
        return Excel::download(new TyreDataExport, 'Raport-depozit.xlsx');
    }
}

