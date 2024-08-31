<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TwoFactorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'two_factor_code' => 'integer|required',
        ]);

        $user = Auth::user();

        if($request->input('two_factor_code') == $user->two_factor_code)
        {
            $user->resetTwoFactorCode();

            return redirect()->route('dashboard');
        }

        return redirect()->back()->withErrors(['two_factor_code' => 'The two factor code you have entered does not match']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
