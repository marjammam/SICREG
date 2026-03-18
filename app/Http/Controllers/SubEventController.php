<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubEventPatchRequest;
use App\Http\Requests\SubEventPostRequest;
use App\Models\SubEvent;
use Illuminate\Http\Request;

class SubEventController extends Controller
{
    public function listByEventId(int $eventId)
    {
        $subEvents = SubEvent::where('Evento_idEvento', $eventId)->get();

        return view('sub-event.sub-event', [
            'subEvents' => $subEvents,
            'eventId' => $eventId,
        ]);
    }

    public function store(SubEventPostRequest $request)
    {
        $subEvent = new SubEvent();

        $subEvent->nombreSE = $request->input('subevent-name');
        $subEvent->tipoEvento = $request->input('subevent-type');
        $subEvent->fechaSE = $request->input('subevent-date');
        $subEvent->horaInicio = $request->input('subevent-time1');
        $subEvent->horaFin = $request->input('subevent-time2');
        $subEvent->estadoSE = $request->input('subevent-state');
        $subEvent->Evento_idEvento = $request->input('eventId');

        $subEvent->save();

        return redirect('subeventos/evento/' . $subEvent->Evento_idEvento);
    }

    public function update(int $subEventId, SubEventPatchRequest $request)
    {
        $subEvent = SubEvent::find($subEventId);

        $subEvent->nombreSE = $request->input('subevent-name', $subEvent->nombreSE);
        $subEvent->tipoEvento = $request->input('subevent-type', $subEvent->tipoEvento);
        $subEvent->fechaSE = $request->input('subevent-date', $subEvent->fechaSE);
        $subEvent->horaInicio = $request->input('subevent-time1', $subEvent->horaInicio);
        $subEvent->horaFin = $request->input('subevent-time2', $subEvent->horaFin);
        $subEvent->estadoSE = $request->input('subevent-state', $subEvent->estadoSE);
        $subEvent->Evento_idEvento = $request->input('eventId', $subEvent->Evento_idEvento);

        $subEvent->save();

        return redirect('subeventos/evento/' . $subEvent->Evento_idEvento);
    }

    public function delete(int $subEventId)
    {
        $subEvent = SubEvent::find($subEventId);

        $subEvent->delete();

        return redirect('subeventos/evento/' . $subEvent->Evento_idEvento);
    }
}
