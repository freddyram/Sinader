<?php

namespace App\Http\Controllers;

use App\Declaration;
use App\User;
use App\UserEstablishment;
use App\Establishment;
use App\WasteDetail;
use App\Company;
use App\MonthWaste;
use App\ProcessType;
use App\ManageType;

use App\Carrier;
use App\Vehicle;
use App\LerWaste;
use App\LerSubChapter;
use App\LerChapter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Imports\MonthWasteImport;
use App\Imports\MonthWasteImports;
use Excel;

use App\Exports\MonthWasteExport;


use \PDF;
use \QrCode;

class DeclarationController extends Controller
{
  
    public function index()
    {
        $user = Auth::user();
        $user_establishment = UserEstablishment::where('user_id', $user->id)->first();

        $declarations = Declaration::where('establishment_id',$user_establishment->establishment_id)->orderBy('correlative')->get();
        return response()->json($declarations);
    }


    public function indexAdmin()
    {

        $declarations = Declaration::orderBy('id')->get();
        return response()->json($declarations);
    }


    public function forreceiver($receiver_id){
        Info("******* declarations for receiver **********");
        Info($receiver_id);

        $declarations = Declaration::select(
            'declarations.id',
            'declarations.correlative', 
            'declarations.correlative_dv',
            'declarations.period',
            'declarations.created_at',
            'declarations.establishment',
            'declarations.status',
            'declarations.company',
            'declarations.rut',
            'declarations.establishment',
            'declarations.direccion',
            'declarations.comuna',
            'declarations.region'
            )->distinct()->join('waste_details','declarations.id',"=",'waste_details.declaration_id')->where('waste_details.establishment_id',$receiver_id)->where('generator','REGISTERED')->where('declarations.status','!=','CREADA')->orderBy('correlative')->get();

        return response()->json($declarations);   
    }


    public function forreceiverGenNN($receiver_id){
        Info("******* declarations for receiver **********");
        Info($receiver_id);

        $declarations = Declaration::select(
            'declarations.id',
            'declarations.correlative', 
            'declarations.correlative_dv',
            'declarations.period',
            'declarations.created_at',
            'declarations.establishment',
            'declarations.status',
            'declarations.company',
            'declarations.rut',
            'declarations.establishment',
            'declarations.direccion',
            'declarations.comuna',
            'declarations.region'
            )->distinct()->join('waste_details','declarations.id',"=",'waste_details.declaration_id')->where('waste_details.establishment_id',$receiver_id)->where('generator','UNREGISTERED')->orderBy('correlative')->get();

        return response()->json($declarations);   
    }


    public function declaration($declaration_id){
        $declaration = Declaration::where('id', $declaration_id)->get()->first();
        return response()->json($declaration);   
    }

    public function find($declaration_id){
        $declaration = Declaration::where('id', $declaration_id)->get()->first();
        return response()->json($declaration);   
    }

