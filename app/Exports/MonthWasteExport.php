<?php

namespace App\Exports;

use App\MonthWaste;
use Maatwebsite\Excel\Concerns\FromCollection;

class MonthWasteExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return MonthWaste::all();
    }

    
    public function headings()
    {
        return ["ler", "rut", "Establecimiento","tratamiento","cantidad","Region","rut_transportista","patente","fecha","gestion"];
    }
}
