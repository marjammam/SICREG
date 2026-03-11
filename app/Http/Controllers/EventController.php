<?php

namespace App\Http\Controllers;

use App\Http\Requests\EventPatchRequest;
use App\Http\Requests\EventPostRequest;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function list()
    {
        $events = Event::orderBy('fechaInicioE', 'desc')->get();

        return view('event.event', ['events' => $events]);
    }

    public function store(EventPostRequest $request)
    {
        $event = new Event();

        $event->nombreE = $request->input('name');
        $event->descripcionE = $request->input('description');
        $event->fechaInicioE = $request->input('event-date1');
        $event->fechaFinE = $request->input('event-date2');
        $event->estadoE = $request->input('state');

        $event->save();

        return redirect('eventos');
    }

    public function update(int $eventId, EventPatchRequest $request)
    {
        $event = Event::find($eventId);

        $event->nombreE = $request->input('name', $event->nombreE);
        $event->descripcionE = $request->input('description', $event->descripcionE);
        $event->fechaInicioE = $request->input('event-date1', $event->fechaInicioE);
        $event->fechaFinE = $request->input('event-date2', $event->fechaFinE);
        $event->estadoE = $request->input('state', $event->estadoE);

        $event->save();

        return redirect('eventos');
    }
}
