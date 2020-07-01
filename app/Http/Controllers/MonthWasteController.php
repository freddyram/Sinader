<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Excel;
use App\Imports\MonthWasteImport;

use App\Exports\MonthWasteExport;



class MonthWasteController extends Controller
{
    private $excel;
    
    public function __construct(Excel $excel)
    {
        $this->excel = $excel;
    }
    
    public function import()
    {
        (new MonthWasteImport)->import(request()->file('file'));

        return redirect('/')->with('success', 'Archivo importado correctamente!');
    }


    public function export() 
    {
        Info('******** Export *********');
        return Excel::download(new MonthWasteExport, 'plantilla.xlsx');
    }



    public function saveDeclaration()
    {
         
        $user = Auth::user();
        $monthwaste = monthwaste::all()->toArray();
        
        foreach ($monthwaste as $waste ) {
            
            $company = Company::where('rut', substr($waste['rut'], 1, strpos($waste['rut'], '-') + 1))->first();
            $establishment = Establishment::where('retc_code', $waste['entablishment'])->first();
            $process = Process::where('id', $waste['process'])->first();
            $carrier = Carrier::where('rut', substr($waste['carrier'], 1, strpos($waste['carrier'], '-') + 1))->first();
            $vehicle = Vehicle::where('plate', $waste['plate'])->first();
            $ler_waste = LerWaste::where('waste_code', $waste['ler'])->first();
            $last_declaration = Declaration::where('establishment_id', $establishment->id)->get()->last();
            $declaration = new Declaration();
            $declaration->correlative       = $waste['waste'];
            if($last_declaration){
                $declaration->correlative   = $last_declaration->correlative + 1; 
            }
            
            $declaration->correlative_dv    = $this->getMod11Dv($declaration->correlative);
            $declaration->establishment_id  = $waste['entablishment'];
            $declaration->user_id           = $user->id;   
            $declaration->status            = 'CREADA';
            $declarationNew->save() 
            $waste_detail = new WasteDetail();
            $waste_detail->declaration_id   = $declaration_id;
            $waste_detail->waste            = $waste['waste'];   
            $waste_detail->company          = $company->name;
            $waste_detail->establishment    = $establishment->name;
            $waste_detail->processing       = $process->name;
            
            $waste_detail->quantity         = $waste['quantity'];
            $waste_detail->waste_id         = $ler_waste->id;
            $waste_detail->company_id       = $company->id;
            $waste_detail->establishment_id = $establishment->id;
            $waste_detail->process_id       = $waste['process'];
            $waste_detail->unit_id          = 1;
            $waste_detail->carrier_id       = $carrier->carrier_id;
            $waste_detail->plate            = $waste['plate'];

            $waste_detail->manage_id        = $waste['manage'];


            $waste_detail->save();


        }
    }
}
