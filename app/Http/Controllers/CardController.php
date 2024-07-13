<?php

namespace App\Http\Controllers;

use App\Category;
use App\Card;
use App\Customer;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class CardController extends Controller
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
        $category = Category::orderBy('name','ASC')
            ->get()
            ->pluck('name','id');
        
        $customer = Customer::orderBy('name','ASC')
            ->get()
            ->pluck('name','id');

        $cards = Card::all();
        return view('cards.index', compact(['category','customer']));
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

    public function saveworkingdays(Request $request){
        $workingDays = $request->working_days;
        
        $data = [
            'number' => 1,
            'working_days' => $workingDays,
            'usage_hours' => 1,
            'bus_lines' => 1,
            'category_id' => 1,
            'customer_id' => 2,
            'is_default' => 1,
            
        ];
        Card::create($data);
        return response()->json([
            'success' => true,
            'message' => 'Working Days Created',
            'working_days' => $workingDays
        ]);
    }
    public function getworkingdays(){
        $card = Card::where('is_default', 1)->orderBy('created_at', 'desc')->first();

        if ($card) {
            return response()->json([
                'success' => true,
                'working_days' => $card->working_days
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'No working days found'
            ]);
        }
    }

    public function store(Request $request)
    {
        $category = Category::orderBy('name','ASC')
            ->get()
            ->pluck('name','id');

        $this->validate($request , [
            'number'          => 'required|string',
            // 'working_days'   => 'required',
            // 'usage_hours'   => 'required',
            // 'bus_lines'   => 'required',
        ]);
        
        $input = $request->all();
        $usage_hours = implode(",", $input['usage_hours']);
        $data = [
            'number' => $input['number'],
            'working_days' => $input['working_days'],
            'usage_hours' => $usage_hours,
            'bus_lines' => $input['bus_lines'],
            'category_id' => $input['category_id'],
            'customer_id' => $input['customer_id'],
            'is_default' => 0
        ];

        // $input['image'] = null;

        // if ($request->hasFile('image')){
        //     $input['image'] = '/upload/cards/'.str_slug($input['name'], '-').'.'.$request->image->getClientOriginalExtension();
        //     $request->image->move(public_path('/upload/cards/'), $input['image']);
        // }

        Card::create($data);

        return response()->json([
            'success' => true,
            'message' => 'cards Created'
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
        $category = Category::orderBy('name','ASC')
            ->get()
            ->pluck('name','id');
        $card = Card::find($id);
        return $card;
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
        $category = Category::orderBy('name','ASC')
            ->get()
            ->pluck('name','id');

        $this->validate($request , [
            'number'          => 'required|string',
            // 'harga'         => 'required',
            // 'qty'           => 'required',
//            'image'         => 'required',
            'category_id'   => 'required',
        ]);

        $input = $request->all();
        $usage_hours = implode(",", $input['usage_hours']);

        $produk = Card::findOrFail($id);


        // $input['image'] = $produk->image;

        // if ($request->hasFile('image')){
        //     if (!$produk->image == NULL){
        //         unlink(public_path($produk->image));
        //     }
        //     $input['image'] = '/upload/cards/'.str_slug($input['name'], '-').'.'.$request->image->getClientOriginalExtension();
        //     $request->image->move(public_path('/upload/cards/'), $input['image']);
        // }

        $data = [
            'number' => $input['number'],
            'working_days' => json_encode($input['working_days']),
            'usage_hours' => json_encode($usage_hours),
            'bus_lines' => json_encode($input['bus_lines']),
            'category_id' => $input['category_id'],
        ];

        $produk->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Cards Update'
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
        $card = Card::findOrFail($id);

        if (!$card->image == NULL){
            unlink(public_path($card->image));
        }

        Card::destroy($id);

        return response()->json([
            'success' => true,
            'message' => 'cards Deleted'
        ]);
    }

    public function apicards(){
        $card = Card::where('is_default',0)->get();
        

        return Datatables::of($card)
            ->addColumn('category_name', function ($card){
                return $card->category->name;
            })
            ->addColumn('customer_name', function ($card){
                return $card->customer->name;
            })
            ->addColumn('show_photo', function($card){
                if ($card->image == NULL){
                    return 'No Image';
                }
                return '<img class="rounded-square" width="50" height="50" src="'. url($card->image) .'" alt="">';
            })
            ->addColumn('action', function($card){
                return'<a onclick="editForm('. $card->id .')" class="btn btn-primary btn-xs"><i class="glyphicon glyphicon-edit"></i> Edit</a> ' .
                    '<a onclick="deleteData('. $card->id .')" class="btn btn-danger btn-xs"><i class="glyphicon glyphicon-trash"></i> Delete</a>';
            })
            ->rawColumns(['category_name','show_photo','action'])->make(true);

    }
}
