<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReportRequest;
use App\Jobs\CreateReport;
use App\Service\Reports\ReportService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ManageController extends Controller
{
    public function __construct(
        public ReportService $reportService
    )
    {}

    public function report(ReportRequest $request)
    {
        $rand = Str::uuid()->toString();
        $now = Carbon::now()->format('His');

        $name = "report_{$rand}_{$now}.xlsx";

        CreateReport::dispatch($name, $request->user(), ...$request->safe()->all());

        return response()->json([
            'message' => "Report will be send at you email {$request->user()->email} when created."
        ]);
    }

    public function downloadReport(Request $request, string $filename)
    {
        if ($request->hasValidSignature())
            return response()->download(Storage::disk('reports')->path($filename));

        return response()->json(['message' => 'Invalid signature'], 403);
    }
}