    public function create()
    {

        $user = Auth::user();
        $user_establishment = UserEstablishment::where('user_id', $user->id)->first();

        $last_declaration = Declaration::orderBy('correlative')->get()->last();

        $declaration = new Declaration();
        $declaration->correlative       = 1;
        if($last_declaration){
            $declaration->correlative   = $last_declaration->correlative + 1; 
        }
        
        $declaration->correlative_dv    = $this->getMod11Dv($declaration->correlative);
        $declaration->establishment_id  = $user_establishment->establishment_id;
        $declaration->user_id           = $user->id;   
        $declaration->status            = 'CREADA';

        // $declaration->save();

        return response()->json($declaration);
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {


        Info($request);

        $user = Auth::user();
        $user_establishment = UserEstablishment::where('user_id', $user->id)->first();

        $declaration = $request->Input('declaration');


        $declarationNew = Declaration::where('correlative', $declaration["correlative"])->get()->first();

        if(!$declarationNew){
            $declarationNew = new Declaration();
        }
        
        $declarationNew->correlative    = $declaration['correlative'];       
        $declarationNew->correlative_dv = $declaration['correlative_dv']; 
        $declarationNew->type           = $declaration['type'];
        $declarationNew->period         = $declaration['period'];

        $declarationNew->rut            = $declaration['rut'];
        $declarationNew->company        = $declaration['company'];
        $declarationNew->establishment  = $declaration['establishment'];
        $declarationNew->direccion      = $declaration['direccion'];
        $declarationNew->comuna         = $declaration['comuna'];
        $declarationNew->region         = $declaration['region'];
        $declarationNew->generator      = $declaration['generator'];
 
        $declarationNew->status            = 'CREADA';
        $declarationNew->establishment_id  = $user_establishment->establishment_id;
        $declarationNew->user_id           = $user->id;

        if ($declarationNew->save() ){
            WasteDetail::where('declaration_id', $declarationNew->id)->delete();
            $this->storeDetail($declaration['waste_detail'], $declarationNew->id, 0);
        }

        return response()->json($declarationNew);
    }


    public function storeDetail($residues, $declaration_id, $declaration_origin_id){

        Info("**************************");
        Info($residues);

        foreach ($residues as $waste ) {            
            $waste_detail = new WasteDetail();

            $waste_detail->declaration_id   = $declaration_id;

            $waste_detail->waste            = $waste['waste'];   
            $waste_detail->chapter          = $waste['chapter'];
            $waste_detail->subchapter       = $waste['subchapter'];

            $waste_detail->company          = $waste['company'];
            $waste_detail->establishment    = $waste['establishment'];
            $waste_detail->processing       = $waste['processing'];
            $waste_detail->gestion          = $waste['gestion'];

            $waste_detail->pais             = $waste['pais'];
            $waste_detail->empresa          = $waste['empresa'];
            $waste_detail->contacto         = $waste['contacto'];
            $waste_detail->email            = $waste['email'];

            $waste_detail->quantity         = $waste['quantity'];
            $waste_detail->waste_id         = $waste['waste_id'];

            $waste_detail->chapter_id         = $waste['chapter_id'];
            $waste_detail->subchapter_id         = $waste['subchapter_id'];

            $waste_detail->company_id       = $waste['company_id'];
            $waste_detail->establishment_id = $waste['establishment_id'];
            $waste_detail->manage_id        = $waste['manage_id'];
            $waste_detail->process_id       = $waste['process_id'];
            $waste_detail->unit_id          = $waste['unit_id'];
            $waste_detail->status           = 'ENVIADO';

            if(array_key_exists('carrier_id',$waste)){
                $waste_detail->carrier_id = $waste['carrier_id'];
                $waste_detail->carrier_name = $waste['carrier_name'];
                $waste_detail->trasnport_date = $waste['trasnport_date'];
                $waste_detail->plate = $waste['plate'];

                
            } else {
                $waste_detail->carrier_id = 1;
            }

            $waste_detail->save();
        }
    }

    public function savetraceability(Request $request){

        // Info($request);

        $declaration_origin = $request->input('declaration_origin');
        $waste_detail       = $request->input('waste_detail');


        $user = Auth::user();

        $user_establishment = UserEstablishment::where('user_id', $user->id)->first();
        $establishment = Establishment::where('id', $user_establishment->establishment_id)->get()->first();
        $company = Company::where('id',$establishment->company_id)->get()->first();


        $last_declaration = Declaration::where('establishment_id', $user_establishment->establishment_id)->get()->last();

        $declaration = new Declaration();
        $declaration->correlative       = 1;
        if($last_declaration){
            $declaration->correlative   = $last_declaration->correlative + 1; 
        }
        
        $declaration->declaration_origin_id = $declaration_origin['id'];
        $declaration->correlative_dv    = $this->getMod11Dv($declaration->correlative);
        $declaration->establishment_id  = $user_establishment->establishment_id;
        $declaration->user_id           = $user->id;   
        $declaration->status            = 'CREADA';

        $declaration->rut            = $company->rut . '-' . $company->dv;
        $declaration->company        = $company->name;
        $declaration->establishment  = $establishment->name;
        $declaration->direccion      = $establishment->street . ' ' . $establishment->number;
        $declaration->comuna         = $establishment->commune->name;
        $declaration->region         = $establishment->region->name;
        $declaration->type           = ' ';
        $declaration->period         = '2019';
        
 
        $declaration->establishment_id  = $user_establishment->establishment_id;
        $declaration->user_id           = $user->id;


        if ($declaration->save() ){
            WasteDetail::where('declaration_id', $declaration->id)->delete();
            $this->storeDetail($waste_detail, $declaration->id, $declaration_origin['id']);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Declaration  $declaration
     * @return \Illuminate\Http\Response
     */
    public function show(Declaration $declaration)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Declaration  $declaration
     * @return \Illuminate\Http\Response
     */
    public function edit(Declaration $declaration)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Declaration  $declaration
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Declaration $declaration)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Declaration  $declaration
     * @return \Illuminate\Http\Response
     */
    public function destroy(Declaration $declaration)
    {
        
    }
    
    public function delete($declaration_id)
    {
        Declaration::where('id',$declaration_id)->delete();
       
        WasteDetail::where('declaration_id',$declaration_id)->delete();
        
        return response()->json($declaration_id);
    }


    public function changeStatusEnviada($declaration_id)
    {
        $declaration = Declaration::where('id',$declaration_id)->get()->first();
        $declaration->status = 'ENVIADA';
        $declaration->save();
        
        return response()->json($declaration);
    }


    public function changeStatus(Request $request)
    {

        Info($request);

        $declaration_id = $request->input('declaration_id');
        $status = $request->input('status');

        $declaration = Declaration::where('id',$declaration_id)->get()->first();
        if($declaration){
            $declaration->status = $status;
            $declaration->save();
        }
        
        return response()->json($declaration);
    }


    public function sinMovimento(Request $request)
    {
        $user = Auth::user();

        $user_establishment = UserEstablishment::where('user_id', $user->id)->first();
        $establishment = Establishment::where('id', $user_establishment->establishment_id)->get()->first();
        $company = Company::where('id',$establishment->company_id)->get()->first();


        $declaration = $request->Input('declaration');


        $declarationNew = Declaration::where('correlative', $declaration["correlative"])->get()->first();

        if(!$declarationNew){
            $declarationNew = new Declaration();
        }
        

      

        $declarationNew->correlative    = $declaration['correlative'];       
        $declarationNew->correlative_dv = $declaration['correlative_dv']; 
        $declarationNew->type           = $declaration['type'];
        $declarationNew->period         = $declaration['period'];

        $declarationNew->rut            = $company->rut . '-' . $company->dv;
        $declarationNew->company        = $company->name;
        $declarationNew->establishment  = $establishment->name;
        $declarationNew->direccion      = $establishment->street . ' ' . $establishment->number;
        $declarationNew->comuna         = $establishment->commune->name;
        $declarationNew->region         = $establishment->region->name;

        $declarationNew->status            = 'SINMOVIMIENTO';
        $declarationNew->establishment_id  = $user_establishment->establishment_id;
        $declarationNew->user_id           = $user->id;

        $declarationNew->save(); 

        WasteDetail::where('declaration_id',$declarationNew->id)->delete();
  
        return response()->json($declarationNew);
    }



    public function pdf($id)
    {        

        Info("*****Path*****");
        Info(storage_path());

        $declaration = Declaration::where('id',$id)->get()->first();
        $waste_detail= WasteDetail::where('declaration_id',$declaration->id)->get();        
        $archivo_pdf = 'declaration_'.$id.'.pdf';

        $pdf = PDF::loadView('certificado', compact('declaration','waste_detail'));

        Info($archivo_pdf);

        $pdf->save(storage_path() . '/' .$archivo_pdf);

        return $pdf->stream('certificado');

    }

    function getMod11Dv( $num ){
    /* --------------------------------------------------------------------------------------- */
      $digits = str_replace( array( '.', ',' ), array( ''.'' ), strrev($num ) );
      if ( ! ctype_digit( $digits ) )
      {
        return false;
      }

      $sum = 0;
      $factor = 2;
     
      for( $i=0;$i<strlen( $digits ); $i++ )
      {
        $sum += substr( $digits,$i,1 ) * $factor;
        if ( $factor == 7 )
        {
          $factor = 2;
        }else{
         $factor++;
       }
      }
     
      $dv = 11 - ($sum % 11);
      if ( $dv < 10 )
      {
        return $dv;
      }
      if ( $dv == 10 )
      {
        return 'K';
      }
      return 0;
    }

     function upload(Request $request){
        //$rows = Excel::toArray(new MonthWaste, $request->file('file'));
        //return response()->json(["rows"=>$rows]);

        Info("****** Upload *********");
        Info($request);
        Info("***************");

        
        //$rows = Excel::toArray(new MonthWaste, $request->file('file'));
        MonthWaste::truncate();

        $ExcelImport = Excel::import(new MonthWasteImport, $request->file('file'));
        //(new MonthWasteImport)->import(request()->file('file'));

        Info("Termina ExcelImport");
        
        $errors = [];
        
        //return response()->json(["rows"=>$rows]);

        $monthwaste = monthwaste::all()->toArray();

        Info($monthwaste);
        
        $residue = [];
        $waste_detail = [];
        $line=0;
        $ind=0;
        $errors=0;
        
        foreach ($monthwaste as $waste ) {

            $line++;
            
            $rut_company = substr($waste['rut'], 0, -2); //strpos($waste['rut'], '-'));
            $rut_carrier = substr($waste['carrier'], 0, -2); //strpos($waste['carrier'], '-') - 1);

            Info("****** Upload *********");
            Info($rut_company);
            Info("-----------");
            Info($rut_carrier);
            Info("***************");

            $company = Company::where('rut', $rut_company)->get()->first();
            $establishment = Establishment::where('id', $waste['entablishment'])->first();
            $process = ProcessType::where('id', $waste['process'])->first();
            $manage = ManageType::where('id', $waste['manage'])->first();
            $carrier = Carrier::where('rut', $rut_carrier )->get()->first();
            $vehicle = Vehicle::where('plate', $waste['plate'])->get()->first();
            $ler_waste = LerWaste::where('waste_code', $waste['ler'])->get()->first();
            $waste_detail[$ind]['declaration_id']   = 1; //$declaration_id;
            
            if($ler_waste){
                $waste_detail[$ind]['waste']        = $ler_waste->name;
                $ler_subchapter = LerSubChapter::where('id', $ler_waste->subchapter_id)->first();
                $ler_chapter = LerChapter::where('id', $ler_subchapter->chapter_id)->first();
                $waste_detail[$ind]['chapter_id']      = $ler_chapter->id;
                $waste_detail[$ind]['subchapter_id']   = $ler_subchapter->id;

                $waste_detail[$ind]['chapter']      = $ler_chapter->name;
                $waste_detail[$ind]['subchapter']   = $ler_subchapter->name;



            }else{
                //return response()->json(["compañia no existe"]);
                $errors[] = "Linea ".$line.": Residuo no existe";
            }

            $waste_detail[$ind]['pais']     = 'Chile';   
            $waste_detail[$ind]['empresa']  = '';
            $waste_detail[$ind]['contacto'] = '';
            $waste_detail[$ind]['email']    = '';

            if($company){
                $waste_detail[$ind]['company_id']   = $company->id;
                $waste_detail[$ind]['company']      = $company->name;
            }else{
                //return response()->json(["compañia no existe"]);
                $errors[] = "Linea ".$line.": compañia no existe";
            }

            if($establishment){
                $waste_detail[$ind]['establishment_id'] = $establishment->id;
                $waste_detail[$ind]['establishment']    = $establishment->name;
            }else{
                //return response()->json(["Establecimiento no existe"]);
                $errors[] = "Linea ".$line.": Establecimiento no existe";
            }

            if($process){
                $waste_detail[$ind]['process_id']   = $process->id;
                $waste_detail[$ind]['processing']   = $process->name;
            }else{
                $errors[] = "Linea ".$line.": proceso no existe";
            }

            $waste_detail[$ind]['quantity']         = $waste['quantity'];

            if($ler_waste){
                $waste_detail[$ind]['waste_id']     = $ler_waste->id;
            }else{
                $errors[] = "Linea ".$line.": Código LER no existe";
            }
                 
            
            $waste_detail[$ind]['unit_id']          = 2;

            if($carrier){
                $waste_detail[$ind]['carrier_id']     = $carrier->id;
                $waste_detail[$ind]['carrier_name']   = $carrier->name;
                $waste_detail[$ind]['trasnport_date'] = $waste['date'];
               // $waste_detail->plate              = $waste['plate'];            
                
            }else{
                $errors[] = "Linea ".$line.": Transportista no existe";
            }

            $waste_detail[$ind]['plate']            = $waste['plate'];

             if($manage){
                $waste_detail[$ind]['manage_id']   = $manage->id;
                $waste_detail[$ind]['gestion']   = $manage->name;
            }else{
                $errors[] = "Linea ".$line.": gestión no existe";
            }


            $ind++;

        }

        if($errors){
            Info($errors);
            return response()->json(["errors"=>$errors]);
        }else{
            Info($waste_detail);
            return response()->json($waste_detail);  
        }

    }

    public function export() 
    {
        Info('******** Export *********');
        return Excel::download(new MonthWasteExport, 'plantilla.xlsx');
    }

}
