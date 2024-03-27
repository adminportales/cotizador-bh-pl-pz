<?php

namespace App\Http\Livewire\Admin;

use App\Models\Quote;
use App\Models\User;
use Carbon\Carbon;
use Livewire\Component;

/**
 * Clase DashboardComponent
 *
 * Esta clase representa el componente de panel de control del administrador.
 * Contiene métodos para obtener y procesar datos relacionados con las cotizaciones y los usuarios.
 */
class DashboardComponent extends Component
{
    /**
     * @var string|null $company
     * El nombre de la compañía seleccionada.
     */
    public $company;

    /**
     * @var string $start
     * La fecha de inicio para filtrar las cotizaciones.
     */
    public $start;

    /**
     * @var string $end
     * La fecha de fin para filtrar las cotizaciones.
     */
    public $end;

    /**
     * @var array $dataQuoteCompany
     * Los datos de cotizaciones por compañía.
     */
    public $dataQuoteCompany;

    /**
     * @var array $dataUserInfoTickets
     * Los datos de información de usuarios y cotizaciones.
     */
    public $dataUserInfoTickets;

    /**
     * @var array $dataUserMountTickets
     * Los datos de monto total de cotizaciones por usuario.
     */
    public $dataUserMountTickets;

    /**
     * @var array $dates
     * Las fechas de inicio y fin para filtrar las cotizaciones.
     */
    public $dates;

    /**
     * El método mount se ejecuta al inicializar el componente.
     * Establece las fechas de inicio y fin por defecto.
     */
    public function mount()
    {
        $this->end = Carbon::now()->format("Y-m-d");
        $this->start = Carbon::now()->subDays(5)->format("Y-m-d");
    }

    /**
     * El método render se encarga de obtener y procesar los datos necesarios para mostrar en la vista del panel de control.
     * Retorna la vista del panel de control.
     */
    public function render()
    {
        // Obtener fechas formateadas
        $start = date($this->start);
        $end = date($this->end);
        $this->dates = [$start, $end];

        // Obtener cotizaciones por compañía
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

        // Procesar cotizaciones por compañía
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

        // Obtener información de usuarios y cotizaciones
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

            if ($leadsCreated > 0) {
                array_push($dataUserCreatedLeads, $userCount->name);
                array_push($dataUserCountLeads, $leadsCreated);
            } else {
                if ($userCount->visible && $userCount->company_session == $this->company)
                    array_push($dataUserWithoutLeads, [$userCount->name . ' ' . $userCount->lastname, $userCount->last_login]);
            }
        }
        $this->dataUserInfoTickets = [$dataUserCreatedLeads, $dataUserCountLeads, $dataUserWithoutLeads];

        // Obtener monto total de cotizaciones por usuario
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
                    $mount = $mount + ($quote->latestQuotesUpdate ? $quote->latestQuotesUpdate->quoteProducts->sum('precio_total') : 0);
                }
                array_push($dataUserMountLeads, $userCount->name);
                array_push($dataUserCountLeads, $mount);
            }
        }
        $this->dataUserMountTickets = [$dataUserMountLeads, $dataUserCountLeads];

        // Disparar evento de actualización de datos
        $this->dispatchBrowserEvent('refreshData', [
            'dataQuoteCompany' => $this->dataQuoteCompany,
            'dataUserInfoTickets' => $this->dataUserInfoTickets,
            'dataUserMountTickets' => $this->dataUserMountTickets
        ]);

        // Retornar vista del panel de control
        return view('admin.dashboard.dashboard-component');
    }
}
