<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
      // Exibir todos os posts
      public function index()
      {
          $posts = Post::paginate(15);
          return response()->json($posts);
      }
  
      // Armazenar um novo post
      public function store(Request $request)
      {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'title' => 'required|max:255',
            'content' => 'required',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }
    
        $validatedData = $validator->validated();
        $post = Post::create($validatedData);
    
        return response()->json(['message' => 'Post created successfully.', 'post' => $post], 201);
      }
  
      // Exibir um único post
      public function show($post)
      {
          $post = Post::find($post);
  
          if (!$post) {
              return response()->json(['error' => 'Post not found.'], 404);
          }
  
          return response()->json($post);
      }
  
      // Atualizar um post existente
      public function update(Request $request, $post)
      {
          $post = Post::find($post);
  
          if (!$post) {
              return response()->json(['error' => 'Post not found.'], 404);
          }
  
          $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'title' => 'required|max:255',
            'content' => 'required',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }
    
        $validatedData = $validator->validated();
        $post = Post::create($validatedData);
    
        return response()->json(['message' => 'Post updated successfully.', 'post' => $post], 204);
      }
  
      // Remover um post
      public function destroy($post)
      {
          $post = Post::find($post);
  
          if (!$post) {
              return response()->json(['error' => 'Post not found.'], 404);
          }
  
          $post->delete();
  
          return response()->json(['success' => 'Post deleted successfully.'], 204);
      }
}
