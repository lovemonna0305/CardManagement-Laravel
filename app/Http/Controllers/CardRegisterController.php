<?php

namespace App\Http\Controllers;

use App\Category;
use App\Customer;
use App\Exports\ExportProdukKeluar;
use App\Card;
use App\Card_Register;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use PDF;


class CardRegisterController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:admin,staff');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cards = Card::orderBy('number','ASC')
            ->get()
            ->pluck('number','id');

        $customers = Customer::orderBy('name','ASC')
            ->get()
            ->pluck('name','id');

        $invoice_data = Card_Register::all();
        return view('Card_Register.index', compact('cards','customers', 'invoice_data'));
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
        $this->validate($request, [
           'card_id'     => 'required',
           'customer_id'    => 'required',
           'qty'            => 'required',
           'tanggal'           => 'required'
        ]);

        Card_Register::create($request->all());

        $card = card::findOrFail($request->card_id);
        $card->qty -= $request->qty;
        $card->save();

        return response()->json([
            'success'    => true,
            'message'    => 'cards Out Created'
        ]);

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
        $Card_Register = Card_Register::find($id);
        return $Card_Register;
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
        $this->validate($request, [
            'card_id'     => 'required',
            'customer_id'    => 'required',
            'qty'            => 'required',
            'tanggal'           => 'required'
        ]);

        $Card_Register = Card_Register::findOrFail($id);
        $Card_Register->update($request->all());

        $card = card::findOrFail($request->card_id);
        $card->qty -= $request->qty;
        $card->update();

        return response()->json([
            'success'    => true,
            'message'    => 'card Out Updated'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Card_Register::destroy($id);

        return response()->json([
            'success'    => true,
            'message'    => 'cards Delete Deleted'
        ]);
    }



    public function apicardsOut(){
        $card = Card_Register::all();

        return Datatables::of($card)
            ->addColumn('cards_name', function ($card){
                return $card->card->name;
            })
            ->addColumn('customer_name', function ($card){
                return $card->customer->name;
            })
            ->addColumn('action', function($card){
                return'<a onclick="editForm('. $card->id .')" class="btn btn-primary btn-xs"><i class="glyphicon glyphicon-edit"></i> Edit</a> ' .
                    '<a onclick="deleteData('. $card->id .')" class="btn btn-danger btn-xs"><i class="glyphicon glyphicon-trash"></i> Delete</a>';
            })
            ->rawColumns(['cards_name','customer_name','action'])->make(true);

    }

    public function exportcardKeluarAll()
    {
        $Card_Register = Card_Register::all();
        $pdf = PDF::loadView('Card_Register.cardKeluarAllPDF',compact('Card_Register'));
        return $pdf->download('card_out.pdf');
    }

    public function exportcardKeluar($id)
    {
        $Card_Register = Card_Register::findOrFail($id);
        $pdf = PDF::loadView('Card_Register.cardKeluarPDF', compact('Card_Register'));
        return $pdf->download($Card_Register->id.'_card_out.pdf');
    }

    public function exportExcel()
    {
        return (new ExportProdukKeluar)->download('Card_Register.xlsx');
    }
}
