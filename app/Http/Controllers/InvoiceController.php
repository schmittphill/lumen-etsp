<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InvoiceModel;
use Illuminate\Support\Facades\Hash;

class InvoiceController extends Controller
{
    //

    //View all
    public function index(Request $request){
        $invoices = InvoiceModel::all();
        return response()->json($invoices);
    }

    // Create Invoice
    public function store(Request $request)
    {

        $validated = $this->validate($request, [
                                'customer_id' => 'nullable|integer',
                                'customer_name' => 'nullable|string',
                                'customer_phone' => 'nullable|string',
                                'business_type' => 'required|in:parking,retail,subscription,service',
                                'item_details' => 'required|array',
                                'amount' => 'required|numeric',
                                'currency' => 'required|string',
                                'issued_by' => 'required|integer',
                            ]);

        $hashInput = json_encode($validated['item_details'], JSON_UNESCAPED_UNICODE) . "-" . $validated['amount'] . "-" . $validated['currency'];
        $validated['ref'] = sha1($hashInput);

        $existingInvoice = InvoiceModel::where('ref', $validated['ref'])->first();

        if ($existingInvoice) {
            return response()->json(['message' => 'Invoice already exists', 'ref' => $validated['ref']]);
        }

        //$invoice = InvoiceModel::create($validated);

        $invoice = new InvoiceModel();

        $invoice->ref = $validated['ref'];
        $invoice->customer_id = $validated['customer_id'];
        $invoice->customer_name = $validated['customer_name'];
        $invoice->customer_phone = $validated['customer_phone'];
        $invoice->business_type = $validated['business_type'];
        $invoice->item_details = json_encode($validated['item_details'], JSON_UNESCAPED_UNICODE);
        $invoice->amount = $validated['amount'];
        $invoice->currency = $validated['currency'];
        $invoice->issued_by = $validated['issued_by'];

        $invoice->save();

        return response()->json(['message' => 'Invoice created successfully', 'ref' => $invoice->ref], 201);
    }

    // Retrieve Invoice
    public function show($ref)
    {
        $invoice = InvoiceModel::where('ref', $ref)->first();
        if (!$invoice) {
            return response()->json(['error' => 'Invoice not found'], 404);
        }
        return response()->json($invoice);
    }

    // Update Invoice
    public function update(Request $request, $ref)
    {
        $invoice = InvoiceModel::where('ref', $ref)->first();
        if (!$invoice) {
            return response()->json(['error' => 'Invoice not found'], 404);
        }
        if ($invoice->payment_status === 'paid' || $invoice->status === 'cancelled') {
            return response()->json(['error' => 'Cannot update a paid or cancelled invoice'], 400);
        }

        //$invoice->update($request->only(['customer_name', 'customer_phone', 'amount']));
        $invoice->update(['amount' => $request->amount, 'payment_status' => 'paid']);

        return response()->json(['message' => 'Invoice updated successfully']);
    }

    // Cancel Invoice
    public function cancel($ref)
    {
        $invoice = InvoiceModel::where('ref', $ref)->first();
        if (!$invoice) {
            return response()->json(['error' => 'Invoice not found'], 404);
        }
        if ($invoice->payment_status === 'paid') {
            return response()->json(['error' => 'Cannot cancel a paid invoice'], 400);
        }

        $invoice->update(['status' => 'cancelled']);
        return response()->json(['message' => 'Invoice cancelled successfully']);
    }

}
