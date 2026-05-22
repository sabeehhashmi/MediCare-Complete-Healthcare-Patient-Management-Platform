<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;

class AddressController extends Controller
{
    public function __construct()
    {
       // $this->middleware('auth');
    }

    public function index()
    {
        $addresses = Address::where('user_id', Auth::id())
            ->orderBy('is_default', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();
            
        return response()->json([
            'status' => '1',
            'data' => $addresses
        ]);
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'plot_office_no' => 'required|string|max:255',
                'building_name' => 'required|string|max:255',
                'emirates' => 'required|string|max:255',
                'locality' => 'required|string|max:255',
                'name' => 'required|string|max:255',
                'mobile_number' => 'required|string|max:20',
                'address_type' => 'required|in:home,office,work',
                'location_name' => 'nullable|string|max:255',
                'latitude' => 'nullable|numeric',
                'longitude' => 'nullable|numeric',
                'is_default' => 'boolean'
            ]);

            if ($request->is_default) {
                Address::where('user_id', Auth::id())->update(['is_default' => false]);
            }

            $address = Address::create([
                'user_id' => Auth::id(),
                'location_name' => $request->location_name,
                'plot_office_no' => $request->plot_office_no,
                'building_name' => $request->building_name,
                'emirates' => $request->emirates,
                'locality' => $request->locality,
                'name' => $request->name,
                'mobile_number' => $request->mobile_number,
                'address_type' => $request->address_type,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'is_default' => $request->is_default ?? false
            ]);

            return response()->json([
                'status' => '1',
                'message' => 'Address added successfully',
                'data' => $address
            ]);

        } catch (Exception $e) {
            return response()->json([
                'status' => '0',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $address = Address::where('user_id', Auth::id())->findOrFail($id);

            $request->validate([
                'plot_office_no' => 'required|string|max:255',
                'building_name' => 'required|string|max:255',
                'emirates' => 'required|string|max:255',
                'locality' => 'required|string|max:255',
                'name' => 'required|string|max:255',
                'mobile_number' => 'required|string|max:20',
                'address_type' => 'required|in:home,office,work',
                'is_default' => 'boolean'
            ]);

            if ($request->is_default) {
                Address::where('user_id', Auth::id())
                    ->where('id', '!=', $id)
                    ->update(['is_default' => false]);
            }

            $address->update($request->all());

            return response()->json([
                'status' => '1',
                'message' => 'Address updated successfully',
                'data' => $address
            ]);

        } catch (Exception $e) {
            return response()->json([
                'status' => '0',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $address = Address::where('user_id', Auth::id())->findOrFail($id);
            
            if ($address->is_default) {
                $firstAddress = Address::where('user_id', Auth::id())
                    ->where('id', '!=', $id)
                    ->first();
                if ($firstAddress) {
                    $firstAddress->update(['is_default' => true]);
                }
            }
            
            $address->delete();

            return response()->json([
                'status' => '1',
                'message' => 'Address deleted successfully'
            ]);

        } catch (Exception $e) {
            return response()->json([
                'status' => '0',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function setDefault($id)
    {
        try {
            Address::where('user_id', Auth::id())->update(['is_default' => false]);
            
            $address = Address::where('user_id', Auth::id())->findOrFail($id);
            $address->update(['is_default' => true]);

            return response()->json([
                'status' => '1',
                'message' => 'Default address updated successfully'
            ]);

        } catch (Exception $e) {
            return response()->json([
                'status' => '0',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}