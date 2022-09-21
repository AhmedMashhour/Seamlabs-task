<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PartOneController extends Controller
{

    private function getCount($start,$end){
        /**
         * this loop do the following:
         *  1- strpos check the number if contain 5 or not
         *  2- if not increase the count and the $i by 1
         *  3- if the number contain 5 then we need to jump to the next number that doesn't
         *      contain 5 example if we are in 5123 we need to jump to 6000 that will be the next
         *      number doesn't contain 5 , 1511 to 1600 , 150 to 160 and so on ...
        */
        $count=0;
        for($i = $start ; $i <= $end; ){
            $index=strpos((string)$i  ,'5');
            if($index === false){
                $count++;
                $i++;
            }else{
                /**
                 * the first part of formula calculate the power
                 * determine the digit where 5 is in then calculate the numbers we need to skip
                 * ex: if we have 3524 , the number 5 is in hundreds or digit 3
                 *  len of string is 4 the index is 1 and minimize it by 1 give us 2
                 *  the number should be skipped is 76
                 *  will be like : 100 - ( 3524 % 50) : 100 - 24 = 76
                 *
                 *   note : this approach will help on big numbers like hundreds of thousands
                 *      from 1 to 100000 get it in 209 ms
                 *      the normal iteration i get 615 ms
                 *
                 */
                $power=strlen((string)$i) - ($index +1);
                $increment=pow(10, $power) -( $i % (5 * pow(10, $power) ) );
                if($increment == 0 )
                    $i++;
                else
                    $i += $increment;
            }
        }
        return $count;
    }
    public function getCountOfNumbers(Request $request){
        /**
         * Validate parameters
         */
        $validate = Validator::make($request->all(),[
            'start_number' => 'required|numeric',
            'end_number' => 'required|numeric',
        ]);
        if($validate->fails()){
            return response()->json(
                [
                    'message' => "Enter valid numeric parameters"
                ],
                400
            );
        }


        $start_number = (int)$request->start_number;
        $end_number = (int)$request->end_number;

        if($start_number > $end_number){
            return  response()->json(
                [
                    'message' => "Start number must be smaller than end number"
                ],
                400
            );
        }
        // call count function to get count
        $count = $this->getCount($start_number,$end_number);

        return response()->json(
            [
                'count' => $count,
            ],
            200
        );
    }

    public function getIndexOfString(Request $request){

        /**
         * Validate parameter
         */
        $validate = Validator::make($request->all(),[
            'word' => 'required|regex:/^[A-Z]+$/u'
        ]);
        if($validate->fails()){
            return response()->json(
                [
                    'message' => "Word should be a string has only upper case"
                ],
                400
            );
        }

        /**
         * Get word
         * Reverse word
         * Loop to calc every character with index
         */
        $word = $request->word;
        $word = strrev($word);

        $index = 0;
        for ($i=0 ; $i<strlen($word) ; $i++)
        {
            $index += ( ord($word[$i]) - 64 ) * pow(26,$i);
        }

        return response()->json(
            [
                'Index'=>$index
            ],
            200
        );
    }

    public function calcStep(int $number){
        /**
         * this recursive function calculate steps by
         * 1- find the best factor for the number
         * 2- if no factor found use step2 by minimize number by 1
        */
        $sqrt=(int)sqrt($number);
        $step2=true; // this variable used to use step 2 if no factor
        if ($number == 1)
        {
            return 1;
        }else{
            for($i= $sqrt ; $i > 1 ; $i-- ){
                if($number % $i == 0){
                    $step2=false;// set to false to not to use step 2
                    // recursively check the next number of the factors using max number of them
                    return $this->calcStep(max($i,($number/$i))) + 1;
                }
            }
            if ($step2){
                return $this->calcStep($number - 1) +1;
            }
        }
    }

    public function  steps(Request $request){
        /**
         * Validate parameter
         */
        $validate = Validator::make($request->all(),[
            'number_of_elements' => 'required|numeric',
            'elements'=>'required',
            'elements.*'=>'required|numeric',
        ]);
        if($validate->fails()){
            return response()->json(
                [
                    'message' => "invalid data input"
                ],
                400
            );
        }
        $number_of_elements=$request->number_of_elements;
        $elements= $request->elements;
        $output=[];

        for($i = 0 ; $i< $number_of_elements; $i++){
            $number=(int) $elements[$i] ;
            // save every number and it's calculated steps
            $output[]=array(
                'number'=>$number,
                'steps'=>$this->calcStep($number)// call the calculate steps function that return number of steps
            );
        }

        return response()->json(
            [
                'steps' => $output,
            ],200
        );
    }
}
