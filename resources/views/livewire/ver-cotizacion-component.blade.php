<div class="row">
    <div class="col-md-5">
        <div class="card w-100 h-100">
            <div class="card-body">
                @if ($quote->latestQuotesUpdate)
                    <div class="d-flex justify-content-between">
                        <h5 class="card-title">{{ $quote->latestQuotesUpdate->quotesInformation->name }} |
                            {{ $quote->latestQuotesUpdate->quotesInformation->company }}</h5>
                        <div style="width: 20px; cursor: pointer;" data-toggle="modal" data-target="#editarInfoCliente">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path
                                    d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
                                <path fill-rule="evenodd"
                                    d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>
                    <h6><b>Numero de lead: </b> {{ $quote->lead }}</h6>
                    <h6><b>Oportunidad: </b> {{ $quote->latestQuotesUpdate->quotesInformation->oportunity }}</h6>
                    <br>
                    <p><b>Email: </b>{{ $quote->latestQuotesUpdate->quotesInformation->email }}</p>
                    <p><b>Telefono: </b>{{ $quote->latestQuotesUpdate->quotesInformation->landline }}</p>
                    <p><b>Celular: </b>{{ $quote->latestQuotesUpdate->quotesInformation->cell_phone }}</p>
                    <p><b>Rank: </b>{{ $quote->latestQuotesUpdate->quotesInformation->rank }}</p>
                    <p><b>Departamento: </b>{{ $quote->latestQuotesUpdate->quotesInformation->department }}</p>
                    <p><b>Informacion adicional: </b>{{ $quote->latestQuotesUpdate->quotesInformation->information }}
                    </p>
                @else
                    <p>Sin informacion</p>
                @endif

                @if ($quote->latestQuotesUpdate)
                    <!-- Modal -->
                    <div class="modal fade" id="editarInfoCliente" tabindex="-1"
                        aria-labelledby="editarInfoClienteLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editarInfoClienteLabel">Editar Informacion</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    @livewire('edit-information-client-component', ['quoteInfo' => $quote->latestQuotesUpdate->quotesInformation, 'quote' => $quote])
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-7">
        <div class="card w-100 h-100">
            @livewire('editar-cotizacion-component', ['quote' => $quote], key($quote->id))
            @if ($quote->latestQuotesUpdate)
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <a class="btn btn-success w-100" target="_blank"
                                href="{{ route('previsualizar.cotizacion', ['quote' => $quote]) }}">Ver PDF</a>
                        </div>
                        <div class="col-md-4">
                            <button class="btn btn-primary w-100" wire:click="enviar">Enviar al Cliente</button>
                        </div>
                        <div class="col-md-4">
                            <button class="btn btn-danger w-100" wire:click="enviar">Enviar a ODOO</button>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
    <script>
        window.addEventListener('showModalInfoClient', event => {
            $('#editarInfoCliente').modal('hide')
        })
    </script>
</div>
