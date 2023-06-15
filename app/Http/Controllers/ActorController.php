<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Actor;

class ActorController extends Controller
{
    public function index()
    {
        $actors = Actor::all();
        return view('admin.actors.actors', compact('actors'));
    }

    public function store(Request $request)
    {
        $actor = new Actor;
        $actor->actor_name = $request->input('actor_name');
        $actor->save();

        return redirect()->route('actors.index')->with('success', 'Aktor został dodany.');
    }

    public function destroy($id)
    {
        $actor = Actor::findOrFail($id);
        $actor->movies()->detach();
        $actor->delete();

        return redirect()->route('actors.index')->with('success', 'Aktor został usunięty.');
    }

    public function edit($id)
    {
        $actor = Actor::findOrFail($id);
        return view('admin.actors.edit', compact('actor'));
    }

    public function update(Request $request, $id)
    {
        $actor = Actor::findOrFail($id);
        $actor->actor_name = $request->input('actor_name');
        $actor->save();

        return redirect()->route('actors.index')->with('success', 'Aktor został zaktualizowany.');
    }
}
