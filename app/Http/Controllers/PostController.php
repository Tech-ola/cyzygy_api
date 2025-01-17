<?php
namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function index()
    {
        try {
            $posts = Post::where('user_id', Auth::id())->get();
            return response()->json($posts, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch posts', 'details' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'title' => 'required|string',
                'content' => 'required|string',
            ]);

            $post = Post::create([
                'title' => $request->title,
                'content' => $request->content,
                'user_id' => Auth::id(),
            ]);

            return response()->json(['post' => $post, 'message' => 'Post created successfully'], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to create post', 'details' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $post = Post::findOrFail($id);

            if ($post->user_id !== Auth::id()) {
                return response()->json(['error' => 'Unauthorized access'], 403);
            }

            return response()->json($post, 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'Post not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch post', 'details' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $post = Post::findOrFail($id);

            if ($post->user_id !== Auth::id()) {
                return response()->json(['error' => 'Unauthorized access'], 403);
            }

            $request->validate([
                'title' => 'string',
                'content' => 'string',
            ]);

            $post->update($request->only('title', 'content'));

            return response()->json(['post' => $post, 'message' => 'Post updated successfully'], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'Post not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to update post', 'details' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $post = Post::findOrFail($id);

            if ($post->user_id !== Auth::id()) {
                return response()->json(['error' => 'Unauthorized access'], 403);
            }

            $post->delete();

            return response()->json(['message' => 'Post deleted successfully'], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'Post not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete post', 'details' => $e->getMessage()], 500);
        }
    }
}
