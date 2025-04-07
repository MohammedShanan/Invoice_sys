<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SalesInvoice;
use App\Models\ReturnInvoice;
use App\Models\Client;
use Illuminate\Support\Facades\Log;

class InvoiceController extends Controller
{
    public function index(Request $request, $type)
    {
        $query = $type === 'sales' ? SalesInvoice::query() : ReturnInvoice::query();

        if ($request->has('search')) {
            $searchTerm = $request->get('search');
            $query->whereHas('client', function ($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                    ->orWhere('email', 'like', "%{$searchTerm}%");
            });
        }

        return response()->json($query->with('client')->get());
    }

    public function store(Request $request, $type)
    {
        $request->validate([
            'date' => 'required|date',
            'representative' => 'required|string',
            'client_id' => 'required|exists:clients,id',
            'invoice_type' => 'required|in:sales,maintenance',
            'discount' => 'nullable|in:flat_rate,percentage',
            'discount_value' => 'nullable|numeric',
            'tax' => 'required|numeric',
            'final_total' => 'required|numeric',
            'items' => 'required|array',
            'items.*.name' => 'required|string',
            'items.*.quantity' => 'required|numeric',
            'items.*.price' => 'required|numeric',
        ]);

        $invoiceModel = $type === 'sales' ? new SalesInvoice() : new ReturnInvoice();

        $invoice = $invoiceModel->create([
            'date' => $request->date,
            'representative' => $request->representative,
            'client_id' => $request->client_id, 
            'invoice_type' => $request->invoice_type,
            'discount' => $request->discount,
            'discount_value' => $request->discount_value,
            'tax' => $request->tax,
            'final_total' => $request->final_total,
        ]);
        foreach ($request->items as $item) {
            $invoice->items()->create([
                'name' => $item['name'],
                'description' => $item['description'] ?? null,
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                $type . '_invoice_id' => $invoice->id,
            ]);
        }

        return response()->json($invoice->load('items'), 201);
    }


    public function show($type, $id)
    {
        $invoiceModel = $type === 'sales' ? SalesInvoice::class : ReturnInvoice::class;
        $invoice = $invoiceModel::with('client')->findOrFail($id);
        return response()->json($invoice);
    }

    public function update(Request $request, $type, $id)
    {
        $invoiceModel = $type === 'sales' ? SalesInvoice::class : ReturnInvoice::class;
        $invoice = $invoiceModel::findOrFail($id);
        $invoice->update($request->all());
        return response()->json($invoice);
    }

    public function destroy($type, $id)
    {
        $invoiceModel = $type === 'sales' ? SalesInvoice::class : ReturnInvoice::class;
        $invoice = $invoiceModel::findOrFail($id);
        $invoice->delete();
        return response()->json(['message' => 'Invoice deleted successfully']);
    }
}
