<?php

namespace App\Jobs;

use App\Models\Perjanjian;
use App\Services\SupabaseService;
use App\Http\Controllers\PerjanjianSupabaseController;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ExportPerjanjianPdfJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected Perjanjian $perjanjian;

    public function __construct(Perjanjian $perjanjian)
    {
        $this->perjanjian = $perjanjian;
    }

    public function handle(SupabaseService $supabase)
    {
        app(PerjanjianSupabaseController::class)
            ->generateAndUploadPdf($this->perjanjian);
    }
}