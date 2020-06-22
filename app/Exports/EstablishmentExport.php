<?php

namespace App\Exports;

use App\Establishment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Facades\DB;

class EstablishmentExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Establishment::where('type', 'GeneradorMunicipal')->with('Company')->with('commune')->with('region')->get();


    }
    public function headings()
    {
        return ["Rut", "Digito", "Compañía","Retc_code","Establecimiento","Region","Comuna"];
    }

    // public function title(): string
    // {
    // 	return '';
    // }
}
