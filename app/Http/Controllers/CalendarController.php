<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event; // Assuming you have an Event model

class CalendarController extends Controller
{
  public function index()
  {
    $events = Event::all(['event_id', 'event_title', 'event_date']); // customize fields as needed
    return view('admin.calendar.calendar', compact('events'));
  }

  public function store(Request $request)
  {
    $request->validate([
      'event_title' => 'required|string',
      'start' => 'required|date',
      'end' => 'required|date|after_or_equal:start',
      'venue' => 'required|string',
    ]);

    Event::create($request->all());

    return response()->json(['success' => true]);
  }
}
