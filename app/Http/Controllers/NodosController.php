<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Nodos;
use Exception;
use \NumberFormatter;

use  Stichoza\GoogleTranslate\GoogleTranslate;
use DateTimeZone; 

class NodosController extends Controller
{
    //
    public function index(Request $request){
        

        try{

            $nodos = Nodos::where('parent',null)->get();

            if ($request->hasHeader('Accept-Language')) {
                $language = '';
                $language = $request->header('Accept-Language');

                foreach($nodos as $item){
                    $item->title = GoogleTranslate::trans($item->title, $language, "es");
                }

            }
            if ($request->ZonaHoraria){
                $ZonaHoraria = preg_replace('/\\\\/', '', $request->ZonaHoraria); 
                 
                foreach($nodos as $item){
                    $item->created_at = $item->created_at->setTimezone(new DateTimeZone($ZonaHoraria));
                    $item->updated_at = $item->updated_at->setTimezone(new DateTimeZone($ZonaHoraria));
                }
            }
            $parametros['status'] = true;
            $parametros['message'] = 'Nodos consultados satisfactoriamente';
            $parametros['data'] = $nodos;

            return response()->json($parametros,200);
        }catch(\Exception $e){

            $parametros['status'] = false;
            $parametros['message'] = 'Error : ' . $e->getMessage() ;
            $parametros['data'] = null;

            return response()->json($parametros ,200);
        } 
    }

    public function find(Request $request){
        
        $id = $request->id;
        $deep = ($request->deep) ? $request->deep : 0;

        try{
            $nodo = Nodos::findorFail($id);

            $children = Nodos::where('parent', $id)->get();

            if ($request->hasHeader('Accept-Language')) {
                $language = '';
                $language = $request->header('Accept-Language');

                $nodo->title = GoogleTranslate::trans($nodo->title, $language, "es");
                foreach($children as $item){
                    $item->title = GoogleTranslate::trans($item->title, $language, "es");
                }
            }

            if ($request->ZonaHoraria){
                $ZonaHoraria = preg_replace('/\\\\/', '', $request->ZonaHoraria); 

                $nodo->created_at = $nodo->created_at->setTimezone(new DateTimeZone($ZonaHoraria));
                $nodo->updated_at = $nodo->updated_at->setTimezone(new DateTimeZone($ZonaHoraria));

                foreach($children as $item){
                    $item->created_at = $item->created_at->setTimezone(new DateTimeZone($ZonaHoraria));
                    $item->updated_at = $item->updated_at->setTimezone(new DateTimeZone($ZonaHoraria));
                }
            }

            if ($request->deep){
                if ($deep == 2){

                    foreach($children as $item){
                        $childrenMore = Nodos::where('parent',$item->id)->get();
                        $item->children = $childrenMore;
                    }


                }
            }

            $myNodos['parent'] = $nodo;
            $myNodos['children'] = $children;

            $parametros['status'] = true;
            $parametros['message'] = 'Nodo consultado satisfactoriamente';
            $parametros['data'] = $myNodos;        

            return response()->json($parametros,200);
        }catch(\Exception $e){
            $parametros['status'] = false;
            $parametros['message'] = "Error consultado nodo $id : " . $e->getMessage() ;
            $parametros['data'] = null;

            return response()->json($parametros ,401);            
        }
    }

    public function create(Request $request){
        
        try{
            $data = $request->only('parent','title');
            $nodo = Nodos::create($data);

            $nodo->update([
                'title' => $this->convertir($nodo->id)
            ]
            );

            $parametros['status'] = true;
            $parametros['message'] = 'Nodo creado satisfactoriamente';
            $parametros['data'] = $nodo;       

            return response()->json($parametros,200);
        }catch(\Exception $e){
            $parametros['status'] = false;
            $parametros['message'] = "Error creando nodo : " . $e->getMessage() ;
            $parametros['data'] = null;

            return response()->json($parametros ,401);            
        }
    }

    
    public function delete(Request $request){
        
        $id = $request->id;
        
        try{
            $nodo = Nodos::findorFail($id);
            if ($nodo){

                $children = Nodos::where('parent', $id)->get();
                if (count($children) > 0){
                     throw new Exception('- No se puede eliminar nodo con Hijos');
                }

                $nodo->delete();

                $parametros['status'] = true;
                $parametros['message'] = 'Nodo eliminado satisfactoriamente';
                $parametros['data'] = $nodo;      
            }else{
                throw new Exception('Error eliminando nodo');
            }

            return response()->json($parametros,200);
        }catch(\Exception $e){
            $parametros['status'] = false;
            $parametros['message'] = "Error eliminando el  nodo $id : " . $e->getMessage() ;
            $parametros['data'] = null;

            return response()->json($parametros ,401);            
        }

    }




    function basico($numero) {
        $valor = array ('uno','dos','tres','cuatro','cinco','seis','siete','ocho',
        'nueve','diez','once','doce','trece','catorce','quince','dieciseis','diecisiete','dieciocho','diecinueve','veinte','veintiuno ','vientidos ','veintitrés ', 'veinticuatro','veinticinco',
        'veintiséis','veintisiete','veintiocho','veintinueve');
        return $valor[$numero - 1];
    }
        
    function decenas($n) {
        $decenas = array (30=>'treinta',40=>'cuarenta',50=>'cincuenta',60=>'sesenta',
        70=>'setenta',80=>'ochenta',90=>'noventa');
        if( $n <= 29) return $this->basico($n);
        $x = $n % 10;
        if ( $x == 0 ) {
        return $decenas[$n];
        } else return $decenas[$n - $x].' y '. $this->basico($x);
    }
        
    function centenas($n) {
        $cientos = array (100 =>'cien',200 =>'doscientos',300=>'trecientos',
        400=>'cuatrocientos', 500=>'quinientos',600=>'seiscientos',
        700=>'setecientos',800=>'ochocientos', 900 =>'novecientos');
        if( $n >= 100) {
            if ( $n % 100 == 0 ) {
                return $cientos[$n];
            } else {
                $u = (int) substr($n,0,1);
                $d = (int) substr($n,1,2);
                return (($u == 1)?'ciento':$cientos[$u*100]).' '. $this->decenas($d);
            }
        } else return $this->decenas($n);
    }
        
    function miles($n) {
        if($n > 999) {
            if( $n == 1000) {return 'mil';}
        else {
            $l = strlen($n);
            $c = (int)substr($n,0,$l-3);
            $x = (int)substr($n,-3);
            if($c == 1) {$cadena = 'mil '. $this->centenas($x);}
            else if($x != 0) {$cadena = $this->centenas($c).' mil '. $this->centenas($x);}
            else $cadena = $this->centenas($c). ' mil';
            return $cadena;
        }
        } else return $this->centenas($n);
    }
        
    function millones($n) {
        if($n == 1000000) {return 'un millón';}
        else {
            $l = strlen($n);
            $c = (int)substr($n,0,$l-6);
            $x = (int)substr($n,-6);
            if($c == 1) {
                $cadena = ' millón ';
            } else {
                $cadena = ' millones ';
            }
            return $this->miles($c).$cadena.(($x > 0)?$this->miles($x):'');
        }
    }
    function convertir($n) {
        switch (true) {
            case ( $n >= 1 && $n <= 29) : return $this->basico($n); break;
            case ( $n >= 30 && $n < 100) : return $this->decenas($n); break;
            case ( $n >= 100 && $n < 1000) : return $this->centenas($n); break;
            case ($n >= 1000 && $n <= 999999): return $this->miles($n); break;
            case ($n >= 1000000): return $this->millones($n);
        }
    }



}
