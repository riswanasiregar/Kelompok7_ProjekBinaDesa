<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Multipleuploads;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::when(!Auth::user()->isAdmin(), function ($query) {
            $query->where('user_id', Auth::id());
        })
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('customers.index', compact('customers'));
    }

    public function create()
    {
        return view('customers.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:30',
            'address' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $data['user_id'] = Auth::id();

        Customer::create($data);

        return redirect()->route('customers.index')->with('success', 'Customer berhasil ditambahkan.');
    }

    public function show(Customer $customer)
    {
        if (!Auth::user()->isAdmin() && $customer->user_id !== Auth::id()) {
            abort(403);
        }

        $files = Multipleuploads::where('ref_table', 'pelanggan')
            ->where('ref_id', $customer->id)
            ->latest()
            ->get();

        return view('customers.show', compact('customer', 'files'));
    }

    public function edit(Customer $customer)
    {
        if (!Auth::user()->isAdmin() && $customer->user_id !== Auth::id()) {
            abort(403);
        }

        return view('customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        if (!Auth::user()->isAdmin() && $customer->user_id !== Auth::id()) {
            abort(403);
        }

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:30',
            'address' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $customer->update($data);

        return redirect()->route('customers.index')->with('success', 'Customer berhasil diperbarui.');
    }

    public function destroy(Customer $customer)
    {
        if (!Auth::user()->isAdmin() && $customer->user_id !== Auth::id()) {
            abort(403);
        }

        $customer->supportingFiles()->each(function ($file) {
            if ($file->filename && Storage::disk('public')->exists($file->filename)) {
                Storage::disk('public')->delete($file->filename);
            }
            $file->delete();
        });

        $customer->delete();

        return redirect()->route('customers.index')->with('success', 'Customer berhasil dihapus.');
    }
}

