<?php

namespace App\Http\Controllers;

use App\Models\Perjanjian;
use App\Helpers\PdfHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * Controller untuk generate PDF menggunakan Snappy (wkhtmltopdf)
 * Supports landscape & portrait orientations
 * 
 * Endpoints:
 * - GET /perjanjian/{id}/pdf/download - Download PDF
 * - GET /perjanjian/{id}/pdf/preview - Preview PDF di browser
 * - POST /perjanjian/{id}/pdf/save - Save ke storage
 */
class BrowsershotPdfController extends Controller
{

  /**
   * Download PDF Perjanjian
   *
   * @param int $id
   * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
   */
  public function download($id): \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
  {
    try {
      $perjanjian = Perjanjian::findOrFail($id);
      $pdfContent = PdfHelper::generatePerjanjianSnappy($perjanjian);
      $fileName = PdfHelper::generateFilename($perjanjian);

      return response($pdfContent)
        ->header('Content-Type', 'application/pdf')
        ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"');
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
      return response()->json([
        'success' => false,
        'message' => 'Perjanjian tidak ditemukan',
      ], 404);
    } catch (\Exception $e) {
      Log::error('BrowsershotPdfController download error: ' . $e->getMessage(), [
        'id' => $id,
        'trace' => $e->getTraceAsString(),
      ]);

      return response()->json([
        'success' => false,
        'message' => 'Gagal generate PDF: ' . $e->getMessage(),
      ], 500);
    }
  }

  /**
   * Preview/Stream PDF Perjanjian di browser
   *
   * @param int $id
   * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
   */
  public function preview($id): \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
  {
    try {
      $perjanjian = Perjanjian::findOrFail($id);
      $pdfContent = PdfHelper::generatePerjanjianSnappy($perjanjian);
      $fileName = PdfHelper::generateFilename($perjanjian);

      return response($pdfContent)
        ->header('Content-Type', 'application/pdf')
        ->header('Content-Disposition', 'inline; filename="' . $fileName . '"');
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
      return response()->json([
        'success' => false,
        'message' => 'Perjanjian tidak ditemukan',
      ], 404);
    } catch (\Exception $e) {
      Log::error('BrowsershotPdfController preview error: ' . $e->getMessage(), [
        'id' => $id,
        'trace' => $e->getTraceAsString(),
      ]);

      return response()->json([
        'success' => false,
        'message' => 'Gagal generate PDF: ' . $e->getMessage(),
      ], 500);
    }
  }

  /**
   * Save PDF ke storage (local atau Supabase)
   *
   * @param Request $request
   * @param int $id
   * @return \Illuminate\Http\JsonResponse
   */
  public function save(Request $request, $id)
  {
    try {
      $perjanjian = Perjanjian::findOrFail($id);
      $pdfContent = PdfHelper::generatePerjanjianSnappy($perjanjian);
      $fileName = PdfHelper::generateFilename($perjanjian);
      
      $storage = $request->input('storage', 'local');

      if ($storage === 'supabase') {
        // Save to Supabase
        $folder = $request->input('folder', 'perjanjian-pdfs');
        $path = $folder . '/' . now('Asia/Jakarta')->format('Y/m') . '/' . $fileName;
        
        $result = app(\App\Services\SupabaseService::class)->uploadFile(
          $pdfContent,
          $fileName,
          $folder . '/' . now('Asia/Jakarta')->format('Y/m')
        );

        if ($result['success']) {
          $perjanjian->update([
            'pdf_url'  => $result['url'],
            'pdf_path' => $result['path'],
          ]);
        }

        return response()->json($result);
      } else {
        // Save to local storage
        $disk = $request->input('disk', 'local');
        $storePath = 'perjanjian-pdfs/' . now('Asia/Jakarta')->format('Y/m') . '/' . $fileName;
        
        \Illuminate\Support\Facades\Storage::disk($disk)->put($storePath, $pdfContent);

        $perjanjian->update([
          'pdf_path' => $storePath,
        ]);

        return response()->json([
          'success' => true,
          'message' => 'PDF saved successfully',
          'path' => $storePath,
        ]);
      }
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
      return response()->json([
        'success' => false,
        'message' => 'Perjanjian tidak ditemukan',
      ], 404);
    } catch (\Exception $e) {
      Log::error('BrowsershotPdfController save error: ' . $e->getMessage(), [
        'id' => $id,
        'trace' => $e->getTraceAsString(),
      ]);

      return response()->json([
        'success' => false,
        'message' => 'Gagal save PDF: ' . $e->getMessage(),
      ], 500);
    }
  }

  /**
   * Diagnostics endpoint untuk troubleshooting
   * Hanya tersedia di local environment
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function diagnostics()
  {
    if (!app()->environment('local')) {
      return response()->json([
        'success' => false,
        'message' => 'Diagnostics only available in local environment',
      ], 403);
    }

    $diagnostics = [
      'wkhtmltopdf_binary' => config('snappy.pdf.binary'),
      'wkhtmltopdf_exists' => file_exists(config('snappy.pdf.binary')),
      'php_version' => PHP_VERSION,
      'laravel_version' => app()->version(),
      'os' => php_uname(),
    ];

    // Test generate simple PDF
    $testResult = null;
    try {
      $viewData = [
        'for_pdf' => true,
        'test_mode' => true,
      ];
      
      $testHtml = view('perjanjian.pdf-snappy', $viewData)->render();
      $pdf = \Barryvdh\Snappy\Facades\SnappyPdf::loadHTML($testHtml);
      $pdf->setPaper('Folio');
      $pdf->setOrientation('Portrait');
      $pdfContent = $pdf->output();
      
      $testResult = [
        'success' => true,
        'message' => 'PDF generation test passed',
        'pdf_size' => strlen($pdfContent) . ' bytes',
      ];
    } catch (\Exception $e) {
      $testResult = [
        'success' => false,
        'message' => 'PDF generation test failed',
        'error' => $e->getMessage(),
      ];
    }

    return response()->json([
      'diagnostics' => $diagnostics,
      'test_result' => $testResult,
    ]);
  }

  /**
   * Test PDF dengan data sample
   * Hanya tersedia di local environment
   *
   * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
   */
  public function testPdf(): \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
  {
    if (!app()->environment('local')) {
      return response()->json([
        'success' => false,
        'message' => 'Test only available in local environment',
      ], 403);
    }

    try {
      $perjanjian = Perjanjian::first();

      if (!$perjanjian) {
        return response()->json([
          'success' => false,
          'message' => 'No perjanjian found for testing',
        ], 404);
      }

      $pdfContent = PdfHelper::generatePerjanjianSnappy($perjanjian);
      $fileName = 'test-snappy-' . now()->format('Y-m-d_His') . '.pdf';

      return response($pdfContent)
        ->header('Content-Type', 'application/pdf')
        ->header('Content-Disposition', 'inline; filename="' . $fileName . '"');
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'message' => 'Test failed: ' . $e->getMessage(),
        'trace' => $e->getTraceAsString(),
      ], 500);
    }
  }
}
