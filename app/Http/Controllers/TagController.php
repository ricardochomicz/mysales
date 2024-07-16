<?php

namespace App\Http\Controllers;

use App\Http\Requests\TagRequest;
use App\Models\Tag;
use App\Services\BaseService;
use App\Services\TagService;
use App\Traits\TypeTags;
use Illuminate\Http\Request;

class TagController extends Controller
{
    use TypeTags;
    private $service;
    private $tagService;
    public function __construct()
    {
        $this->service = new BaseService(Tag::class);
        $this->tagService = new TagService();
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $view = [];
        return view('pages.tags.index', $view);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $view = [
            'types' => $this->typeTags()
        ];
        return view('pages.tags.create', $view);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TagRequest $request)
    {
        try {
            $this->tagService->store($request->all());
            notyf()->success('Tag cadastrada com sucesso.');
            return redirect()->route('tags.index');
        } catch (\Throwable $e) {
            notyf()->error("Ops! Erro ao cadastrar.");
            return back();
        }
    }



    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $data = $this->service->get($id);
        if(!$data){
            notyf()->error("Ops! Tag não encontrada.");
            return back();
        }
        $view = [
            'types' => $this->typeTags(),
            'data' => $data,
        ];
        return view('pages.tags.edit', $view);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TagRequest $request, $id)
    {
        try {
            $this->service->update($request->all(), $id);
            notyf()->success('Tag atualizada com sucesso.');
            return redirect()->route('tags.index');
        } catch (\Throwable $e) {
            notyf()->error("Ops! Erro ao atualizar.");
            return back();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $tag = $this->service->get($id, true);
        $this->tagService->destroy($id);
        if ($tag->deleted_at == null) {
            notyf()->success('Tag desativada com sucesso.');
            return redirect()->route('tags.index');
        } else {
            notyf()->success('Tag reativada com sucesso.');
            return redirect()->route('tags.index');
        }
    }



}
