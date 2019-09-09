<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\invoiceSendMail;
use DB;
use Mail;
use PDF;

class invoiceController extends Controller
{
    public function invoice_page(){
        $last_id = DB::table('c1ft_stock_manager.sm_invoice')->latest('id')->value('id');

        return view('invoice.invoice',compact('last_id'));
    }

    public function send_invoice(Request $request){

        DB::table('c1ft_stock_manager.sm_invoice')->insert(
            array('invoice_number' => intval($request->invoice_id) + 1)
        );

        $email = $request->email;
        //$email = 'jianqilu1126@gmail.com';

         Mail::to($email)->send(new invoiceSendMail
                 (
                     $request->lists,
                     $request->date,
                     $request->shipping_address,
                     $request->billing_address,
                     $request->email,
                     $request->name,
                     $request->order_ref,
                     $request->total_tax,
                     $request->total_price,
                     $request->invoice_id
                 ));

                 return redirect()->route('invoice_page');
        // return new invoiceSendMail
        //         (
        //             $request->lists,
        //             $request->date,
        //             $request->shipping_address,
        //             $request->billing_address,
        //             $request->email,
        //             $request->name,
        //             $request->order_ref,
        //             $request->total_tax,
        //             $request->total_price,
        //             $request->invoice_id
        //         );
    }
}
