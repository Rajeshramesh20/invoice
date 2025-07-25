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


    // ->when($startDate && $endDate, function ($query) use($startDate, $endDate) {
                //   return $query->whereHas('jobDetails', function ($q) use ($startDate, $endDate) {
                //         $q->whereBetween('joining_date', [$startDate, $endDate]);
                //     });
                // })

                // ->when($startDate && !$endDate, function ($query, $startDate) {
                //   return $query->whereHas('jobDetails', function ($q) use ($startDate) {
                //         $q->whereDate('joining_date', $startDate);
                //     });
                // })


                        // document.getElementById("nextToAddress").onclick = () => {
                    //  // if(!validation()){
                    //  //  return;
                    //  // }
                    //     employeeInfo.style.display = "none";
                    //     employeeAddress.style.display = "block";
                    // };

                    // document.getElementById("nextToBank").onclick = () => {
                    //  // if(!employeesAddress()){
                    //  //  return;
                    //  // }
                        
                    //     employeeAddress.style.display = "none";
                    //     employeeBank.style.display = "block";
                    // };


                    // document.getElementById("nextToJob").onclick = () => {                       
                    //  // if(!employeeBandDetail()){
                    //  //  return;
                    //  // }
                    //     employeeBank.style.display = "none";
                    //     employeeJob.style.display = "block";
                    // };

                    // document.getElementById("nextToSalary").onclick = () => {
                    //  // if(!jobDetailsValidation()){
                    //  //  return;
                    //  // }
                        
                    //     employeeJob.style.display = "none";
                    //     employeeSalary.style.display = "block";
                    // };


                    // //previous navigate()
                    // //salary to job
                    //  document.getElementById("previousJob").onclick = () => {
                    //     employeeSalary.style.display = "none";
                    //     employeeJob.style.display = "block";
                    // };

                    // //job to Bank
                    // document.getElementById("previousEmployeeBank").onclick = () => {
                    //     employeeJob.style.display = "none";
                    //     employeeBank.style.display = "block";
                    // };

                    //  //Bank to Address
                    // document.getElementById("previousEmployeeAddress").onclick = () => {
                    //     employeeBank.style.display = "none";
                    //     employeeAddress.style.display = "block";
                    // };

                    // //Address to employee
                    // document.getElementById("previousEmployeeInfo").onclick = () => {
                    //     employeeAddress.style.display = "none";
                    //     employeeInfo.style.display = "block";
                    // };                                   

    // document.getElementById('photo').value = data.photo || '';
                        // if (data.photo) {
                        //   document.getElementById('profilePreview').src = 'http://127.0.0.1:8000/storage/' + data.photo;
                        // }


            // //Employee Bankdetails
        // if($employee->salary->bankDetails){
        //     $employee->salary->bankDetails->update([
        //       'bank_name' => $request->input('bank_name', $employee->salary->bankDetails->bank_name),
        //       'account_holder_name' => $request->input('account_holder_name', $employee->salary->bankDetails->account_holder_name),
        //       'account_number' => $request->input('account_number', $employee->salary->bankDetails->account_number),
        //       'ifsc_code' => $request->input('ifsc_code', $employee->salary->bankDetails->ifsc_code),
        //       'branch_name' => $request->input('branch_name', $employee->salary->bankDetails->branch_name),
        //       'account_type' => $request->input('account_type', $employee->salary->bankDetails->account_type),
        //       'updated_by' => $commonServices->getUserID()
        //     ]);
        // }

        //employee Address 
        // if($employee->address){
        //     $employee->address->update([
        //         'line1' => $request->input('line1', $employee->line1),
        //         'line2' => $request->input('line2', $employee->line2),
        //         'line3' => $request->input('line3', $employee->line3),
        //         'line4' => $request->input('line4', $employee->line4),
        //         'pincode' => $request->input('pincode', $employee->pincode),
        //         'updated_by' => $commonServices->getUserID()
        //     ]);
        // }
}
