<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;


    // MAIL_MAILER=smtp
    // MAIL_HOST=mailpit
    // MAIL_PORT=1025
    // MAIL_USERNAME=null
    // MAIL_PASSWORD=null
    // MAIL_ENCRYPTION=null
    // MAIL_FROM_ADDRESS="r.rajeshramesh2025@gmail.com"
    // MAIL_FROM_NAME="${APP_NAME}"




    //generate pdf
    /* public function generatePdf($invoiceId,InvoiceService $invoiceService)
    {
        try{
            $data = $invoiceService->generatePdf($invoiceId);

            if (!$data) {
                return response()->json(['message' => 'Invoice or Company not found'], 404);
            }

            $pdf = Pdf::loadView('pdf.invoicePdf', $data);

            $file_name = 'invoice-' . $data['invoice']->invoice_no . '.pdf';
            $file_path = 'invoice/' . $file_name;
            Storage::disk('public')->put($file_path, $pdf->output());

            return response()->json(['message' => 'PDF generated', 'path' => $file_path]);
        }catch(Exception $e){

            return response()->json([
                'status' => false,
                'message' => 'Something went wrong.',
                'error' => $e->getMessage(),
            ], 500);
        }
     
    }*/
}
