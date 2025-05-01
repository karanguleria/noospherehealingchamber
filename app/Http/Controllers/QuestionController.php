<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Element;
use App\Models\Bodypart;
use App\Models\Question;
use App\Models\Result;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Mail;
use App\Mail\ShowResult;
use App\Mail\ShowResultPractitioner;
use App\Models\McqAnswer;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http; 
use App\Jobs\SaveResult;

class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        Cache::put('password_authenticated', 'value', 0);
        $questions = Question::where('state', '1')->get();
        
        $elements = Element::where('state', '1')->get();
        
        $elements_arr = [];
         foreach($elements as $key => $element){
            $elements_arr[$element->title]['name'] = $element->title;
            $elements_arr[$element->title]['seasone'] = $element->seasone;
            $elements_arr[$element->title]['description'] = $element->description;
            $elements_arr[$element->title]['image'] = $element->image;

         }
        $question_arr = [];

        foreach($questions as $key => $val){
            $type =  "Physical";
            if($val->type == 1){
                $type = "Physical";
            }else{
                $type ="Mental";
            }
            // Changed the key to sort the array
            if($val->element->title == "Fire"){
                $key_arr = "0_Fire" ;
            }else if($val->element->title == "Metal"){
                $key_arr = "2_Metal" ;
            }else if($val->element->title == "Earth"){
                $key_arr = "1_Earth" ;
            }else if($val->element->title == "Water"){
                $key_arr = "3_Water" ;
            }else if($val->element->title == "Wood"){
                $key_arr = "4_Wood" ;
            }
            $question_arr[$key_arr][$val->bodypart->title]['image'] = $val->bodypart->image;
            $question_arr[$key_arr][$val->bodypart->title][$type][$key]['question'] = $val->title;
            $question_arr[$key_arr][$val->bodypart->title][$type][$key]['id'] = $val->id;
            $question_arr[$key_arr][$val->bodypart->title][$type][$key]['option_a'] = $val->option_a;
            $question_arr[$key_arr][$val->bodypart->title][$type][$key]['option_b'] = $val->option_b;
            $question_arr[$key_arr][$val->bodypart->title][$type][$key]['option_c'] = $val->option_c;
        }
        // ksort done for sorting
        ksort($question_arr) ; 

        // loop to reset the keys back
        foreach($question_arr as $key => $val ){
            $key_data = explode("_",$key);    
            $question_arr_sorted[$key_data[1]] = $val;
        }

        return view('question', ['questions' => $question_arr_sorted,'elements_arr'=>$elements_arr]);
    }
    
    public function result(): View
    {
        $results = Result::where('user_id', auth()->user()->id)->get();

        return view('results', ['results' => $results]);
    }  

    /**
     * Display the specified resource.
     */
    public function resultShow(string $id)
    {
        $result = Result::where('id', $id)->first();
        $answers = Answer::where('result_id', $id)->get();
    
        foreach($answers as $answer){
            $elements[$answer->element][$answer->bodypart]['type'][] = (@$answer->type == "Total") ? "" : $answer->type ; 
            $elements[$answer->element][$answer->bodypart]['excess'][] = $answer->excess;
            $elements[$answer->element][$answer->bodypart]['balance'][] = $answer->balance;
            $elements[$answer->element][$answer->bodypart]['insufficiency'][] = $answer->insufficiency;
        }

        return view('result_detail', ['elements' => $elements, 'result' => $result,'answers' => $answers]);
    }
    public function resultShowUser(string $id)
    {

        $result = Result::where('id', $id)->first();
        $answers = Answer::where('result_id', $id)->get();
        $elements = [];
      
        foreach($answers as $answer){
            $elements[$answer->element][strtoupper($answer->bodypart)][$answer->type]['excess'] = $answer->excess;
            $elements[$answer->element][strtoupper($answer->bodypart)][$answer->type]['balance'] = $answer->balance;
            $elements[$answer->element][strtoupper($answer->bodypart)][$answer->type]['insufficiency'] = $answer->insufficiency;
        }
        $elements_arr = Element::where('state', '1')->get();
        foreach($elements_arr as $element_key => $element){
            
            if($element->title=="Fire"){
            $elements[$element->title]["message"]["a"] = $result->fire_a;
            $elements[$element->title]["message"]["b"] = $result->fire_b;
            $elements[$element->title]["message"]["c"] = $result->fire_c;
            }
            if($element->title=="Earth"){
                $elements[$element->title]["message"]["a"] = $result->earth_a;
                $elements[$element->title]["message"]["b"] = $result->earth_b;
                $elements[$element->title]["message"]["c"] = $result->earth_c;
            }
            if($element->title=="Metal"){
                $elements[$element->title]["message"]["a"] = $result->metal_a;
                $elements[$element->title]["message"]["b"] = $result->metal_b;
                $elements[$element->title]["message"]["c"] = $result->metal_c;
            }
            if($element->title=="Water"){
                $elements[$element->title]["message"]["a"] = $result->water_a;
                $elements[$element->title]["message"]["b"] = $result->water_b;
                $elements[$element->title]["message"]["c"] = $result->water_c;
            }
            if($element->title=="Wood"){
                $elements[$element->title]["message"]["a"] = $result->wood_a;
                $elements[$element->title]["message"]["b"] = $result->wood_b;
                $elements[$element->title]["message"]["c"] = $result->wood_c;
            }
                                    
            $bodypart_arr = Bodypart::where('element_id', $element->id)->get();
                foreach($bodypart_arr as $bodypart_key => $bodypart){

                    if (!isset($elements[$element->title][strtoupper($bodypart->title)]['Physical']['excess'])) {
                        $elements[$element->title][strtoupper($bodypart->title)]['Physical']['excess']="0";
                    }
                    if (!isset($elements[$element->title][strtoupper($bodypart->title)]['Physical']['balance'])) {
                        $elements[$element->title][strtoupper($bodypart->title)]['Physical']['balance']="0";
                    }
                    if (!isset($elements[$element->title][strtoupper($bodypart->title)]['Physical']['insufficiency'])) {
                        $elements[$element->title][strtoupper($bodypart->title)]['Physical']['insufficiency']="0";
                    }
                    if (!isset($elements[$element->title][strtoupper($bodypart->title)]['Mental']['excess'])) {
                        $elements[$element->title][strtoupper($bodypart->title)]['Mental']['excess']="0";
                    }
                    
                    if (!isset($elements[$element->title][strtoupper($bodypart->title)]['Mental']['balance'])) {
                        $elements[$element->title][strtoupper($bodypart->title)]['Mental']['balance']="0";
                    }
                    if (!isset($elements[$element->title][strtoupper($bodypart->title)]['Mental']['insufficiency'])) {
                        $elements[$element->title][strtoupper($bodypart->title)]['Mental']['insufficiency']="0";
                    }
                    if (!isset($elements[$element->title][strtoupper($bodypart->title)]['Total']['excess'])) {
                        $elements[$element->title][strtoupper($bodypart->title)]['Total']['excess']="0";
                    }
                    if (!isset($elements[$element->title][strtoupper($bodypart->title)]['Total']['balance'])) {
                        $elements[$element->title][strtoupper($bodypart->title)]['Total']['balance']="0";
                    }
                    if (!isset($elements[$element->title][strtoupper($bodypart->title)]['Total']['insufficiency'])) {
                        $elements[$element->title][strtoupper($bodypart->title)]['Total']['insufficiency']="0";
                    }       
                }
        }
            $elements["total"]["message"]["a"] = $result->total_a;
            $elements["total"]["message"]["b"] = $result->total_b;
            $elements["total"]["message"]["c"] = $result->total_c;

             $email = new ShowResult( 
                auth()->user()->name, 
                auth()->user()->practitioner->name,
                $result->excess,
                $result->insufficiency, 
                $result->balance, 
                $result->total_a, 
                $result->total_b,
                $result->total_c,
                $id
            );
            
            $pdf = PDF::loadView('result_detail_download', ['elements' => $elements,'answers' => $answers, 'result' => $result]);
            Storage::put('public/pdf/Nosphere Healing Results-'.$id.'.pdf', $pdf->output());
            
            // Sent Email to user
            Mail::to(auth()->user()->email)->send($email);
            
            // Send email to practitioner
            Mail::to(auth()->user()->practitioner->email)->send(new ShowResultPractitioner(auth()->user()->name,auth()->user()->practitioner->name,$id));

            $result = Result::where('id', $id)->first();
            $answers = Answer::where('result_id', $id)->get();
            $elements = [];
        
            foreach($answers as $answer){
                $elements[$answer->element][$answer->bodypart]['type'][] = (@$answer->type == "Total") ? "" : $answer->type ; 
                $elements[$answer->element][$answer->bodypart]['excess'][] = $answer->excess;
                $elements[$answer->element][$answer->bodypart]['balance'][] = $answer->balance;
                $elements[$answer->element][$answer->bodypart]['insufficiency'][] = $answer->insufficiency;
            }
        return view('result_detail_user', ['elements' => $elements, 'result' => $result,'answers' => $answers]);
    }
    
    /**
      *  New page for the result thnkyou for user only
    */
    public function resultThankyou(string $id)
    {
        $result = Result::where('id', $id)->first();
        $answers = Answer::where('result_id', $id)->get();
        $elements = [];
      
        foreach($answers as $answer){
            $elements[$answer->element][strtoupper($answer->bodypart)][$answer->type]['excess'] = $answer->excess;
            $elements[$answer->element][strtoupper($answer->bodypart)][$answer->type]['balance'] = $answer->balance;
            $elements[$answer->element][strtoupper($answer->bodypart)][$answer->type]['insufficiency'] = $answer->insufficiency;
        }
            
        $elements_arr = Element::where('state', '1')->get();
        foreach($elements_arr as $element_key => $element){
            
            if($element->title=="Fire"){
            $elements[$element->title]["message"]["a"] = $result->fire_a;
            $elements[$element->title]["message"]["b"] = $result->fire_b;
            $elements[$element->title]["message"]["c"] = $result->fire_c;
            }
            if($element->title=="Earth"){
                $elements[$element->title]["message"]["a"] = $result->earth_a;
                $elements[$element->title]["message"]["b"] = $result->earth_b;
                $elements[$element->title]["message"]["c"] = $result->earth_c;
            }
            if($element->title=="Metal"){
                $elements[$element->title]["message"]["a"] = $result->metal_a;
                $elements[$element->title]["message"]["b"] = $result->metal_b;
                $elements[$element->title]["message"]["c"] = $result->metal_c;
            }
            if($element->title=="Water"){
                $elements[$element->title]["message"]["a"] = $result->water_a;
                $elements[$element->title]["message"]["b"] = $result->water_b;
                $elements[$element->title]["message"]["c"] = $result->water_c;
            }
            if($element->title=="Wood"){
                $elements[$element->title]["message"]["a"] = $result->wood_a;
                $elements[$element->title]["message"]["b"] = $result->wood_b;
                $elements[$element->title]["message"]["c"] = $result->wood_c;
            }
                                    
            $bodypart_arr = Bodypart::where('element_id', $element->id)->get();
                foreach($bodypart_arr as $bodypart_key => $bodypart){

                    if (!isset($elements[$element->title][strtoupper($bodypart->title)]['Physical']['excess'])) {
                        $elements[$element->title][strtoupper($bodypart->title)]['Physical']['excess']="0";
                    }
                    if (!isset($elements[$element->title][strtoupper($bodypart->title)]['Physical']['balance'])) {
                        $elements[$element->title][strtoupper($bodypart->title)]['Physical']['balance']="0";
                    }
                    if (!isset($elements[$element->title][strtoupper($bodypart->title)]['Physical']['insufficiency'])) {
                        $elements[$element->title][strtoupper($bodypart->title)]['Physical']['insufficiency']="0";
                    }
                    if (!isset($elements[$element->title][strtoupper($bodypart->title)]['Mental']['excess'])) {
                        $elements[$element->title][strtoupper($bodypart->title)]['Mental']['excess']="0";
                    }
                    
                    if (!isset($elements[$element->title][strtoupper($bodypart->title)]['Mental']['balance'])) {
                        $elements[$element->title][strtoupper($bodypart->title)]['Mental']['balance']="0";
                    }
                    if (!isset($elements[$element->title][strtoupper($bodypart->title)]['Mental']['insufficiency'])) {
                        $elements[$element->title][strtoupper($bodypart->title)]['Mental']['insufficiency']="0";
                    }
                    if (!isset($elements[$element->title][strtoupper($bodypart->title)]['Total']['excess'])) {
                        $elements[$element->title][strtoupper($bodypart->title)]['Total']['excess']="0";
                    }
                    if (!isset($elements[$element->title][strtoupper($bodypart->title)]['Total']['balance'])) {
                        $elements[$element->title][strtoupper($bodypart->title)]['Total']['balance']="0";
                    }
                    if (!isset($elements[$element->title][strtoupper($bodypart->title)]['Total']['insufficiency'])) {
                        $elements[$element->title][strtoupper($bodypart->title)]['Total']['insufficiency']="0";
                    }       
                }
        }
            $elements["total"]["message"]["a"] = $result->total_a;
            $elements["total"]["message"]["b"] = $result->total_b;
            $elements["total"]["message"]["c"] = $result->total_c;

             $email = new ShowResult( 
                auth()->user()->name, 
                auth()->user()->practitioner->name,
                $result->excess,
                $result->insufficiency, 
                $result->balance, 
                $result->total_a, 
                $result->total_b,
                $result->total_c,
                $id
            );
            
            $pdf = PDF::loadView('result_detail_download', ['elements' => $elements,'answers' => $answers, 'result' => $result]);
            Storage::put('public/pdf/Nosphere Healing Results-'.$id.'.pdf', $pdf->output());
        
        // Sent Email to user
        // Mail::to(auth()->user()->email)->send($email);
        
        // Send email to practitioner
        Mail::to(auth()->user()->practitioner->email)->send(new ShowResultPractitioner(auth()->user()->name,auth()->user()->practitioner->name,$id));
        return view('result_thankyou');
    }

    /**
     * Display the specified resource.
     */
    public function download(string $id)
    {
        $result = Result::where('id', $id)->first();
        $answers = Answer::where('result_id', $id)->get();
        $elements = [];
        foreach($answers as $answer){
            $elements[$answer->element][strtoupper($answer->bodypart)][$answer->type]['excess'] = $answer->excess;
            $elements[$answer->element][strtoupper($answer->bodypart)][$answer->type]['balance'] = $answer->balance;
            $elements[$answer->element][strtoupper($answer->bodypart)][$answer->type]['insufficiency'] = $answer->insufficiency;
        }
        $elements_arr = Element::where('state', '1')->get();
        foreach($elements_arr as $element_key => $element){
            
            if($element->title=="Fire"){
            $elements[$element->title]["message"]["a"] = $result->fire_a;
            $elements[$element->title]["message"]["b"] = $result->fire_b;
            $elements[$element->title]["message"]["c"] = $result->fire_c;
            }
            if($element->title=="Earth"){
                $elements[$element->title]["message"]["a"] = $result->earth_a;
                $elements[$element->title]["message"]["b"] = $result->earth_b;
                $elements[$element->title]["message"]["c"] = $result->earth_c;
            }
            if($element->title=="Metal"){
                $elements[$element->title]["message"]["a"] = $result->metal_a;
                $elements[$element->title]["message"]["b"] = $result->metal_b;
                $elements[$element->title]["message"]["c"] = $result->metal_c;
            }
            if($element->title=="Water"){
                $elements[$element->title]["message"]["a"] = $result->water_a;
                $elements[$element->title]["message"]["b"] = $result->water_b;
                $elements[$element->title]["message"]["c"] = $result->water_c;
            }
            if($element->title=="Wood"){
                $elements[$element->title]["message"]["a"] = $result->wood_a;
                $elements[$element->title]["message"]["b"] = $result->wood_b;
                $elements[$element->title]["message"]["c"] = $result->wood_c;
            }
                                    
            $bodypart_arr =Bodypart::where('element_id', $element->id)->get();
        
            foreach($bodypart_arr as $bodypart_key => $bodypart){

                if (!isset($elements[$element->title][strtoupper($bodypart->title)]['Physical']['excess'])) {
                    $elements[$element->title][strtoupper($bodypart->title)]['Physical']['excess']="0";
                }
                if (!isset($elements[$element->title][strtoupper($bodypart->title)]['Physical']['balance'])) {
                    $elements[$element->title][strtoupper($bodypart->title)]['Physical']['balance']="0";
                }
                if (!isset($elements[$element->title][strtoupper($bodypart->title)]['Physical']['insufficiency'])) {
                    $elements[$element->title][strtoupper($bodypart->title)]['Physical']['insufficiency']="0";
                }
                if (!isset($elements[$element->title][strtoupper($bodypart->title)]['Mental']['excess'])) {
                    $elements[$element->title][strtoupper($bodypart->title)]['Mental']['excess']="0";
                }
                
                if (!isset($elements[$element->title][strtoupper($bodypart->title)]['Mental']['balance'])) {
                    $elements[$element->title][strtoupper($bodypart->title)]['Mental']['balance']="0";
                }
                if (!isset($elements[$element->title][strtoupper($bodypart->title)]['Mental']['insufficiency'])) {
                    $elements[$element->title][strtoupper($bodypart->title)]['Mental']['insufficiency']="0";
                }
                if (!isset($elements[$element->title][strtoupper($bodypart->title)]['Total']['excess'])) {
                    $elements[$element->title][strtoupper($bodypart->title)]['Total']['excess']="0";
                }
                if (!isset($elements[$element->title][strtoupper($bodypart->title)]['Total']['balance'])) {
                    $elements[$element->title][strtoupper($bodypart->title)]['Total']['balance']="0";
                }
                if (!isset($elements[$element->title][strtoupper($bodypart->title)]['Total']['insufficiency'])) {
                    $elements[$element->title][strtoupper($bodypart->title)]['Total']['insufficiency']="0";
                }
                    
            }
        }
        $elements["total"]["message"]["a"] = $result->total_a;
        $elements["total"]["message"]["b"] = $result->total_b;
        $elements["total"]["message"]["c"] = $result->total_c;
    
        $pdf = Pdf::loadView('result_detail_download', ['elements' => $elements,'answers' => $answers, 'result' => $result]);
        $pdf_name = 'Nosphere Healing Results.pdf';
    
        return $pdf->download($pdf_name);
    
    }

     /**
     * Display the specified resource.
     */
    public function mcqAnswers(string $id)
    {
        $mcqAnswers = McqAnswer::where('result_id', $id)->get();
        $simpl_arr = [];
        foreach($mcqAnswers as $key =>$single_mcq){
            $simpl_arr[$single_mcq->question_id] = $single_mcq->answer;
        }

        $questions = Question::where('state', '1')->get();
        $elements = Element::where('state', '1')->get();
        $elements_arr = [];

        foreach($elements as $key => $element){
            $elements_arr[$element->title]['name'] = $element->title;
            $elements_arr[$element->title]['seasone'] = $element->seasone;
            $elements_arr[$element->title]['description'] = $element->description;
            $elements_arr[$element->title]['image'] = $element->image;
         }

        $question_arr = [];
        
        foreach($questions as $key => $val){
            $type =  "Physical";
            if($val->type == 1){
                $type = "Physical";
            }else{
                $type ="Mental";
            }
            // Changed the key to sort the array
            if($val->element->title == "Fire"){
                $key_arr = "0_Fire" ;
            }else if($val->element->title == "Metal"){
                $key_arr = "2_Metal" ;
            }else if($val->element->title == "Earth"){
                $key_arr = "1_Earth" ;
            }else if($val->element->title == "Water"){
                $key_arr = "3_Water" ;
            }else if($val->element->title == "Wood"){
                $key_arr = "4_Wood" ;
            }
            $question_arr[$key_arr][$val->bodypart->title]['image'] = $val->bodypart->image;
            $question_arr[$key_arr][$val->bodypart->title][$type][$key]['question'] = $val->title;
            $question_arr[$key_arr][$val->bodypart->title][$type][$key]['id'] = $val->id;
            $question_arr[$key_arr][$val->bodypart->title][$type][$key]['option_a'] = $val->option_a;
            $question_arr[$key_arr][$val->bodypart->title][$type][$key]['option_b'] = $val->option_b;
            $question_arr[$key_arr][$val->bodypart->title][$type][$key]['option_c'] = $val->option_c;
            $question_arr[$key_arr][$val->bodypart->title][$type][$key]['answer'] = @$simpl_arr[$val->id];
        }
        // ksort done for sorting
        ksort($question_arr) ; 

        // loop to reset the keys back
        foreach($question_arr as $key => $val ){
            $key_data = explode("_",$key);    
            $question_arr_sorted[$key_data[1]] = $val;
        }
        
        return view('answer_detail', ['questions' => $question_arr_sorted,'elements_arr'=>$elements_arr,'result_id'=>$id]);
        
    }

     /**
     * Display the specified resource.
     */
    public function mcqAnswersDownload(string $id)
    {
        $mcqAnswers = McqAnswer::where('result_id', $id)->get();
        $simpl_arr = [];
        foreach($mcqAnswers as $key =>$single_mcq){
            $simpl_arr[$single_mcq->question_id] = $single_mcq->answer;
        }
        $questions = Question::where('state', '1')->get();
        $elements = Element::where('state', '1')->get();
        $elements_arr = [];
         foreach($elements as $key => $element){
            $elements_arr[$element->title]['name'] = $element->title;
            $elements_arr[$element->title]['seasone'] = $element->seasone;
            $elements_arr[$element->title]['description'] = $element->description;
            $elements_arr[$element->title]['image'] = $element->image;

         }
        $question_arr = [];
        foreach($questions as $key => $val){
            $type =  "Physical";
            if($val->type == 1){
                $type = "Physical";
            }else{
                $type ="Mental";
            }
             // Changed the key to sort the array
             if($val->element->title == "Fire"){
                $key_arr = "0_Fire" ;
            }else if($val->element->title == "Metal"){
                $key_arr = "2_Metal" ;
            }else if($val->element->title == "Earth"){
                $key_arr = "1_Earth" ;
            }else if($val->element->title == "Water"){
                $key_arr = "3_Water" ;
            }else if($val->element->title == "Wood"){
                $key_arr = "4_Wood" ;
            }
            $question_arr[$key_arr][$val->bodypart->title]['image'] = $val->bodypart->image;
            $question_arr[$key_arr][$val->bodypart->title][$type][$key]['question'] = $val->title;
            $question_arr[$key_arr][$val->bodypart->title][$type][$key]['id'] = $val->id;
            $question_arr[$key_arr][$val->bodypart->title][$type][$key]['option_a'] = $val->option_a;
            $question_arr[$key_arr][$val->bodypart->title][$type][$key]['option_b'] = $val->option_b;
            $question_arr[$key_arr][$val->bodypart->title][$type][$key]['option_c'] = $val->option_c;
            $question_arr[$key_arr][$val->bodypart->title][$type][$key]['answer'] = @$simpl_arr[$val->id];
        }
        // ksort done for sorting
        ksort($question_arr) ; 

        // loop to reset the keys back
        foreach($question_arr as $key => $val ){
            $key_data = explode("_",$key);    
            $question_arr_sorted[$key_data[1]] = $val;
        }

        $pdf = Pdf::loadView('answer_detail_download', ['questions' => $question_arr_sorted,'elements_arr'=>$elements_arr]);
        return $pdf->download('Nosphere Healing Results Answer.pdf');
    }
    
    public function thankyou(Request $request)
    {
        $data = $_POST['radio'];
        $result['total']["a"] = 0;
        $result['total']["b"] = 0;
        $result['total']["c"] = 0;
        $body_parts_count = 0;
        $elements = Element::where('state', '1')->get();
        
        foreach ($data as $element_key => $elements_data) {

            foreach ($elements_data as $body_parts_key => $body_parts) {
                $body_parts_count++;
                $type_count=0;
                foreach ($body_parts as $type_key => $types) {
                    $total_question = 0;
                    $option_a = 0;
                    $option_b = 0;
                    $option_c = 0;
                    foreach ($types as $questions_key => $answer) {
                        $type_count++;
                        $total_question++;
                        if ($answer == 1) {
                            $option_a++;
                        } elseif ($answer == 2) {
                            $option_b++;
                        } else {
                            $option_c++;
                        }
                        $maqAnswers_insert = new McqAnswer;
                        $maqAnswers_insert->answer = $answer;
                        $maqAnswers_insert->element = $element_key;
                        $maqAnswers_insert->bodypart = $body_parts_key;
                        $maqAnswers_insert->type = $type_key;
                        $maqAnswers_insert->question_id = $questions_key;
                        $maqAnswers_insert->user_id = auth()->user()->id;
                        $maqAnswers_insert->save();
                
                    }
                 
                    $option_percentage = 100 / $total_question;
                    $result[$element_key][$body_parts_key][$type_key]["a"] = $option_a * $option_percentage;
                    $result[$element_key][$body_parts_key][$type_key]["b"] = $option_b * $option_percentage;
                    $result[$element_key][$body_parts_key][$type_key]["c"] = $option_c * $option_percentage;
                }
                if($type_count > 1 ){
                    $result[$element_key][$body_parts_key]['total']["a"] = (@$result[$element_key][$body_parts_key]['mental']['a'] + @$result[$element_key][$body_parts_key]['physical']['a']) / 2;
                    $result[$element_key][$body_parts_key]['total']["b"] = (@$result[$element_key][$body_parts_key]['mental']['b'] + @$result[$element_key][$body_parts_key]['physical']['b']) / 2;
                    $result[$element_key][$body_parts_key]['total']["c"] = (@$result[$element_key][$body_parts_key]['mental']['c'] + @$result[$element_key][$body_parts_key]['physical']['c']) / 2;
                }else{

                    $result[$element_key][$body_parts_key]['total']["a"] = (@$result[$element_key][$body_parts_key]['mental']['a'] + @$result[$element_key][$body_parts_key]['physical']['a']) / 1;
                    $result[$element_key][$body_parts_key]['total']["b"] = (@$result[$element_key][$body_parts_key]['mental']['b'] + @$result[$element_key][$body_parts_key]['physical']['b']) / 1;
                    $result[$element_key][$body_parts_key]['total']["c"] = (@$result[$element_key][$body_parts_key]['mental']['c'] + @$result[$element_key][$body_parts_key]['physical']['c']) / 1;
                }
                $result['total']["a"] =  $result['total']["a"] + $result[$element_key][$body_parts_key]['total']["a"];
                $result['total']["b"] = $result['total']["b"] + $result[$element_key][$body_parts_key]['total']["b"];
                $result['total']["c"] = $result['total']["c"] + $result[$element_key][$body_parts_key]['total']["c"];
            }
        }
       
        $result['total']["a"] = round($result['total']["a"] / $body_parts_count, 2);
        $result['total']["b"] = round($result['total']["b"] / $body_parts_count, 2);
        $result['total']["c"] = round($result['total']["c"] / $body_parts_count, 2);

        // Final structure for the webhook call
        $webhook_data =[];

        // Prepare the array for the webhook call creating structure for call
      foreach($elements as $key => $element){
         $bodyparts = Bodypart::where('element_id', $element->id)->get();
         foreach($bodyparts as $key => $bodypart){
          
         $webhook_data[strtolower($element->title)][strtolower($bodypart->title)]['physical']['a']=(@$result[strtolower($element->title)][strtolower($bodypart->title)]['physical']['a']) ? $result[strtolower($element->title)][strtolower($bodypart->title)]['physical']['a']: 0;
         $webhook_data[strtolower($element->title)][strtolower($bodypart->title)]['physical']['b']=(@$result[strtolower($element->title)][strtolower($bodypart->title)]['physical']['b']) ? $result[strtolower($element->title)][strtolower($bodypart->title)]['physical']['b']: 0;
         $webhook_data[strtolower($element->title)][strtolower($bodypart->title)]['physical']['c']=(@$result[strtolower($element->title)][strtolower($bodypart->title)]['physical']['c']) ? $result[strtolower($element->title)][strtolower($bodypart->title)]['physical']['c']: 0;
         $webhook_data[strtolower($element->title)][strtolower($bodypart->title)]['mental']['a']=(@$result[strtolower($element->title)][strtolower($bodypart->title)]['mental']['a']) ? $result[strtolower($element->title)][strtolower($bodypart->title)]['mental']['a']: 0;
         $webhook_data[strtolower($element->title)][strtolower($bodypart->title)]['mental']['b']=(@$result[strtolower($element->title)][strtolower($bodypart->title)]['mental']['b']) ? $result[strtolower($element->title)][strtolower($bodypart->title)]['mental']['b']: 0;
         $webhook_data[strtolower($element->title)][strtolower($bodypart->title)]['mental']['c']=(@$result[strtolower($element->title)][strtolower($bodypart->title)]['mental']['c']) ? $result[strtolower($element->title)][strtolower($bodypart->title)]['mental']['c']: 0;

        //  $webhook_data[strtolower($element->title)][strtolower($bodypart->title)]['total']['a']=(((@$result[strtolower($element->title)][strtolower($bodypart->title)]['physical']['a']) ? $result[strtolower($element->title)][strtolower($bodypart->title)]['physical']['a']: 0) + ((@$result[strtolower($element->title)][strtolower($bodypart->title)]['mental']['a']) ? $result[strtolower($element->title)][strtolower($bodypart->title)]['mental']['a']: 0)/2);
        //  $webhook_data[strtolower($element->title)][strtolower($bodypart->title)]['total']['b']=(((@$result[strtolower($element->title)][strtolower($bodypart->title)]['physical']['a']) ? $result[strtolower($element->title)][strtolower($bodypart->title)]['physical']['a']: 0) + ((@$result[strtolower($element->title)][strtolower($bodypart->title)]['mental']['a']) ? $result[strtolower($element->title)][strtolower($bodypart->title)]['mental']['a']: 0)/2);
        //  $webhook_data[strtolower($element->title)][strtolower($bodypart->title)]['total']['c']=(((@$result[strtolower($element->title)][strtolower($bodypart->title)]['physical']['a']) ? $result[strtolower($element->title)][strtolower($bodypart->title)]['physical']['a']: 0) + ((@$result[strtolower($element->title)][strtolower($bodypart->title)]['mental']['a']) ? $result[strtolower($element->title)][strtolower($bodypart->title)]['mental']['a']: 0)/2);

         }
      }
      $webhook_data['total']["a"] = round($result['total']["a"] / $body_parts_count, 2);
      $webhook_data['total']["b"] = round($result['total']["b"] / $body_parts_count, 2);
      $webhook_data['total']["c"] = round($result['total']["c"] / $body_parts_count, 2);


        $result_insert = new Result;
        $result_insert->excess = $result['total']["a"];
        $result_insert->balance = $result['total']["b"];
        $result_insert->insufficiency = $result['total']["c"];
        $result_insert->user_id = auth()->user()->id;
        $result_insert->save();
      
        // Add the result id to the list of results
        McqAnswer::where('result_id', Null)
      ->where('user_id', auth()->user()->id)
      ->update(['result_id' => $result_insert->id]);

        foreach ($result as $element_key => $elements_data) {
            if ($element_key != "total") {
                foreach ($elements_data as $body_parts_key => $body_parts) {
                    foreach ($body_parts as $type_key => $types) {
                        $answer_insert = new Answer();
                        $answer_insert->element = ucfirst($element_key);
                        $answer_insert->bodypart = ucfirst($body_parts_key);
                        $answer_insert->type = ucfirst($type_key);
                        $answer_insert->result_id = $result_insert->id;
                        $answer_insert->excess = round($types["a"]);
                        $answer_insert->balance = round($types["b"]);
                        $answer_insert->insufficiency = round($types["c"]);
                        $answer_insert->user_id = auth()->user()->id;
                        $answer_insert->save();
                    }
                }
            }
        }
        SaveResult::dispatch($webhook_data,$result_insert->id);

        return redirect('/result-thankyou/' . $result_insert->id);
    }
    public function elementsGuide(Request $request)
    {
        return view('element_guide');
    }
    public function elementsSeasons(Request $request)
    {
        return view('elements_seasons');
    }
    public function interpret(): View
    {   
        return view('interpret');
    } 
     /**
     * Display the specified resource.
     */
    public function downloadpreview()
    {
        $id = 191;
        $result = Result::where('id', $id)->first();
        $answers = Answer::where('result_id', $id)->get();
        // echo "<pre>";
        // print_r($answers);
        // echo "</pre>";
        // die("as");
        $elements = [];
        foreach($answers as $answer){
            $elements[$answer->element][strtoupper($answer->bodypart)][$answer->type]['excess'] = $answer->excess;
            $elements[$answer->element][strtoupper($answer->bodypart)][$answer->type]['balance'] = $answer->balance;
            $elements[$answer->element][strtoupper($answer->bodypart)][$answer->type]['insufficiency'] = $answer->insufficiency;

            
        }
        $elements_arr = Element::where('state', '1')->get();
        foreach($elements_arr as $element_key => $element){
            
            if($element->title=="Fire"){
            $elements[$element->title]["message"]["a"] = $result->fire_a;
            $elements[$element->title]["message"]["b"] = $result->fire_b;
            $elements[$element->title]["message"]["c"] = $result->fire_c;
            }
            if($element->title=="Earth"){
                $elements[$element->title]["message"]["a"] = $result->earth_a;
                $elements[$element->title]["message"]["b"] = $result->earth_b;
                $elements[$element->title]["message"]["c"] = $result->earth_c;
            }
            if($element->title=="Metal"){
                $elements[$element->title]["message"]["a"] = $result->metal_a;
                $elements[$element->title]["message"]["b"] = $result->metal_b;
                $elements[$element->title]["message"]["c"] = $result->metal_c;
            }
            if($element->title=="Water"){
                $elements[$element->title]["message"]["a"] = $result->water_a;
                $elements[$element->title]["message"]["b"] = $result->water_b;
                $elements[$element->title]["message"]["c"] = $result->water_c;
            }
            if($element->title=="Wood"){
                $elements[$element->title]["message"]["a"] = $result->wood_a;
                $elements[$element->title]["message"]["b"] = $result->wood_b;
                $elements[$element->title]["message"]["c"] = $result->wood_c;
            }
                                    
            $bodypart_arr =Bodypart::where('element_id', $element->id)->get();
                foreach($bodypart_arr as $bodypart_key => $bodypart){

                    // $elements[$element->title." m"][strtoupper($bodypart->title)]['Physical']['excess']="Asd";
                    if (!isset($elements[$element->title][strtoupper($bodypart->title)]['Physical']['excess'])) {
                        $elements[$element->title][strtoupper($bodypart->title)]['Physical']['excess']="0";
                    }
                    if (!isset($elements[$element->title][strtoupper($bodypart->title)]['Physical']['balance'])) {
                        $elements[$element->title][strtoupper($bodypart->title)]['Physical']['balance']="0";
                    }
                    if (!isset($elements[$element->title][strtoupper($bodypart->title)]['Physical']['insufficiency'])) {
                        $elements[$element->title][strtoupper($bodypart->title)]['Physical']['insufficiency']="0";
                    }
                    if (!isset($elements[$element->title][strtoupper($bodypart->title)]['Mental']['excess'])) {
                        $elements[$element->title][strtoupper($bodypart->title)]['Mental']['excess']="0";
                    }
                    
                    if (!isset($elements[$element->title][strtoupper($bodypart->title)]['Mental']['balance'])) {
                        $elements[$element->title][strtoupper($bodypart->title)]['Mental']['balance']="0";
                    }
                    if (!isset($elements[$element->title][strtoupper($bodypart->title)]['Mental']['insufficiency'])) {
                        $elements[$element->title][strtoupper($bodypart->title)]['Mental']['insufficiency']="0";
                    }
                    if (!isset($elements[$element->title][strtoupper($bodypart->title)]['Total']['excess'])) {
                        $elements[$element->title][strtoupper($bodypart->title)]['Total']['excess']="0";
                    }
                    if (!isset($elements[$element->title][strtoupper($bodypart->title)]['Total']['balance'])) {
                        $elements[$element->title][strtoupper($bodypart->title)]['Total']['balance']="0";
                    }
                    if (!isset($elements[$element->title][strtoupper($bodypart->title)]['Total']['insufficiency'])) {
                        $elements[$element->title][strtoupper($bodypart->title)]['Total']['insufficiency']="0";
                    }
                    
                    
                }
        }
        
            $elements["total"]["message"]["a"] = $result->total_a;
            $elements["total"]["message"]["b"] = $result->total_b;
            $elements["total"]["message"]["c"] = $result->total_c;

        return view('result_detail_download', ['elements' => $elements,'answers' => $answers, 'result' => $result]);
        // $pdf_name = 'Nosphere Healing Results.pdf';
        // return $pdf->download($pdf_name);
    } 
}
