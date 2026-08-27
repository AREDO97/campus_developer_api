<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;

class EventsController extends Controller
{
    // create event
    public function create(Request $request)
    {
        $request->validate([
            'title'=>'required',
            'description'=>'max:100',
            'image'=>'required|image',
            'date'=>'date'
        ]);
            $path=null;
        if($request->hasFile('image')){
            $path=$request->file('image')->store('images','public');
        }
        $event=Event::create([
            'title'=>$request->title,
            'user_id'=>auth()->id(),
            'image'=>$path,
            'description'=>$request->description,
            'date'=>$request->date
        ]);
        // response
        return response()->json([
            'message'=>'Event created successifully',
            'event'=>$event
        ]);
    }
    // update events
    public function update(Request $request, Event $event)
    {
         $request->validate([
            'title'=>'max:100',
            'description'=>'max:100',
            'image'=>'image',
            'date'=>'date'
        ]);

        // image path
           $path=null;
        if($request->hasFile('image')){
            $path=$request->file('image')->store('images','public');
        }
        // update event
         $event->update([
            'title'=>$request->title ?? $event->title,
            'image'=>$path ?? $event->image,
            'description'=>$request->description ?? $event->description,
            'date'=>$request->date ?? $event->date,
        ]);

         return response()->json([
            'message'=>'Event updated successifully',
            'event'=>$event
        ]);
    }
    // delete events
    public function destroy(Event $event)
    {
        $event->update([
            'status'=>'deleted'
        ]);
        // response 
        return response()->json([
            'message'=>'Event deleted successifully',
            'event'=>$event
        ]);
    }
// all upcoming events
public function index()
{
    $events=Event::where('status','upcoming')->latest()->paginate(10);
    // response
    return response()->json($events);
}
}
