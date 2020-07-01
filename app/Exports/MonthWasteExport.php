<?php

namespace App\Exports;

use App\MonthWaste;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class MonthWasteExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return MonthWaste::all();
    }


    public function headings(): array
    {
        return ['ler', 'rut', 'Establecimiento','tratamiento','cantidad','Region','rut_transportista','patente','fecha','gestion'];
    }
}
