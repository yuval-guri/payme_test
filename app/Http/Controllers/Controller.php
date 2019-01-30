<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;


class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    const APIURL = "https://preprod.paymeservice.com/api/generate-sale";

    private static function getCurrencies(){
        return [0 => "USD", 1 => "EUR" , 2 => "ILS"];
    }

    private function validatePaymentFields(){
        //validates inputs and returns a boolean
    }

    private function sendSaleDetails($formFields = []){
        $apiConstantRequestFields = array(
            "seller_payme_id"=>"MPL14985-68544Z1G-SPV5WK2K-0WJWHC7N", // Use this static ID
            "installments"=>"1", // Constant value
            "language"=> "en" // Constant value*;
        ); // From input
        $apiRequestFields = array_merge($formFields,$apiConstantRequestFields);
        $apiRequesetString = json_encode($apiRequestFields);
        $ch = \curl_init($this::APIURL);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $apiRequesetString);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Content-Type: application/json',
                    'Content-Length: ' . strlen($apiRequesetString))
            );
//        curl_setopt($ch,CURLOPT_POSTFIELDS,array('seller_payme_id' => "MPL14985-68544Z1G-SPV5WK2K-0WJWHC7N"));
        $result = curl_exec($ch);
        return json_decode($result,TRUE);
    }

    private function saveSaleDetails($details){
        $currencies = $this::getCurrencies();
        $details['currency'] = array_search($details['currency'],$currencies);
        DB::insert('INSERT INTO sales(id,description,amount,currency,payment_link) VALUES(?,?,?,?,?)'
            ,[$details['payme_sale_code'] , $details['product_name'],$details['price'],$details['currency'],$details['sale_url']]);
    }

    public function index(){
        return view("form");
    }

    public function getSalesForDisplay(){
        $sales = DB::table('sales')->get();
        $currencies = $this::getCurrencies();
        foreach ($sales as &$sale){
            $sale->currency = $currencies[$sale->currency];
        }
        return $sales;
    }

    public function table(){
        $sales = $this::getSalesForDisplay();
        return view("table",['sales' => $sales]);
    }

    public function process_form(){
        $currencies = $this::getCurrencies();
        $formFields = $_REQUEST;
        $formFields['currency'] = $currencies[$formFields['currency']];
        $formFields['sale_price'] = floatval($formFields['sale_price']) * 100;
        $apiResult = $this->sendSaleDetails($formFields);
        if (!$apiResult || $apiResult['status_code'] != 0){
            $error = ($apiResult) ? $apiResult['status_error_details'] : 'Request was unsuccessful';
            return view('payment_form',['error' => $error]);
        }
        else{
            $this->saveSaleDetails(array_merge($formFields,$apiResult));
            return view('payment_form',['sale_url' => $apiResult['sale_url']]);
        }
    }
}
