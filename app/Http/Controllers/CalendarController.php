<?php

namespace App\Http\Controllers;

use App\Calendar;
use Illuminate\Http\Request;
use App\Http\Resources\CalendarResource;
use Symfony\Component\HttpFoundation\Response;
use Carbon\Carbon;
use PDOException;
use Exception;
use Illuminate\Support\Facades\DB;

class CalendarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return CalendarResource::collection(Calendar::all());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       /* $new_calendar = Calendar::create($request->all());
        return response()->json([
            'data' => new CalendarResource($new_calendar),
            'message' => 'Successfully added new event!',
            'status' => Response::HTTP_CREATED
        ]);*/
        if (!$request->ajax()) return redirect('/');
           $rules = [
                  'event_name' => 'required|max:100'  
            ];
            $messages = [
                'event_name.max' => 'La longitud máxima del :attribute es 100 caracteres.',
            ];
            $this->validate($request, $rules, $messages);            
                try{
                    if (!$request->ajax()) return redirect('/');
                    //$input = $request->start_date.$request->start_time;
                    //$inputEnd = $request->end_date.$request->end_time;
                    $evento = new Calendar();
                    $evento->event_name = $request->event_name;
                    //$evento->descripcion = $request->descripcion;
                   // $evento->start_date = Carbon::parse($input);
                    //$evento->end_date = Carbon::parse($inputEnd);
                      $evento->start_date = $request->start_date;
                    $evento->end_date = $request->end_date;
                   
                    $evento->save();
                } catch (Exception $e){
                    return redirect()->withErrors('Error'); 
                }        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Calendar  $calendar
     * @return \Illuminate\Http\Response
     */
    public function show(Calendar $calendar)
    {
        return response($calendar, Response::HTTP_OK);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Calendar  $calendar
     * @return \Illuminate\Http\Response
     */
    public function edit(Calendar $calendar)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Calendar  $calendar
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        /*$calendar->update($request->all());
        return response()->json([
            'data' => new CalendarResource($calendar),
            'message' => 'Successfully updated event!',
            'status' => Response::HTTP_ACCEPTED
        ]);*/
        if (!$request->ajax()) return redirect('/');
        $rules = [
            'event_name' => 'required|max:100'  
        ];
        $messages = [
            'event_name.max' => 'La longitud maxima del :attribute es 100 caracteres.',
        ];
            $this->validate($request, $rules, $messages);
            try{
                $evento = Calendar::findOrFail($request->id);
                //$input = $request->start_date.$request->start_time;
               // $inputEnd = $request->end_date.$request->end_time;
                $evento->event_name = $request->event_name;
                //$evento->descripcion = $request->descripcion;
                //$evento->start_date = Carbon::parse($input);
                //$evento->end_date = Carbon::parse($inputEnd);
                $evento->start_date = $request->start_date;
                $evento->end_date = $request->end_date;
            //$evento->empresa_id=$request->idempresa;
            $evento->save();
            
            
        } catch (PDOException $e){
            return 'error' + $e;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Calendar  $calendar
     * @return \Illuminate\Http\Response
     */
    public function destroy(Calendar $calendar)
    {
        $calendar->delete();
        return response('Event removed successfully!', Response::HTTP_NO_CONTENT);
    }
}
