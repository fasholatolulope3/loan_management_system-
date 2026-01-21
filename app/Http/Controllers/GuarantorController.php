<?php

namespace App\Http\Controllers;

use App\Models\{Guarantor, Client};
use Illuminate\Http\Request;

class GuarantorController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'name' => 'required|string',
            'phone' => 'required|string',
            'relationship' => 'required|string',
            'address' => 'required|string',
        ]);

        Guarantor::create($validated);
        return back()->with('success', 'Guarantor added.');
    }

    public function destroy(Guarantor $guarantor)
    {
        $guarantor->delete();
        return back()->with('success', 'Guarantor removed.');
    }
}
