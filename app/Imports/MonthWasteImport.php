<?php

namespace App\Imports;

use App\MonthWaste;
//use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;

class MonthWasteImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */

    use Importable, SkipsFailures;

    public function model(array $row)
    {
        try {
            return new MonthWaste([
            //
          //  'id' => $row['id'],
                'ler' => $row['ler'],
                'rut' => $row['rut'],
                'entablishment' => $row['establecimiento'],
                'process' => $row['tratamiento'],
                'quantity' => $row['cantidad'],
                'carrier' => $row['rut_transportista'],
                'plate' => $row['patente'],
                'date' => $row['fecha'],

                'manage' => $row['gestion']

            ]);
        } catch (\Exception $e) {
            dd($e->getMessage(), $row);
        }
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function rules(): array
    {
        return [

            'ler' => 'required',
            'rut' => 'required',
            'establecimiento' => 'required',
            'tratamiento' => 'required',
            'cantidad' => 'required',
            'rut_transportista' => 'required',
            'patente' => 'required',

            'fecha' => 'required',
            'gestion' => 'required'

        ];
    }

    public function validationMessages()
    {
        return [
            'ler.required' => trans('Se requiere código LER'),
            'rut.required' => trans('Se requiere rut'),
            'cantidad.required' => trans('La cantidad no puede venir en blanco'),
            'rut_transportista.required' => trans('se requiere rut del transportista'),
            'patente.requierd' => trans('se requiere patente'),
        ];
    }

}
