<?php

namespace App\Http\Controllers\API\Enquiry;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator, DateTime, Config, Helpers, Hash, DB, Session, Auth, Redirect;
use App\Enquiry;

class EnquiryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required|numeric|phone',
            'message' => 'required|max:255',
        ]);
        if($validator->fails()){
            return Response()->json(['status'=>false,'message'=>$validator->getMessageBag()->first(),'response' => []],200);
        }
        if(!filter_var($request->input('email'), FILTER_VALIDATE_EMAIL))
        {
            return Response()->json(['status'=>false,'message'=>'Email Must be a Valid Email'],200);
        }

        $enquiry = new Enquiry;
        $enquiry->post_id = ($request->input('post_id')?$request->input('post_id'):0);
        $enquiry->enquirer_name = $request->input('name');
        $enquiry->enquirer_email = $request->input('email');
        $enquiry->enquirer_phone = $request->input('phone');
        $enquiry->enquirer_message = $request->input('message');
        $enquiry->save();

        $emailBody = view('Email.EnquiryRequest', compact('enquiry'));

        SendEmail(adminEmail(), 'New Enquiry Request On Infiway', $emailBody, [], '', '', '', '');

        return Response()->json(['status' => true ,'message' => 'Thanks for Reach out! we will Contact You Shortly'],200);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
