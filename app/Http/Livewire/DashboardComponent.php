<?php

namespace App\Http\Livewire;

use App\Models\Quote;
use App\Models\User;
use Livewire\Component;

class DashboardComponent extends Component
{
    public $company, $start, $end;
    public $dataQuoteCompany, $dataUserInfoTickets, $dataUserMountTickets, $dates;
    public function render()
    {
        $start = date($this->start);
        $end = date($this->end);
        $this->dates = [$start, $end];
        // dd($start, $end);
        $company = $this->company;
        $quotes = Quote::when($company !== '' && $company !== null, function ($query, $company) {
            $query->where('company_id', '=', $this->company);
        })
            ->when($start !== '' || $end !== '', function ($query, $dates) {
                if ($this->dates[0] == '')
                    $this->dates[0] = now();
                if ($this->dates[1] == '')
                    $this->dates[1] = now();
                $query->whereBetween('created_at', [$this->dates[0], $this->dates[1]]);
            })
            ->get();
        $pl = 0;
        $bh = 0;
        $pz = 0;
        foreach ($quotes as $quote) {
            $quote->company;
            switch ($quote->company->name) {
                case 'PROMO LIFE':
                    $pl++;
                    break;
                case 'PROMO ZALE':
                    $pz++;
                    break;
                case 'BH TRADEMARKET':
                    $bh++;
                    break;
                default:
                    break;
            }
        }
        $this->dataQuoteCompany  = [$pl, $pz, $bh];

        // Vendedores
        $dataUserCreatedLeads = [];
        $dataUserCountLeads = [];
        $dataUserWithoutLeads = [];
        $users = User::all();
        foreach ($users as $userCount) {
            $leadsCreated = $userCount->quotes()->when($company !== '' && $company !== null, function ($query, $company) {
                $query->where('company_id', '=', $this->company);
            })
                ->when($start !== '' || $end !== '', function ($query, $dates) {
                    if ($this->dates[0] == '')
                        $this->dates[0] = now();
                    if ($this->dates[1] == '')
                        $this->dates[1] = now();
                    $query->whereBetween('created_at', [$this->dates[0], $this->dates[1]]);
                })->count();

            // ->where('created_at', '>', now()->subMonth())
            if ($leadsCreated > 0) {
                array_push($dataUserCreatedLeads, $userCount->name);
                array_push($dataUserCountLeads, $leadsCreated);
            } else {
                if ($userCount->visible && $userCount->company_session == $this->company)
                    array_push($dataUserWithoutLeads, [$userCount->name . ' ' . $userCount->lastname, $userCount->last_login]);
            }
        }
        $this->dataUserInfoTickets = [$dataUserCreatedLeads, $dataUserCountLeads, $dataUserWithoutLeads];

        // Vendedores
        $dataUserMountLeads = [];
        $dataUserCountLeads = [];
        $users = User::all();
        foreach ($users as $userCount) {
            $leadsCreated = $userCount->quotes()->when($company !== '' && $company !== null, function ($query, $company) {
                $query->where('company_id', '=', $this->company);
            })
                ->when($start !== '' || $end !== '', function ($query, $dates) {
                    if ($this->dates[0] == '')
                        $this->dates[0] = now();
                    if ($this->dates[1] == '')
                        $this->dates[1] = now();
                    $query->whereBetween('created_at', [$this->dates[0], $this->dates[1]]);
                })->count();

            if ($leadsCreated > 0) {
                $mount = 0;
                $leadsByUser = $userCount->quotes()->when($company !== '' && $company !== null, function ($query, $company) {
                    $query->where('company_id', '=', $this->company);
                })
                    ->when($start !== '' || $end !== '', function ($query, $dates) {
                        if ($this->dates[0] == '')
                            $this->dates[0] = now();
                        if ($this->dates[1] == '')
                            $this->dates[1] = now();
                        $query->whereBetween('created_at', [$this->dates[0], $this->dates[1]]);
                    })->get();
                foreach ($leadsByUser as $quote) {
                    $mount = $mount + $quote->latestQuotesUpdate->quoteProducts->sum('precio_total');
                }
                array_push($dataUserMountLeads, $userCount->name);
                array_push($dataUserCountLeads, $mount);
            }
        }
        $this->dataUserMountTickets = [$dataUserMountLeads, $dataUserCountLeads];

        $this->dispatchBrowserEvent('refreshData', [
            'dataQuoteCompany' => $this->dataQuoteCompany,
            'dataUserInfoTickets' => $this->dataUserInfoTickets,
            'dataUserMountTickets' => $this->dataUserMountTickets
        ]);

        return view('livewire.dashboard-component');
    }
}
