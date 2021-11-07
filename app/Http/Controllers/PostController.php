<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUpdatePost;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PostController extends Controller
{
    public function index() {

        $posts = Post::orderBy('id', 'ASC')->paginate();

        // dd($posts);

        return view('admin.posts.index', compact('posts'));
    }

    public function create() {
        return view('admin.posts.create');
    }

    public function store(StoreUpdatePost $request) {

        $data = $request->all();
        if ($request->image->isValid()) {
            $nameFile = Str::of($request->title)->slug('-') . '.' .$request->image->getClientOriginalExtension();
            $image = $request->image->storeAs('posts', $nameFile, 'public');
            $data['image'] = $image;

        }

        Post::create($data);
        return redirect()
                ->route('posts.index')
                ->with('message', 'Post Criado com sucesso');
    }

    public function show($id) {

        // $post = Post::where('id', $id)->first();
        if (!$post = Post::find($id)) {
            return redirect()->route('posts.index');
        }
        return view('admin.posts.show', compact('post'));

    }

    public function edit($id) {

        if (!$post = Post::find($id)) {
            return redirect()->back(); // route('posts.index') também funciona.
        }
        return view('admin.posts.edit', compact('post'));
    }

    public function update(StoreUpdatePost $request, $id) {

        if (!$post = Post::find($id)) {
            return redirect()->back();
        }

        $data = $request->all();
        if ($request->image && $request->image->isValid()) {

            if (Storage::exists('public/'.$post->image)) {
                Storage::delete('public/'.$post->image);
            }

            $nameFile = Str::of($request->title)->slug('-') . '.' .$request->image->getClientOriginalExtension();
            $image = $request->image->storeAs('posts', $nameFile, 'public');
            $data['image'] = $image;
        }

        $post->update($data);
        return redirect()
                ->route('posts.index')
                ->with('message', 'Post Atualizado com sucesso');
    }

    public function destroy($id) {

        if (!$post = Post::find($id)) {
            return redirect()->route('posts.index');
        }

        if (Storage::exists('public/'.$post->image)) {
            Storage::delete('public/'.$post->image);
        }

        $post->delete();
        return redirect()
            ->route('posts.index')
            ->with('message', 'Post Deletado com sucesso');
    }

    public function search(Request $request) {

        $filters = $request->except('_token'); //Pega todos os dados do formulário.

        $posts = Post::where('title', '=', $request->search)
                ->orwhere('content', 'LIKE', "%{$request->search}%")
                ->paginate();
                // ->toSql();  para analisar a sql
                // dd($posts); para debugar
        return view('admin.posts.index', compact('posts', 'filters')); //Passa a variável filter para a view por causa da paginação.

    }
}
