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

    //Update Employee Data
    // public function updateEmployeeData($id, $data){

    //     $employee = Employees::findOrFail($id);

    //     $updateData = [
    //         'first_name' => $data['first_name'] ?? $employee->first_name,
    //         'last_name' => $data['last_name'] ?? $employee->last_name,
    //         'gender' => $data['gender'] ?? $employee->gender,
    //         'date_of_birth' => $data['date_of_birth'] ?? $employee->date_of_birth,
    //         'nationality' => $data['nationality'] ?? $employee->nationality,
    //         'marital_status' => $data['marital_status'] ?? $employee->marital_status,
    //         'contact_number' => $data['contact_number'] ?? $employee->contact_number,
    //         'email' => $data['email'] ?? $employee->email,
    //         'permanent_address' => $data['permanent_address'] ?? $employee->permanent_address,
    //         'current_address' => $data['current_address'] ?? $employee->current_address,
    //     ];

    //     //Log::info("Incoming photo file: ", ['photo' => $data->file('photo')]);
    //     Log::info('Photo field content:', ['photo' => $data['photo'] ?? 'NOT SET']);

    //     //  Only handle photo if it exists
    //     if (isset($data['photo'])&& $data['photo'] instanceof \Illuminate\Http\UploadedFile) {

    //         $oldPhoto = $employee->photo;
    //         if ($oldPhoto && Storage::disk('public')->exists($oldPhoto)) {
    //             Storage::disk('public')->delete($oldPhoto);
    //         }
    //         Log::error("old photo" , $oldPhoto);

    //         $employeeId = $employee->employee_id;
    //         $empProfile = $data['photo'];
    //         $lowerCase = strtolower($employeeId);
    //         $profilePic = str_replace(' ', '-', $lowerCase) . '.' . $empProfile->extension();
    //         $profilePath = $empProfile->storeAs('employeeProfile', $profilePic, 'public');
    //         $updateData['photo'] = $profilePath;
    //     }
    //         $employee->update($updateData);
    //         return $employee;
    // }

}
