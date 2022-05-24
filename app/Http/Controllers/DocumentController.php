<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class DocumentController extends Controller

{
    private function countPages($path)
    {
        $pdftext = Storage::disk('public')->get($path);
        $num = preg_match_all("/\/Page\W/", $pdftext, $dummy);
        return $num;
    }
    public function show($id)
    {


        $document = Document::find($id);
        if ($document == null) {
            return abort(404);
        }
        $pages = $this->countPages($document->path);


        $document->path = asset(Storage::url($document->path));
        
        $commentaries=$document->commentaries->toArray();



        for($i=0;$i<count($commentaries);$i++){
            $user=User::find($commentaries[$i]['user_id'],['first_name','last_name','path_image','role',])->toArray();
            $user['path_image']=asset(Storage::url($user['path_image']));
            $commentaries[$i]['user']=$user;
        }


        return Inertia::render('Contents/Document', [
            'userData' => $this->getUserData(),
            'document' => $document,
            'numberOfPages' => $pages,
            'commentaries' =>$commentaries
        ]);
    }
}
