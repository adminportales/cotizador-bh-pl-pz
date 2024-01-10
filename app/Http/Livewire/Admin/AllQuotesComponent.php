<?php

namespace App\Http\Livewire\Admin;

use App\Models\Quote;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * Clase AllQuotesComponent
 *
 * Esta clase representa el componente de Livewire para mostrar todas las cotizaciones.
 * Contiene la lógica para filtrar y ordenar las cotizaciones según una palabra clave.
 */
class AllQuotesComponent extends Component
{
    use WithPagination;

    public $selected_id, $keyWord, $user_id, $client_odoo_id, $name, $contact, $email, $phone;
    public function render()
    {
        \DB::statement("SET SQL_MODE=''");
        // `quote_information` ON `quote_information`.`id`=`quote_updates`.`quote_information_id` GROUP BY `quote_updates`.`quote_id` ORDER BY `quote_information`.`created_at` ASC;
        $quotes = Quote::join('quote_updates', 'quote_updates.quote_id', 'quotes.id')
            ->join('quote_information', 'quote_updates.quote_information_id', 'quote_information.id')
            ->join('users', 'users.id', 'quotes.user_id')
            ->where(function ($query) {
                $query->orWhere("quote_information.company", "LIKE", '%' . $this->keyWord . '%')
                    ->orWhere("quote_information.name", "LIKE", '%' . $this->keyWord . '%')
                    ->orWhere("quote_information.oportunity", "LIKE", '%' . $this->keyWord  . '%')
                    ->orWhere("quotes.id", "LIKE", '%' . str_replace('QS', '', str_replace('qs', '', $this->keyWord)) . '%')
                    ->orWhere("quotes.lead", "LIKE", '%' . $this->keyWord . '%')
                    ->orWhere("users.name", "LIKE", '%' . $this->keyWord . '%');
            })->select('quotes.*')
            ->groupBy('quotes.id')
            ->orderBy('quote_information.created_at', 'DESC')
            ->simplePaginate(30);
        return view('admin.cotizaciones.all-quotes-component', ['quotes' => $quotes]);
    }
}
