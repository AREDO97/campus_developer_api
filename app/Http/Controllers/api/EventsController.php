<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\AuditLog;
use App\Models\User;
use App\Notifications\eventCreation;
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


  // notify all users
  $users=User::where('id','!=',auth()->id())->get();
  foreach($users as $user){
    $user->notify(
        new eventCreation($user->name,$event->title)
    );
  }
  // Log out event
    AuditLog::Log(
        $request->user()->id,
        'Event Creation',
        $request->user()->name . ' created a new event titled ' . $request->title
    );


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
// Log out event
    AuditLog::Log(
        $request->user()->id,
        'Event Update',
        $request->user()->name . ' updated an event titled ' . $request->title
    );
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

// log out event
        AuditLog::Log(
            auth()->user()->id,
            'Event Deletion',
            auth()->user()->name.' deleted an event titled '.$event->title
        );
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
