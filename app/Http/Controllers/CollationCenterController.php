<?php

namespace App\Http\Controllers;

use App\Models\CollationCenter;
use Illuminate\Http\Request;

class CollationCenterController extends Controller
{
    public function index()
    {
        $centers = CollationCenter::all();
        return view('admin.centers.index', compact('centers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'center_code' => 'required|string|unique:collation_centers',
            'address' => 'required|string',
        ]);

        CollationCenter::create($validated);

        return back()->with('success', 'Collation Center created successfully.');
    }

    public function destroy(CollationCenter $center)
    {
        // Safety: Don't delete if staff or loans are linked to it
        if ($center->users()->exists() || \App\Models\Loan::where('collation_center_id', $center->id)->exists()) {
            return back()->with('error', 'Cannot delete center. Staff or Loans are currently assigned to it.');
        }

        $center->delete();
        return back()->with('success', 'Center removed.');
    }
}