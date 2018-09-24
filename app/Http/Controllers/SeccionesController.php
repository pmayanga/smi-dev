<?php

namespace smi\Http\Controllers;

use Illuminate\Http\Request;
use smi\Seccion;
use smi\SeccionDetalle;
use smi\SeccionAtributo;
use Carbon\Carbon;

class SeccionesController extends Controller
{
    public function get(){
        $secciones=Seccion::where([['eliminado','=','0']])->get();

        $data= array('status'=> true, 'data'=> $secciones);
        return $data;
    }

    public function getById($idSeccion){
        $baseLogoUrl='/img/seccion/logo/';
        $baseMarkerUrl='/img/seccion/marker/';

        $seccion = Seccion::find($idSeccion);

        $dataGeoJson=null;

        if($seccion->geoJsonFile <> null ){
            $fileName= $seccion->geoJsonFile;
            $baseSrc='/storage/app/public/json//';
            $file= base_path().$baseSrc.($fileName);

            $geoFile = @file_get_contents($file);

            if ($geoFile != false) {
                $dataGeoJson = file_get_contents($file);
            }
            
        }

        $logoUrl=null;
        $fullLogoUrl=null;
        if($seccion->logo <> null){
            $fullLogoUrl= url('/').$baseLogoUrl.($seccion->logo);
            $logoUrl= $baseLogoUrl.($seccion->logo);
        }else{
            $fullLogoUrl= url('/').$baseLogoUrl.('logo-default.png');
            $logoUrl= $baseLogoUrl.('logo-default.png');
        }

        $markerUrl=null;
        $fullMarkerUrl=null;
        if($seccion->marker <> null){
            $fullMarkerUrl= url('/').$baseMarkerUrl.($seccion->marker);
            $markerUrl= $baseMarkerUrl.($seccion->marker);
        }else{
            $fullMarkerUrl= url('/').$baseMarkerUrl.('marker-default.svg');
            $markerUrl= $baseMarkerUrl.('marker-default.svg');
        }

        $data= array(
            'status'=> true, 
            'data'=> array(
                'seccion' => $seccion,
                'geoJsonFile'=> $dataGeoJson,
                'fullLogoUrl'=> $fullLogoUrl,
                'logoUrl'=> $logoUrl,
                'fullMarkerUrl'=> $fullMarkerUrl,
                'markerUrl'=> $markerUrl
            )            
        );
        return $data;
    }

    public function getSeccionDetalleByIdSeccion($idSeccion){
        $detalleSeccion=SeccionDetalle::where([['idSeccion','=',$idSeccion]])->get();

        $data= array(
            'status'=> true, 
            'data'=> $detalleSeccion
        );
        
        $json = json_encode($data); 

        return $data;
    }

    public function save(Request $request){
        $user='admin';
        $terminal= $request->getHttpHost();

        error_log($request->input('id'));

        $seccion= new Seccion;

        if($request->input('id')==0){            
            $seccion->codigoGIS=$request->input('codigoGIS');
            $seccion->nombre=$request->input('nombre');
            $seccion->descripcion=$request->input('descripcion');
            $seccion->idSeccionPadre=$request->input('idSeccionPadre');
            $seccion->idTipoGeoData=$request->input('idTipoGeoData');
            $seccion->menuCategoria=$request->input('menuCategoria');
            $seccion->menuAccion=$request->input('menuAccion');
            $seccion->idTipoAccion=$request->input('idTipoAccion');
            $seccion->color=$request->input('color');
            $seccion->logo=$request->input('logo');
            $seccion->marker='';
            $seccion->activo=$request->input('activo');
            $seccion->fechaCrea= Carbon::now();
            $seccion->usuarioCrea=$user;
            $seccion->terminalCrea=$terminal;
            $seccion->fechaCambio=null;
            $seccion->usuarioCambio=null;
            $seccion->terminalCambio=null;
            $seccion->eliminado=0; 
            $seccion->save();
        }
        else{
            $seccion = Seccion::find($request->input('id'));
            $seccion->codigoGIS=$request->input('codigoGIS');
            $seccion->nombre=$request->input('nombre');
            $seccion->descripcion=$request->input('descripcion');
            $seccion->idSeccionPadre=$request->input('idSeccionPadre');
            $seccion->idTipoGeoData=$request->input('idTipoGeoData');
            $seccion->menuCategoria=$request->input('menuCategoria');
            $seccion->menuAccion=$request->input('menuAccion');
            $seccion->idTipoAccion=$request->input('idTipoAccion');
            $seccion->color=$request->input('color');
            $seccion->logo=$request->input('logo');
            $seccion->marker='';
            $seccion->activo=$request->input('activo');
            $seccion->fechaCambio=Carbon::now();
            $seccion->usuarioCambio=$user;
            $seccion->terminalCambio=$terminal;
            $seccion->eliminado=0; 
            $seccion->save();

        }

        $data= array('status'=> true, 'data'=> $seccion);
        return $data;

    }

    public function update(Request $request, $id){

        $seccion = Seccion::findOrFail($id);
        $seccion->update($request->all());

        $data= array('status'=> true, 'data'=> $seccion);
        return $data;
    }

    public function delete(Request $request, $id){
        $seccion = Seccion::findOrFail($id);
        $seccion->eliminado=0;
        $seccion->update($request->all());

        $data= array('status'=> true, 'data'=> $seccion);
        return $data;
    }

    public function uploadFile(Request $request){
        $seccion = Seccion::findOrFail($id);
        $seccion->activo=0;
        $seccion->update($request->all());

        return $seccion;
    }

    public function getSeccionDetalleInformacionPanel($id, $codigoGIS, $idCultivo){
        //$path = storage_path() . "/json/${filename}.json";
        $path=base_path() . '/storage/app/public/json/data-panel.json';

        $jsonData = json_decode(file_get_contents($path), true);
        
        $data= array(
            'status'=> true, 
            'data'=> $jsonData
        );
        return $data;
    }

    
}
