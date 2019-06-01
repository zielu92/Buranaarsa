<?php

namespace App\Http\Controllers;

use App\Category;
use App\Raport;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class UserController extends Controller
{
    public function index()
    {
        return view('user-dashboard', ['raports'=>Raport::where('user_id', '=', Auth::user()->id)->get()]);
    }

    public function volunteer() {
        if((Session::get('userLat')!=null) | (Session::get('userLng')!=null)) {
            $lat = str_replace(',', '.', Session::get('userLat'));
            $lng = str_replace(',', '.', Session::get('userLng'));
            $query = Raport::distance($lat, $lng);
            $raportsAround  = $query->orderBy('distance', 'DESC')->get();

        } else {
            $raportsAround = Raport::all();
        }
        return view('volunteer-dashboard', ['raports'=>$raportsAround, 'categories'=>Category::all()]);
    }

    public function getGeo(Request $request) {
        Session::put('userLat', $request->latitude);
        Session::put('userLng', $request->longitude);
        return "updated location". Session::get('userLat').','.Session::get('userLng');
    }

    public function rank() {

        $users = User::all();
        foreach($users as $user) {
            $rank[] = [
            'user' => User::find($user->id),
            'value' => Raport::where('user_id', '=', $user->id)->count()
            ];
        }
        foreach ($rank as $key => $row)
        {
            $count[$key] = $row['value'];
        }
        array_multisort($count, SORT_DESC, $rank);


        return view('volunteer-ranking', ['ranks'=>$rank]);
    }
}
