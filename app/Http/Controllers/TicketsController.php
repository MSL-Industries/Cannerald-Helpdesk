<?php

namespace App\Http\Controllers;

use App\Repositories\TicketsIndexQuery;
use App\Repositories\TicketsRepository;
use App\Ticket;
use BadChoice\Thrust\Controllers\ThrustController;
use GuzzleHttp\Client;

class TicketsController extends Controller
{
    public function index()
    {
        return (new ThrustController)->index('tickets');
    }

    /*public function index(TicketsRepository $repository)
    {
        $ticketsQuery = TicketsIndexQuery::get($repository);
        $ticketsQuery = $ticketsQuery->select('tickets.*')->latest('updated_at');

        return view('tickets.index', ['tickets' => $ticketsQuery->paginate(25, ['tickets.user_id'])]);
    }*/

    public function show(Ticket $ticket)
    {
        $this->authorize('view', $ticket);

        try {
            $client = new Client();

            $options = [
                'form_params' => [
                    'api_key' => env('API_TOKEN'),
                    'email' => $ticket->getSubscribableEmail()
                ]
            ];

            $options['headers'] = [
                'Accept' => 'application/json',
                'Content-Type' => 'application/x-www-form-urlencoded'
            ];

            $response = $client->request('post', env('APP_ENDPOINT') . 'api/support/user-info', $options);


            $user = json_decode($response->getBody());
        } catch (\Exception $e) {
            $user = null;
        }

        return view('tickets.show', ['ticket' => $ticket, 'user' => $user]);
    }

    public function create()
    {
        return view('tickets.create');
    }

    public function store()
    {
        $this->validate(request(), [
            'requester' => 'required|array',
            'title' => 'required|min:3',
            'body' => 'required',
            'team_id' => 'nullable|exists:teams,id',
        ]);
        $ticket = Ticket::createAndNotify(request('requester'), request('title'), request('body'), request('tags'));
        $ticket->updateStatus(request('status'));

        if (request('team_id')) {
            $ticket->assignToTeam(request('team_id'));
        }

        return redirect()->route('tickets.show', $ticket);
    }

    public function reopen(Ticket $ticket)
    {
        $ticket->updateStatus(Ticket::STATUS_OPEN);

        return back();
    }

    public function update(Ticket $ticket)
    {
        $this->validate(request(), [
            'requester' => 'required|array',
            'priority' => 'required|integer',
            'type' => 'integer',
            //'subject'   => 'string|nullable',
            //'summary'   => 'string'
            //'title'      => 'required|min:3',
        ]);
        $ticket->updateWith(request('requester'), request('priority'), request('type'))
            ->updateSummary(request('subject'), request('summary'));

        return back();
    }
}
