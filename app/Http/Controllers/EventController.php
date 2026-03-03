<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function list()
    {
        $events = Event::all();

        return view('event.event', ['events' => $events]);
    }

    public function store(Request $request)
    {
        $event = new Event();

        $event->nombreE = $request->name;
        $event->descripcionE = $request->description;
        $event->estadoE = $request->state;

        $event->save();

        return redirect('eventos');
    }

    public function update(int $eventId, Request $request)
    {
        $event = Event::find($eventId);

        $event->nombreE = $request->name == null ? $event->nombreE : $request->name;
        $event->descripcionE = $request->description == null ? $event->descripcionE : $request->description;
        $event->estadoE = $request->state == null ? $event->estadoE : $request->state;

        $event->save();

        return redirect('eventos');
    }
}
