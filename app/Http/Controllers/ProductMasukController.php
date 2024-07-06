<?php

namespace App\Http\Controllers;


use App\Exports\ExportProdukMasuk;
use App\card;
use App\Product_Masuk;
use App\Analytics;
use PDF;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;


class cardMasukController extends Controller
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
        $cards = card::orderBy('name','ASC')
            ->get()
            ->pluck('name','id');

        $Analytics = Analytics::orderBy('name','ASC')
            ->get()
            ->pluck('name','id');

        $invoice_data = Product_Masuk::all();
        return view('Product_Masuk.index', compact('cards','Analytics','invoice_data'));
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
            'Analytics_id'    => 'required',
            'qty'            => 'required',
            'tanggal'        => 'required'
        ]);

        Product_Masuk::create($request->all());

        $card = card::findOrFail($request->card_id);
        $card->qty += $request->qty;
        $card->save();

        return response()->json([
            'success'    => true,
            'message'    => 'cards In Created'
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
        $Product_Masuk = Product_Masuk::find($id);
        return $Product_Masuk;
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
            'Analytics_id'    => 'required',
            'qty'            => 'required',
            'tanggal'        => 'required'
        ]);

        $Product_Masuk = Product_Masuk::findOrFail($id);
        $Product_Masuk->update($request->all());

        $card = card::findOrFail($request->card_id);
        $card->qty += $request->qty;
        $card->update();

        return response()->json([
            'success'    => true,
            'message'    => 'card In Updated'
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
        Product_Masuk::destroy($id);

        return response()->json([
            'success'    => true,
            'message'    => 'cards In Deleted'
        ]);
    }



    public function apicardsIn(){
        $card = Product_Masuk::all();

        return Datatables::of($card)
            ->addColumn('cards_name', function ($card){
                return $card->card->name;
            })
            ->addColumn('Analytics_name', function ($card){
                return $card->Analytics->name;
            })
            ->addColumn('action', function($card){
                return '<a onclick="editForm('. $card->id .')" class="btn btn-primary btn-xs"><i class="glyphicon glyphicon-edit"></i> Edit</a> ' .
                    '<a onclick="deleteData('. $card->id .')" class="btn btn-danger btn-xs"><i class="glyphicon glyphicon-trash"></i> Delete</a> ';


            })
            ->rawColumns(['cards_name','Analytics_name','action'])->make(true);

    }

    public function exportcardMasukAll()
    {
        $Product_Masuk = Product_Masuk::all();
        $pdf = PDF::loadView('Product_Masuk.cardMasukAllPDF',compact('Product_Masuk'));
        return $pdf->download('card_enter.pdf');
    }

    public function exportcardMasuk($id)
    {
        $Product_Masuk = Product_Masuk::findOrFail($id);
        $pdf = PDF::loadView('Product_Masuk.cardMasukPDF', compact('Product_Masuk'));
        return $pdf->download($Product_Masuk->id.'_card_enter.pdf');
    }

    public function exportExcel()
    {
        return (new ExportProdukMasuk)->download('Product_Masuk.xlsx');
    }
}
