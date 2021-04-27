<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\mdSegments;
use App\Http\Requests\admin\SegmentsFormRequest;
use App\Library\FilesControl;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class SegmentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $Segments = mdSegments::all();

        $pathImagens = FilesControl::getPathImages();

        return view('admin.segments.Segments',[
            'listSegment'   =>  $Segments,
            'pathImagens'   =>  $pathImagens
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.segments.addSegments');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SegmentsFormRequest $request)
    {
        if(!isset($request->imageSave) || empty($request->imageSave) || is_null($request->imageSave)){
            return back()->with('error','Erro: deve cadastrar uma imagem para o segmento');
        }

        $Segment = new mdSegments();
        $Segment->name = $request->name;
        $Segment->description = $request->description;

        if($Segment->save()){

            if(isset($request->imageSave)){
                if(!is_null($request->imageSave)){
                    if($request->hasFile('imageInput')){
                        $imageSave = $request->imageSave;
                        $imageInput = $request->file('imageInput');
                        try {
                            $Segment->path_image = FilesControl::saveImage($imageSave, $imageInput,'segments', $Segment->id,1);
                        } catch (\Exception $exception) {
                            $Segment->path_image = null;
                            return back()->with('error','Erro Segmento ID: ('.$Segment->id.') '.$exception->getMessage());
                        }finally{
                            $Segment->save();
                        }
                    }
                }
            }

            return back()->with('success','ID: ('.$Segment->id.') Segmento cadastrado com sucesso');
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\mdSegments  $mdSegments
     * @return \Illuminate\Http\Response
     */
    public function show(mdSegments $mdSegments)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\mdSegments  $mdSegments
     * @return \Illuminate\Http\Response
     */
    public function edit(mdSegments $segment)
    {
        $pathImagens = FilesControl::getPathImages();

        return view('admin.segments.editSegments',[
            'Segment'       => $segment,
            'pathImagens'   =>  $pathImagens
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\mdSegments  $mdSegments
     * @return \Illuminate\Http\Response
     */
    public function update(SegmentsFormRequest $request, mdSegments $segment)
    {
        if(!isset($request->imageSave) || empty($request->imageSave) || is_null($request->imageSave)){
            return back()->with('error','Erro: deve cadastrar uma imagem para o segmento');
        }

        $segment->name = $request->name;
        $segment->alterStatusManual = true;
        $segment->status = $request->status;
        $segment->description = $request->description;

        // Upload of Imagen
        if(isset($request->imageSave)){
            if(!is_null($request->imageSave)){
                if($request->hasFile('imageInput')){
                    $imageSave = $request->imageSave;
                    $imageInput = $request->file('imageInput');

                    if (!is_null($segment->path_image)) {
                        $path_img = storage_path('app/public/upload/images/segments/' . $segment->id);
                        if(FilesControl::deleteImage($path_img,$segment->path_image)){
                            try {
                                $segment->path_image = FilesControl::saveImage($imageSave, $imageInput,'segments', $segment->id,1);
                            } catch (\Exception $exception) {
                                return back()->with('error','Erro Segmento ID: ('.$segment->id.') '.$exception->getMessage());
                            }
                        }else{
                            return back()->with('error','Erro Segmento ID: ('.$segment->id.'). Erro ao deletar a imagem anterior!!!');
                        }
                    }else{
                        try {
                            $segment->path_image = FilesControl::saveImage($imageSave, $imageInput,'segments', $segment->id,1);
                        } catch (\Exception $exception) {
                            return back()->with('error','Erro Segmento ID: ('.$segment->id.') '.$exception->getMessage());
                        }
                    }

                }
            }
        }

        if($segment->save()){
            return back()->with('success','ID: ('.$segment->id.') Segmento alterado com sucesso');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\mdSegments  $mdSegments
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        if(mdSegments::where('id', $request->Segment_id)->exists()) {
            $segment = mdSegments::findOrFail($request->Segment_id);
            $pathObject = storage_path('app/public/upload/images/segments/' . $segment->id);
            try {
                if ($segment->delete()) {
                    if (is_dir($pathObject)) {
                        if ( FilesControl::deleteImageAndPath($pathObject)) {
                            return back()->with('success', 'ID: ' . '(' . $segment->id . ')' . ' Segmento e imagem foram deletados com sucesso');
                        } else {
                            return back()->with('error', 'Erro ID: ' . '(' . $segment->id . ')' . ' Segmento deletado, mas imagem não foi deletada!!!');
                        }
                    } else {
                        return back()->with('success', 'ID: ' . '(' . $segment->id . ')' . ' Segmento deletado com sucesso');
                    }
                } else {
                    return back()->with('error', 'Erro ID: ' . '(' . $segment->id . ')' . ' Segmento não foi deletado!!!');
                }
            } catch (\Exception $exception) {
                if($exception->getCode()==23000)
                    return back()->with('error', 'Erro: (23000) ID: '.'('. $segment->id .')'.
                        ' Segmento possuí registros filhos verifique as tabelas dependentes!!!');
            }
        }else{
            return back()->with('error', 'Erro ID: '.'('. $request->Segment_id .')'.' Segmento não existe!!!');
        }
    }

    public function changeStatus(Request $request)
    {
        $objectID       = $request->object_id;
        $objectStatus   = $request->object_status;

        if(mdSegments::where('id', $objectID)->exists()) {

            $segment = mdSegments::where('id', $objectID)->first();

            $segment->alterStatusManual = true;
            $segment->status            = $objectStatus;

            if($segment->save()){
                $responseObject['success'] = true;
                if(strtoupper($objectStatus) == 'S'){
                    $responseObject['message'] = 'Segmento ID ('.$objectID.') habilitado';
                } else {
                    $responseObject['message'] = 'Segmento ID ('.$objectID.') desabilitado';
                }

                unset($segment);

                echo json_encode($responseObject);
                return;
            } else {
                $responseObject['success'] = false;
                $responseObject['message'] = 'Segmento ID ('.$objectID.') erro ao alterar o status!';

                unset($segment);

                echo json_encode($responseObject);
                return;
            }

        } else {
            $responseObject['success'] = false;
            $responseObject['message'] = 'Segmento ID ('.$objectID.') não encontrado!';
            echo json_encode($responseObject);
            return;
        }

    }
}
