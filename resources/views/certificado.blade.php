<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <title>Certificado</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous">
</script>
<style type="text/css">
	.table-condensed{
	  font-size: 10px;
	}
	.text-small1{
	  font-size: 18px;
	}	
	.text-small2{
	  font-size: 14px;
	}
	.text-small3{
	  font-size: 10px;
	}
</style>
  </head>
  <body>
 
        <div>

        <table >
        	<tbody>
        		<tr>
			        <td>
			            <img src="http://drive.google.com/uc?export=view&id=1E59iJEe8OCPcPEtDV0iCwOjxAGeMb1pe">
			        </td>
			        <td>
			        	<h1 class="text-small1">COMPROBANTE - RECEPCIÓN DE INFORMACIÓN</h1>
						<h1 class="text-small3">SISTEMA VENTANILLA ÚNICA DEL RETC</h1>
						<h1 class="text-small2">DECLARACIÓN ANUAL DE RESIDUOS NO PELIGROSOS</h1>
						<h1 class="text-small3">SISTEMA NACIONAL DE DECLARACIÓN DE RESIDUOS (SINADER)</h1>
			        </td>
			        <td>
			        	<img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(100)->generate( 'https://sinader.mma.gob.cl/api/validate/' . $declaration['id'])) !!} ">
			        </td>
			        
			    </tr>
			</tbody>
		</table>

          <br>
          <br>
        <table >
        	<tbody>
        		<tr>
			        <td>
			          Folio:{{ $declaration["correlative"] }}-{{$declaration["correlative_dv"] }}
			        </td>
			        <td>
			        	Estado: {{  $declaration["status"]=="SINMOVIMIENTO" ? "SIN MOVIMIENTO" : $declaration["status"] }}
			        </td>
			        
			    </tr>
        		<tr>
			        <td>
			          Establecimiento:{{ $declaration["establishment"] }} 
			        </td>
			        <td>
			        	
			        </td>
			    </tr>
        		<tr>
			        <td>
			          Empresa: {{ $declaration["rut"]}}|{{ $declaration["company"] }}
			        </td>
			        <td>
			        	
			        </td>
			    </tr>
        		<tr>
			        <td>
			          Fecha: {{ $declaration["created_at"] }}
			        </td>
			        <td>
			          Periodo: {{ $declaration["period"]}} 	
			        </td>
			    </tr>
     		    <tr>
			        <td>
			          Comuna: {{ $declaration["comuna"] }}
			        </td>
			        <td>
			          Región: {{ $declaration["region"]}} 		
			        </td>
			    </tr>
        	</tbody>
        </table> 


          <br>
          <br>

	<p class="text-small3">El presente mensaje únicamente da cuenta de la recepción de la información reportada por el establecimiento individualizado, respecto del cumplimiento
de las obligaciones establecidas en el D.S. Nº1/2013 MMA (Art. Nº26, 27 o 28)</p>

	<table class="table table-striped table-condensed">
	  <thead>
	    <tr>
	      <th class="display-6">Residuo</th>
	      <th class="display-6">Cantidad</th>
	      <th class="display-6">Tipo Teatamiento</th>
	      <th class="display-6">Destino</th>
	    </tr>
	  </thead>
	  <tbody>
	    @foreach($waste_detail as $waste)
	      <tr>
	        <td class="display-6">
	          {{ $waste->waste }}
	        </td>
	        <td class="display-6">
	          {{ $waste->quantity }}
	        </td>
	        <td class="display-6">
	          {{ $waste->processing }}
	        </td>
	        <td class="display-6">
	          {{ $waste->establishment }}
	        </td>

	      </tr>
	     @endforeach
	  </tbody>
	</table>

<p class="text-small3">La integridad y veracidad de la información es de exclusiva responsabilidad del Titular, el cual ante un proceso de fiscalización debe evidenciar las
metodologías utilizadas de estimaciones (si fuera el caso) y considerar comprobantes (ej.: voucher de pesaje, guías de despacho u otros) que permitan
verificar la información reportada para la gestión de sus residuos.</p>

         
        </div>
   
  </body>
</html>