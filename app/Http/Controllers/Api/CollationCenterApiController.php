<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CollationCenter;
use Illuminate\Support\Facades\Auth;

class CollationCenterApiController extends Controller
{
    /**
     * GET /api/admin/centers
     */
    public function index()
    {
        if (Auth::user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized. Admin only.'], 403);
        }

        return response()->json(CollationCenter::all());
    }

    /**
     * POST /api/admin/centers
     */
    public function store(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized. Admin only.'], 403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'center_code' => 'required|string|unique:collation_centers',
            'address' => 'required|string',
        ]);

        $center = CollationCenter::create($validated);

        return response()->json(['message' => 'Collation Center created.', 'data' => $center], 201);
    }

    /**
     * DELETE /api/admin/centers/{center}
     */
    public function destroy(CollationCenter $center)
    {
        if (Auth::user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized. Admin only.'], 403);
        }

        if ($center->users()->exists() || \App\Models\Loan::where('collation_center_id', $center->id)->exists()) {
            return response()->json(['message' => 'Cannot delete center. Staff or Loans are currently assigned to it.'], 422);
        }

        $center->delete();

        return response()->json(['message' => 'Center removed.']);
    }
}
