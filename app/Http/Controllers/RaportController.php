<?php

namespace App\Http\Controllers;

use App\Category;
use App\Photo;
use App\Raport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class RaportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $circle_radius = 3959;
        $max_distance = 20;
        if((Session::get('userLat')!=null) | (Session::get('userLng')!=null)) {
            $lat = str_replace(',', '.', Session::get('userLat'));
            $lng = str_replace(',', '.', Session::get('userLng'));
            $query = Raport::distance($lat, $lng);
            $raportsAround  = $query->orderBy('distance', 'DESC')->get();

        } else {
            $raportsAround = Raport::all();
        }
        return view('index', ['raports'=>$raportsAround, 'categories'=>Category::all()]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('report', ['categories'=>Category::all()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->except('user_id');
        $data['user_id_report'] = Auth::user()->id;
        $data['lng'] = Session::get('userLng');
        $data['lat'] = Session::get('userLat');
        $photo = new Photo();
        if($file = $request->file('photo')){
           $data['photo_id'] = $photo->photoUpload($request->file('photo'), 'raport_', '0', 1);
        }
        Raport::create($data);
        return redirect('/');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Raport  $raport
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return view('raport.show', ['raport'=>Raport::findOrFail($id)]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Raport  $raport
     * @return \Illuminate\Http\Response
     */
    public function edit(Raport $raport)
    {
        //
    }


    /**
     * This is controller for fixed page where user can approve the fixed problem
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function fixed($id) {
        $raport = Raport::findOrFail($id);
        $raport['user_id'] = Auth::user()->id;
        $raport->save();
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Raport  $raport
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Raport $raport)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Raport  $raport
     * @return \Illuminate\Http\Response
     */
    public function destroy(Raport $raport)
    {

    }
}
