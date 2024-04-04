<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
     // Exibir todos os comentários
     public function index()
     {
         $comments = Comment::with('user')->get(); // Inclui dados do usuário
         return response()->json($comments);
     }
 
     // Armazenar um novo comentário
     public function store(Request $request)
     {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'post_id' => 'required|exists:posts,id',
            'content' => 'required',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }
    
        $validatedData = $validator->validated();
        $comment = Comment::create($validatedData);
    
        return response()->json(['message' => 'Comment created successfully.', 'comment' => $comment], 201);
     }
 
     // Exibir um único comentário
     public function show($coment)
     {
         // Inclui dados do usuário ao exibir o comentário
         $comment = Comment::with('user')->find($coment);
 
         if (!$comment) {
             return response()->json(['error' => 'Comment not found.'], 404);
         }
 
         return response()->json($comment);
     }
 
     // Atualizar um comentário existente
     public function update(Request $request, $coment)
     {
         $comment = Comment::find($coment);
 
         if (!$comment) {
             return response()->json(['error' => 'Comment not found.'], 404);
         }
 
         $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'post_id' => 'required|exists:posts,id',
            'content' => 'required',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }
    
        $validatedData = $validator->validated();
        $comment = Comment::create($validatedData);
    
        return response()->json(['message' => 'Comment updated successfully.', 'comment' => $comment], 204);
     }
 
     // Remover um comentário
     public function destroy($coment)
     {
         $comment = Comment::find($coment);
 
         if (!$comment) {
             return response()->json(['error' => 'Comment not found.'], 404);
         }
 
         $comment->delete();
 
         return response()->json(['success' => 'Comment deleted successfully.'], 204);
     }
}
