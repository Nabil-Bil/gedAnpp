<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Commentary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class CommentaryController extends Controller
{
    public function store($id,Request $request)
    {

        $document = Document::find($id);
        if ($document == null) {
            return abort(404);
        }

        $comment=new Commentary();

        $comment->content=$request->comment;
        $comment->user_id=Auth::user()->id;
        $comment->document_id=$id;
        
        $comment->save();

        return Redirect::back();
    }

    public function destroy($id,$commentId){
        $document= Document::find($id);
        $comment=Commentary::find($commentId);
        if ($document == null || $comment==null) {
            return abort(404);
        }

        $comment->delete();
        return Redirect::back();



    }
}
