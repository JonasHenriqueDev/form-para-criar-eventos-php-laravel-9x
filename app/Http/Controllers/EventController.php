<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;

class EventController extends Controller
{
    public function index()
    {
        // lógica pra pesquisar eventos
        $search = request('search');
        

        if ($search) {
            $events = Event::where([
                ['title', 'like', '%' . $search . '%']
            ])->get();
        } else {
            $events = Event::all();
        }

        return view('welcome', ['events' => $events, 'search' => $search]);
    }

    public function create()
    {
        return view('events.create');
    }

    public function store(Request $request)
    {
        $event = new Event;

        $event->title = $request->title;
        $event->city = $request->city;
        $event->private = $request->private;
        $event->event_date = $request->event_date;
        $event->description = $request->description;
        $event->items = $request->items;

        //formatação da data para o padrão brasileiro

        /*$requestDate = $request->event_date;
        $event_date = date('d-m-Y', strtotime($requestDate));
        $event->event_date = $event_date;*/


        //image upload
        if ($request->hasFile('image') && $request->file('image')->isValid()) {

            $requestImage = $request->image;
            $extension = $requestImage->extension();

            $imageName = md5($requestImage->getClientOriginalName() . strtotime("now")) . "." . $extension;

            $requestImage->move(public_path('img/events'), $imageName);
            $event->image = $imageName;
        }


        $event->save();

        return redirect('/');
    }

    public function show($id)
    {
        $event = Event::findOrFail($id);
        return view('events.show', ['event' => $event]);
    }
}
