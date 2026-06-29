<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubEventPatchRequest;
use App\Http\Requests\SubEventPostRequest;
use App\Models\SubEvent;
use App\Models\Event;
use Illuminate\Http\Request;
use App\Exports\SubEventosExport;
use Maatwebsite\Excel\Facades\Excel;

class SubEventController extends Controller
{
    public function listByEventId(int $eventId, Request $request)
    {
        $evento = Event::findOrFail($eventId);
        $query = SubEvent::where('Evento_idEvento', $eventId)
        ->where('estadoSE', '!=', 'Eliminado');

        if ($request->isMethod('post')) {
            $nombreSE = $request->input('nombreSE');

            if ($nombreSE) {
                $query->where('nombreSE', 'like', '%' . $nombreSE . '%');
            }
        }

        return view('sub-event.sub-event', [
            'subEvents' => $query->get(),
            'eventId' => $eventId,
            'evento' => $evento,
        ]);
    }

    public function store(SubEventPostRequest $request)
    {
        $subEvent = new SubEvent();

        $subEvent->nombreSE = $request->input('subevent-name');
        $subEvent->descripcionSE = $request->input('description');
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
        $subEvent->descripcionSE = $request->input('description',$subEvent->descripcionSE);
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
        $subEvent = SubEvent::findOrFail($subEventId);
        $subEvent->update(['estadoSE' => 'Eliminado']);
        return redirect('subeventos/evento/' . $subEvent->Evento_idEvento);

        /*$subEvent = SubEvent::find($subEventId);
        $subEvent->delete();*/
       
    }
   public function listaSubevento(Request $request)
    {
        $query = SubEvent::with('event')
            ->withCount(['asistencias as nro_asistencia'])
            ->where('estadoSE', '!=', 'Eliminado')
            ->orderBy('fechaSE', 'desc');

        $buscar = $request->get('buscar');

        if ($buscar) {
            $query->where(function($q) use ($buscar) {
                $q->where('nombreSE', 'like', '%' . $buscar . '%')
                ->orWhereHas('event', function($q2) use ($buscar) {
                    $q2->where('nombreE', 'like', '%' . $buscar . '%');
                });
            });
        }

        $subEvents = $query->get();

        return view('sub-event.list_subevento', compact('subEvents', 'buscar'));
    }
 

    public function exportarExcel()
    {
        return Excel::download(new SubEventosExport(), 'subeventos_' . date('Y-m-d') . '.xlsx');
    }

}