<?php

namespace App\Jobs;
use App\Models\Answer;
use App\Models\Element;
use App\Models\Bodypart;
use App\Models\Result;
use App\Mail\ShowResult;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use App\Mail\ShowResultPractitioner;
use Illuminate\Support\Facades\Http; 
use Barryvdh\Dompdf\Facade\Pdf;

class SaveResult implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $data;
    protected $id;
    /**
     * Create a new job instance.
     */
    public function __construct($data,$id)
    {
        $this->data = $data;
        $this->id = $id;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {   
        
        $url = 'https://hook.us1.make.com/v46mu776qe408lrpfe2xccrw3m8wxtjw';
        // $data = json_encode($this->data); // Get all data from the request (optional)
        try {
            $response = Http::timeout(60000)->post($url, $this->data);
            $responseBody = $response->json(); // Get the JSON response body
            //    $decoded_response = json_decode($responseBody, true);
            //    echo "<pre>";
            //    print_r($responseBody);
            //    echo "</pre>";
            //    die();
        } catch (RequestException $e) {
            Log::alert('Error sending data: '.$e->getMessage());
        }

        $result_insert = Result::find($this->id);

        $result_insert->total_a = @$responseBody['Total Evaluation']['Interpretation'];
        $result_insert->total_b = @$responseBody['Total Evaluation']['Recommendation'];
        $result_insert->total_c = @$responseBody['Total Evaluation']['Emotional Component'];
        
        $result_insert->fire_a = @$responseBody['Heart and Small Intestine']['Interpretation'];
        $result_insert->fire_b = @$responseBody['Heart and Small Intestine']['Recommendation'];
        $result_insert->fire_c = @$responseBody['Heart and Small Intestine']['Emotional Component'];

        $result_insert->earth_a = @$responseBody['Pancreas and Stomach']['Interpretation'];
        $result_insert->earth_b = @$responseBody['Pancreas and Stomach']['Recommendation'];
        $result_insert->earth_c = @$responseBody['Pancreas and Stomach']['Emotional Component'];

        $result_insert->metal_a = @$responseBody['Large Intestine and Lung']['Interpretation'];
        $result_insert->metal_b = @$responseBody['Large Intestine and Lung']['Recommendation'];
        $result_insert->metal_c = @$responseBody['Large Intestine and Lung']['Emotional Component'];

        $result_insert->water_a = @$responseBody['Bladder and Kidney']['Interpretation'];
        $result_insert->water_b = @$responseBody['Bladder and Kidney']['Recommendation'];
        $result_insert->water_c = @$responseBody['Bladder and Kidney']['Emotional Component'];

        $result_insert->wood_a = @$responseBody['Liver and Gall Bladder']['Interpretation'];
        $result_insert->wood_b = @$responseBody['Liver and Gall Bladder']['Recommendation'];
        $result_insert->wood_c = @$responseBody['Liver and Gall Bladder']['Emotional Component'];
        
        $result_insert->save();

        // // Email attachment 
        // $result = Result::where('id', $this->id)->first();
        // $answers = Answer::where('result_id', $this->id)->get();
        // $elements = [];
        // foreach($answers as $answer){
        //     $elements[$answer->element][strtoupper($answer->bodypart)][$answer->type]['excess'] = $answer->excess;
        //     $elements[$answer->element][strtoupper($answer->bodypart)][$answer->type]['balance'] = $answer->balance;
        //     $elements[$answer->element][strtoupper($answer->bodypart)][$answer->type]['insufficiency'] = $answer->insufficiency;
        // }
        // $elements_arr = Element::where('state', '1')->get();
        // foreach($elements_arr as $element_key => $element){
            
        //     if($element->title=="Fire"){
        //     $elements[$element->title]["message"]["a"] = $result->fire_a;
        //     $elements[$element->title]["message"]["b"] = $result->fire_b;
        //     $elements[$element->title]["message"]["c"] = $result->fire_c;
        //     }
        //     if($element->title=="Earth"){
        //         $elements[$element->title]["message"]["a"] = $result->earth_a;
        //         $elements[$element->title]["message"]["b"] = $result->earth_b;
        //         $elements[$element->title]["message"]["c"] = $result->earth_c;
        //     }
        //     if($element->title=="Metal"){
        //         $elements[$element->title]["message"]["a"] = $result->metal_a;
        //         $elements[$element->title]["message"]["b"] = $result->metal_b;
        //         $elements[$element->title]["message"]["c"] = $result->metal_c;
        //     }
        //     if($element->title=="Water"){
        //         $elements[$element->title]["message"]["a"] = $result->water_a;
        //         $elements[$element->title]["message"]["b"] = $result->water_a;
        //         $elements[$element->title]["message"]["c"] = $result->water_c;
        //     }
        //     if($element->title=="Wood"){
        //         $elements[$element->title]["message"]["a"] = $result->wood_a;
        //         $elements[$element->title]["message"]["b"] = $result->wood_b;
        //         $elements[$element->title]["message"]["c"] = $result->wood_c;
        //     }
                                    
        //     $bodypart_arr = Bodypart::where('element_id', $element->id)->get();
        //         foreach($bodypart_arr as $bodypart_key => $bodypart){

        //             if (!isset($elements[$element->title][strtoupper($bodypart->title)]['Physical']['excess'])) {
        //                 $elements[$element->title][strtoupper($bodypart->title)]['Physical']['excess']="0";
        //             }
        //             if (!isset($elements[$element->title][strtoupper($bodypart->title)]['Physical']['balance'])) {
        //                 $elements[$element->title][strtoupper($bodypart->title)]['Physical']['balance']="0";
        //             }
        //             if (!isset($elements[$element->title][strtoupper($bodypart->title)]['Physical']['insufficiency'])) {
        //                 $elements[$element->title][strtoupper($bodypart->title)]['Physical']['insufficiency']="0";
        //             }
        //             if (!isset($elements[$element->title][strtoupper($bodypart->title)]['Mental']['excess'])) {
        //                 $elements[$element->title][strtoupper($bodypart->title)]['Mental']['excess']="0";
        //             }
                    
        //             if (!isset($elements[$element->title][strtoupper($bodypart->title)]['Mental']['balance'])) {
        //                 $elements[$element->title][strtoupper($bodypart->title)]['Mental']['balance']="0";
        //             }
        //             if (!isset($elements[$element->title][strtoupper($bodypart->title)]['Mental']['insufficiency'])) {
        //                 $elements[$element->title][strtoupper($bodypart->title)]['Mental']['insufficiency']="0";
        //             }
        //             if (!isset($elements[$element->title][strtoupper($bodypart->title)]['Total']['excess'])) {
        //                 $elements[$element->title][strtoupper($bodypart->title)]['Total']['excess']="0";
        //             }
        //             if (!isset($elements[$element->title][strtoupper($bodypart->title)]['Total']['balance'])) {
        //                 $elements[$element->title][strtoupper($bodypart->title)]['Total']['balance']="0";
        //             }
        //             if (!isset($elements[$element->title][strtoupper($bodypart->title)]['Total']['insufficiency'])) {
        //                 $elements[$element->title][strtoupper($bodypart->title)]['Total']['insufficiency']="0";
        //             }
                    
                    
        //         }
        // }
        //     $elements["total"]["message"]["a"] = $result->total_a;
        //     $elements["total"]["message"]["b"] = $result->total_b;
        //     $elements["total"]["message"]["c"] = $result->total_c;
        //     // $pdf = Pdf::loadView('result_detail_download', ['elements' => $elements,'answers' => $answers, 'result' => $result]);
        //     // Email attachment end
        //     // Sent Email to user
        //     // Create a Mailable instance
        //     $emailData = [
        //         "name" => auth()->user()->name, 
        //             "practitioner_name" => auth()->user()->practitioner->name,
        //             "excess" => $result_insert->excess,
        //             "insufficiency" => $result_insert->insufficiency, 
        //             "balance" => $result_insert->balance, 
        //             "total_a" => $result_insert->total_a, 
        //             "total_b" => $result_insert->total_b,
        //             "total_c" => $result_insert->total_c,
        //         // 'pdf' => $pdf,
        //     ];

        // $email = new ShowResult( 
        //     auth()->user()->name, 
        // auth()->user()->practitioner->name,
        // $result_insert->excess,
        //  $result_insert->insufficiency, 
        // $result_insert->balance, 
        //  $result_insert->total_a, 
        // $result_insert->total_b,
        //  $result_insert->total_c
        // );
        // // $email->attachPdf(""); 

        //     // Send the email
        //     Mail::to(auth()->user()->email)->send($email);
        //     // $pdf = Barryvdh\DomPDF\Facade\Pdf::loadView('result_detail_download', ['elements' => $elements,'answers' => $answers, 'result' => $result]);
        //     // $pdf_url = $pdf->download($this->id.'result_detail_download.pdf');
        //     // die($pdf_url);
        // //  Mail::to(auth()->user()->practitioner->email)->send(new ShowResultPractitioner(auth()->user()->name, auth()->user()->practitioner->name,$result_insert->id));
        // // Mail::to($this->data['email'])->send(new WelcomeEmail($this->data));
    }
}
