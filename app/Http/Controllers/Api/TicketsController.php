<?php

namespace App\Http\Controllers\Api;

use App\Notifications\TicketCreated;
use App\Requester;
use App\Settings;
use App\Ticket;
use App\TicketType;
use Illuminate\Http\Response;

class TicketsController extends ApiController
{
    public function index()
    {
        $requester = Requester::whereName(request('requester'))->orWhere('email', '=', request('requester'))->firstOrFail();
        if (request('status') == 'solved') {
            $tickets = $requester->solvedTickets;
        } elseif (request('status') == 'closed') {
            $tickets = $requester->closedTickets;
        } else {
            $tickets = $requester->openTickets;
        }

        return $this->respond($tickets);
    }

    public function show(Ticket $ticket)
    {
        return $this->respond($ticket->load('requester', 'comments'));
    }

    public function store()
    {
        $this->validate(request(), [
            'requester' => 'required|array',
            'title'     => 'required|min:3',
        ]);

        /** @var Ticket $ticket */
        $ticket = Ticket::createAndNotify(
            request('requester'),
            strip_tags(request('title')),
            strip_tags(request('body')),
            request('tags')
        );

        //move to VIP if in list
        if (in_array($ticket->requester->email, explode(',',env('VIP', '')))) {
            $type = TicketType::where('name', 'VIP')->first();
        } else {
            $type = TicketType::whereIn('name', request('tags'))->first();
        }

        if ($type) {
            $ticket->type()->associate($type);
        } else {
            $ticket->type()->associate(TicketType::where('name', 'unassigned')->first());
        }

        $ticket->save();

        if (request('team_id')) {
            $ticket->assignToTeam(request('team_id'));
        } else {
            $this->notifyDefault($ticket);
        }

        return $this->respond(['id' => $ticket->id], Response::HTTP_CREATED);
    }

    public function update(Ticket $ticket)
    {
        $ticket->updateStatus(request('status'));

        return $this->respond(['id' => $ticket->id], Response::HTTP_OK);
    }

    private function notifyDefault($ticket)
    {
        $setting = Settings::first();
        if ($setting && $setting->slack_webhook_url) {
            $setting->notify(new TicketCreated($ticket));
        }
    }
}
