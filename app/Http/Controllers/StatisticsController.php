<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Models\Ticket;

class StatisticsController extends Controller
{
    public function index(Request $request)
    {
        $intervals = [0, 7, 15, 30, 60];
        $from = date('d.m.Y', strtotime("-1 years"));
        $to = date('d.m.Y', strtotime("+1 years"));
        $statistics = [
            array("key" => "Поступивших с $from по $to", "value" => Ticket::whereBetween('created_at', array($from, $to))->count()),
            array("key" => "Отвеченных с $from по $to", "value" => 0),
            array("key" => "НЕотвеченных с $from по $to", "value" => 0),
            array("key" => "НЕотвеченных за ночную смену", "value" => 0),
            array("key" => "Отвеченных в Контроле Качества", "value" => 0),
            array("key" => "Отвеченных за все время", "value" => 0),
            array("key" => "Отвеченных за " . $intervals[1] . " минут", "value" => 0),
            array("key" => "Отвеченных за " . $intervals[1] . "-" . $intervals[2] . " минут", "value" => 0),
            array("key" => "Отвеченных за " . $intervals[2] . "-" . $intervals[3] . " минут", "value" => 0),
            array("key" => "Отвеченных за " . $intervals[3] . "-" . $intervals[4] . " минут", "value" => 0),
            array("key" => "Отвеченных через час", "value" => 0),
        ];
        return view('statistics', ['statistics' => $statistics]);
    }
    public function show(Request $request)
    {
        $request->validate([
            'from' => 'required',
            'to' => 'required'
        ]);
        $intervals = [0, 7, 15, 30, 60];
        $from = request("from");
        $from = date("d.m.Y", strtotime($from));

        $to = request('to');
        $to = date("d.m.Y", strtotime($to));
        $statistics = [
            array("key" => "Поступивших с $from по $to", "value" => Ticket::whereBetween('created_at', array($from, $to))->count()),
            array("key" => "Отвеченных с $from по $to", "value" => Ticket::whereBetween('created_at', array($from, $to))->where('ticket_status', '>=', 4)->count()),
            array("key" => "НЕотвеченных с $from по $to", "value" => Ticket::whereBetween('created_at', array($from, $to))->where('ticket_status', '<', 4)->count()),
            array("key" => "НЕотвеченных за ночную смену", "value" => 0),
            array("key" => "Отвеченных в Контроле Качества", "value" => 0),
            array("key" => "Отвеченных за все время", "value" => Ticket::where('ticket_status','<=',4)->count()),
            //array("key" => "Отвеченных за " . $intervals[1] . " минут", "value" => Ticket::where('answered_time' - 'created_at' <= 7)->count()),
           // array("key" => "Отвеченных за " . $intervals[1] . "-" . $intervals[2] . " минут", "value" => Ticket::whereBetween("answer_time", array("created_at",date(("Y-m-d H:i:s"),strtotime("+7 minutes",strtotime("created_at")))))->where("ticket_status", ">=", 4)->count()),
            array("key" => "Отвеченных за " . $intervals[2] . "-" . $intervals[3] . " минут", "value" => 0),
            array("key" => "Отвеченных за " . $intervals[3] . "-" . $intervals[4] . " минут", "value" => 0),
            array("key" => "Отвеченных через час", "value" => 0)
        ];
        return view('statistics', ['statistics' => $statistics]);
    }

}
