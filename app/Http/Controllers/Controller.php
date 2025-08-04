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


        /* Log::info('Updating Job Title', [$request->input('job_title')]);
            Log::info('Updating Branch Name', [$request->input('branch_name')]);
            Log::info('Has job details?', [$employee->job_details]);
            Log::info('Has salary?', [$employee->salary]);
            Log::info('Has bank details?', [$employee->salary?->bank_details]);*/


            // 'job_title' => $request->input('job_title', $employee->jobDetails->job_title),
              // 'department_id' => $request->input('department_id', $employee->jobDetails->department_id),
              // 'employee_type' => $request->input('employee_type', $employee->jobDetails->employee_type),
              // 'employment_status' => $request->input('employment_status', $employee->jobDetails->employment_status),
              // 'joining_date' => $request->input('joining_date', $employee->jobDetails->joining_date),
              // 'probation_period' => $request->input('probation_period', $employee->jobDetails->probation_period),
              //  'work_location' => $request->input('work_location', $employee->jobDetails->work_location),
              //  'updated_by' => $commonServices->getUserID()

            // 'base_salary' => $request->input('base_salary', $employee->salary->base_salary),
                  // 'pay_grade' => $request->input('pay_grade', $employee->salary->pay_grade),
                  // 'pay_frequency' => $request->input('pay_frequency', $employee->salary->pay_frequency)

                // 'first_name'        => $request->input('first_name', $employee->first_name),
                // 'last_name'         => $request->input('last_name', $employee->last_name),
                // 'gender'            => $request->input('gender', $employee->gender),
                // 'date_of_birth'     => $request->input('date_of_birth', $employee->date_of_birth),
                // 'nationality'       => $request->input('nationality', $employee->nationality),
                // 'marital_status'    => $request->input('marital_status', $employee->marital_status),
                // 'contact_number'    => $request->input('contact_number', $employee->contact_number),
                // 'email'             => $request->input('email', $employee->email),
                // 'updated_by' => $commonServices->getUserID()


    //     document.getElementById("submitAll").addEventListener("click", function () {
    //     if (
    //         validatePersonalInfo() &&
    //         validateAddress() &&
    //         validateBank() &&
    //         validateJob() &&
    //         validateSalary()
    //     ) {
    //         alert("Form is valid! Submitting...");
    //         // Submit your form data via AJAX or standard POST
    //     } else {
    //         alert("Please fill all required fields correctly.");
    //     }
    // });


//             ['tab2', 'tab3', 'tab4'].forEach(tabId => {
//     document.querySelector(`label[for="${tabId}"]`).addEventListener('click', function (e) {
//         e.preventDefault();

//         if (validation()) {
//             document.getElementById(tabId).checked = true;
//         } else {
//             alert("Please complete this section before continuing.");
//         }
//     });
// });

                // public function sendOTP($data){

    //   try{
    //     $otp = rand(100000, 999999);
    //     Session::put('register_data',[
    //         'name' => $data['name'],
    //         'email' => $data['email'],
    //         'user_phone_num' => $data['user_phone_num'],
    //         'password' => $data['password'],
    //         'role_id'=>$data['role_id'],  
    //         'otp' => $otp
    //     ]);
    //      Session::save();
    //      Log::error('Full session', session()->all());
    //     $contactNo = "+91".$data['user_phone_num'];
    //     Log::error('contact_no', ['contact_no' => $contactNo]);
    //     //Twilio Send SMS
    //     $twilio = new Client(
    //       config('services.twilio.sid'),
    //       config('services.twilio.token')
    //     );
    //     $from = config('services.twilio.sms_from');
    //     $twilio->messages->create($contactNo,[
    //       'from' => $from,
    //       'body' => "Your OTP is: $otp"
    //     ]);

    //     return true;

    //   }catch(Exception $e){
    //       Log::error('error in '. $e->getMessage());
    //   }
    // }


    // public function verifyOTP($otp){
    //     $data = Session::get('register_data');
    //      Session::save();
    //      Log::error('user details', ['user' => $data]);


    //     if (!$data || !isset($data['otp'])) {
    //       Log::error('Session expired or OTP missing.');
    //       return [
    //           'OTPerror' => true,
    //           'message' => ' Session expired. Please register again.'
    //       ];
    //   }
}
