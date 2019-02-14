<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use Auth;
use Carbon\Carbon;

class StatisticsController extends Controller
{
    public function get_interval_stats($intervals, $tickets)
    {
        $stats = [
            "$intervals[0]" => 0,
            "$intervals[0]-$intervals[1]" => 0,
            "$intervals[1]-$intervals[2]" => 0,
            "$intervals[2]-$intervals[3]" => 0,
            "$intervals[3]" => 0
        ];

        foreach ($tickets as $ticket) {
            if (is_null($ticket->answered_at) == FALSE) {
                $answered_at = Carbon::createFromFormat('Y-m-d H:i:s', $ticket->answered_at);
                $diff = $answered_at->diffInMinutes($ticket->created_at);
                if ($diff <= $intervals[0]) {
                    $stats["$intervals[0]"] = $stats["$intervals[0]"] + 1;
                }
                if ($diff > $intervals[0] && $diff <= $intervals[1]) {
                    $stats["$intervals[0]-$intervals[1]"] = $stats["$intervals[0]-$intervals[1]"] + 1;
                }
                if ($diff > $intervals[1] && $diff <= $intervals[2]) {
                    $stats["$intervals[1]-$intervals[2]"] = $stats["$intervals[1]-$intervals[2]"] + 1;
                }
                if ($diff > $intervals[2] && $diff <= $intervals[3]) {
                    $stats["$intervals[2]-$intervals[3]"] = $stats["$intervals[2]-$intervals[3]"] + 1;
                }
                if ($diff > $intervals[3]) {
                    $stats["$intervals[3]"] = $stats["$intervals[3]"] + 1;
                }
            }
        }

        return $stats;
    }

    public function get_stats($from, $to)
    {
        $intervals = [7, 15, 30, 60];

        $new = Ticket::whereBetween('created_at', [$from, $to])->count();
        $answered = Ticket::whereBetween('answered_at', [$from, $to])->count();
        $not_answered = $new - $answered;
        $answered_at_all = Ticket::whereNotNull('answered_at')->count();

        $stats = $this->get_interval_stats($intervals, Ticket::whereBetween('answered_at', [$from, $to])->get());

        $statistics = [
            array("key" => "Поступивших с $from по $to", "value" => $new),
            array("key" => "Отвеченных с $from по $to", "value" => $answered),
            array("key" => "Неотвеченных с $from по $to", "value" => $not_answered),
            array("key" => "Отвеченных за все время", "value" => $answered_at_all),
            array("key" => "Отвеченных за $intervals[0] минут", "value" => $stats["$intervals[0]"]),
            array("key" => "Отвеченных за $intervals[0]-$intervals[1] минут", "value" => $stats["$intervals[0]-$intervals[1]"]),
            array("key" => "Отвеченных за $intervals[1]-$intervals[2] минут", "value" => $stats["$intervals[1]-$intervals[2]"]),
            array("key" => "Отвеченных за $intervals[2]-$intervals[3] минут", "value" => $stats["$intervals[2]-$intervals[3]"]),
            array("key" => "Отвеченных через $intervals[3] минут", "value" => $stats["$intervals[3]"]),
        ];

        return $statistics;
    }

    public function index(Request $request)
    {
        $from = date('Y-m-d', strtotime("-1 years"));
        $to = date('Y-m-d', strtotime("+1 years"));

        $statistics = $this->get_stats($from, $to);

        return view('statistics', ['statistics' => $statistics]);
    }

    public function show(Request $request)
    {
        $attributes = $request->validate([
            'from' => 'required',
            'to' => 'required'
        ]);

        $from = date('Y-m-d', strtotime($attributes['from']));
        $to = date('Y-m-d', strtotime($attributes['to']));

        $statistics = $this->get_stats($from, $to);

        $request->flash();

        return view('statistics', ['statistics' => $statistics]);
    }
}
