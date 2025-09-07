<?php

namespace App\Exports;

use App\Models\Inventory;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class InventoryExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Inventory::all();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Name',
            'Description',
            'Quantity',
            'Price (Riel)',
            'Clothing Type',
            'Size',
            'Gender',
            'Color',
            'School House',
            'Created At',
        ];
    }

    public function map($inventory): array
    {
        return [
            $inventory->id,
            $inventory->name,
            $inventory->description,
            $inventory->quantity,
            number_format($inventory->price, 0, ',', '.'),
            $inventory->clothing_type,
            $inventory->size,
            $inventory->gender,
            $inventory->color,
            $inventory->school_house,
            $inventory->created_at->format('Y-m-d H:i'),
        ];
    }
}
