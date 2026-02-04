<?php

namespace App\Http\Controllers;

use App\Models\InventoryItem;
use App\Models\InventoryCheck;
use App\Models\InventoryCheckDetail;
use App\Models\MaintenanceTicket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InventoryCheckController extends Controller
{
    // Form untuk Inventaris Isi Pos
    public function createPos()
    {
        $items = InventoryItem::where('category', 'Isi Pos')->get();
        return view('inventory.pos', compact('items'));
    }

    // Form untuk Inventaris General
    public function createGeneral()
    {
        $items = InventoryItem::where('category', 'General')->get();
        return view('inventory.general', compact('items'));
    }

    // Store untuk Isi Pos
    public function storePos(Request $request)
    {
        return $this->storeInventoryCheck($request, 'Isi Pos');
    }

    // Store untuk General
    public function storeGeneral(Request $request)
    {
        return $this->storeInventoryCheck($request, 'General');
    }

    // Logika Umum Store
    private function storeInventoryCheck(Request $request, string $category)
    {
        $validated = $request->validate([
            'check_date' => 'required|date',
            'shift' => 'required|in:A,B,C,D',
            'pos_location' => 'required|string|max:255',
            'items' => 'required|array',
            'items.*.item_id' => 'required|exists:inventory_items,id',
            'items.*.condition' => 'required|in:Baik,Rusak',
            'items.*.remarks' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            // Buat header pengecekan
            $inventoryCheck = InventoryCheck::create([
                'user_id' => auth()->id(),
                'check_date' => $validated['check_date'],
                'shift' => $validated['shift'],
                'pos_location' => $validated['pos_location'],
            ]);

            $damagedItems = [];

            // Simpan detail pengecekan
            foreach ($validated['items'] as $itemData) {
                $detail = InventoryCheckDetail::create([
                    'check_id' => $inventoryCheck->id,
                    'item_id' => $itemData['item_id'],
                    'condition' => $itemData['condition'],
                    'remarks' => $itemData['remarks'] ?? null,
                ]);

                // AUTO-CREATE TICKET untuk barang rusak
                if ($itemData['condition'] === 'Rusak') {
                    $item = InventoryItem::find($itemData['item_id']);
                    
                    MaintenanceTicket::create([
                        'ticket_code' => MaintenanceTicket::generateTicketCode(),
                        'reporter_id' => auth()->id(),
                        'source_inv_detail_id' => $detail->id,
                        'issue_description' => "Barang Rusak: {$item->item_name} di {$validated['pos_location']}. " . 
                            ($itemData['remarks'] ?? 'Tidak ada keterangan tambahan.'),
                        'status' => 'Open',
                    ]);

                    $damagedItems[] = $item->item_name;
                }
            }

            DB::commit();

            $message = 'Pengecekan inventaris berhasil disimpan.';
            if (count($damagedItems) > 0) {
                $message .= ' Tiket maintenance dibuat untuk: ' . implode(', ', $damagedItems);
            }

            return redirect()->route('dashboard')
                ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }
}