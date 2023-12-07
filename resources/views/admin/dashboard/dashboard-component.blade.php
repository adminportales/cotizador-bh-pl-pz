<div>
    <div class="card-body">
        <section class="row">
            <div class="col-6">
                <label for="">Filtrar por empresa</label>
                <select name="company" class="form-control" wire:model='company'>
                    <option value="">Seleccione empresa...</option>
                    <option value="1">Promo Life</option>
                    <option value="2">BH TradeMarket</option>
                    <option value="3">Promo Zale</option>
                </select>
            </div>
            <div class="col-6">
                <div class="d-flex">
                    <div class="w-50">
                        <label for="">Fecha Inicial</label>
                        <input type="date" class="form-control" wire:model='start'>
                    </div>
                    <div class="w-50">
                        <label for="">Fecha Final</label>
                        <input type="date" class="form-control" wire:model='end'>
                    </div>
                </div>
            </div>
        </section>
        <br>
        <div class="dashboard-content">
            <section class="row" wire:loading.class="opacity">
                <div class="col-md-4">
                    <p class="text-center">Numero de Leads Por Empresa</p>
                    <canvas id="myChart" width="400" height="400"></canvas>
                </div>
                <div class="col-md-8">
                    <p class="text-center">Usuarios/Total de Leads</p>
                    <canvas id="myChartBarCreated" width="400" height="200"></canvas>
                </div>
                <div class="w-100">
                    <br>
                </div>
                <div class="col-md-8">
                    <p class="text-center">Usuarios/Monto Total</p>
                    <canvas id="myChartBarMoney" width="400" height="200"></canvas>
                </div>
                <div class="col-md-4">
                    <p class="text-center">Usuarios que no han creado un lead</p>
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nombre</th>
                                <th>Last Login</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($dataUserInfoTickets[2] as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ Str::limit($item[0], 25, '...') }}</td>
                                    <td>{{ $item[1] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>
            <div wire:loading.flex>
                <div class="spinnerloading w-100 d-flex justify-content-center">
                    <div>
                        <div role="status">
                            <svg aria-hidden="true"
                                class="inline w-8 h-8 text-gray-200 animate-spin dark:text-gray-600 fill-blue-600"
                                viewBox="0 0 100 101" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z"
                                    fill="currentColor" />
                                <path
                                    d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z"
                                    fill="currentFill" />
                            </svg>
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <style>
        .spinnerloading {
            position: absolute;
            top: 10px;
        }

        .dashboard-content {
            position: relative;
        }

        .opacity {
            opacity: .5;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
    <script>
        let dataQuoteCompany = @json($dataQuoteCompany);
        let dataUserInfoTicketsNames = @json($dataUserInfoTickets[0]);
        let dataUserInfoTicketsTotal = @json($dataUserInfoTickets[1]);
        let dataUserMountTicketsNames = @json($dataUserMountTickets[0]);
        let dataUserMountTicketsTotal = @json($dataUserMountTickets[1]);

        let myChartType, myChart, myChartMount;
        window.addEventListener('refreshData', event => {
            dataQuoteCompany = event.detail.dataQuoteCompany;
            dataUserInfoTicketsNames = event.detail.dataUserInfoTickets[0];
            dataUserInfoTicketsTotal = event.detail.dataUserInfoTickets[1];
            dataUserMountTicketsNames = event.detail.dataUserMountTickets[0];
            dataUserMountTicketsTotal = event.detail.dataUserMountTickets[1];
            crearGraficas()
        })

        function crearGraficas() {
            // Chart Type
            if (myChartType) {
                myChartType.destroy();
            }
            if (myChart) {
                myChart.destroy();
            }
            if (myChartMount) {
                myChartMount.destroy();
            }
            const ctxType = 'myChart';
            const dataType = {
                labels: [
                    'PROMO LIFE',
                    'PROMO ZALE',
                    'BH TRADEMARKET'
                ],
                datasets: [{
                    label: 'Tipos de Tickets Creados',
                    data: dataQuoteCompany,
                    backgroundColor: [
                        'rgb(2, 158, 250)',
                        'rgb(44,240, 190)',
                        'rgb(9, 67, 227)',
                    ],
                    hoverOffset: 4
                }]
            };
            myChartType = new Chart(ctxType, {
                type: 'pie',
                data: dataType,
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // Chart Creadores
            const ctx = 'myChartBarCreated';
            // $dataUserInfoTickets = [$dataUserCreatedTickets, $dataUserCountTickets];
            const labels = dataUserInfoTicketsNames;
            const data = {
                labels: labels,
                datasets: [{
                    label: 'Total de Leads',
                    data: dataUserInfoTicketsTotal,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(255, 159, 64, 0.2)',
                        'rgba(255, 205, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(201, 203, 207, 0.2)'
                    ],
                    borderColor: [
                        'rgb(255, 99, 132)',
                        'rgb(255, 159, 64)',
                        'rgb(255, 205, 86)',
                        'rgb(75, 192, 192)',
                        'rgb(54, 162, 235)',
                        'rgb(153, 102, 255)',
                        'rgb(201, 203, 207)'
                    ],
                    borderWidth: 1
                }]
            };
            myChart = new Chart(ctx, {
                type: 'bar',
                data: data,
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                },
            });
            const ctxMount = 'myChartBarMoney';
            // $dataUserInfoTickets = [$dataUserCreatedTickets, $dataUserCountTickets];
            const labelsMount = dataUserMountTicketsNames;
            const dataMount = {
                labels: labelsMount,
                datasets: [{
                    label: 'Monto Total',
                    data: dataUserMountTicketsTotal,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(255, 159, 64, 0.2)',
                        'rgba(255, 205, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(201, 203, 207, 0.2)'
                    ],
                    borderColor: [
                        'rgb(255, 99, 132)',
                        'rgb(255, 159, 64)',
                        'rgb(255, 205, 86)',
                        'rgb(75, 192, 192)',
                        'rgb(54, 162, 235)',
                        'rgb(153, 102, 255)',
                        'rgb(201, 203, 207)'
                    ],
                    borderWidth: 1
                }]
            };
            myChartMount = new Chart(ctxMount, {
                type: 'bar',
                data: dataMount,
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                },
            });
        }
        crearGraficas()
        /*
        // Chart Type
        const ctxDesigner = 'chartDesigner';
        const dataDesigner = {
            labels: []
            datasets: [{
                label: 'Tipos de Tickets Creados',
                data: [],
                backgroundColor: [
                    'rgb(255, 99, 132)',
                    'rgb(54, 162, 235)',
                    'rgb(255, 205, 86)'
                ],
                hoverOffset: 4
            }]
        };
        const myChartDesigner = new Chart(ctxDesigner, {
            type: 'pie',
            data: dataDesigner,
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });


        // Chart Type
        const ctxStatus = 'chartStatus';
        const dataStatus = {
            labels: [],
            datasets: [{
                label: 'Tipos de Tickets Creados',
                data: [],
                backgroundColor: [
                    'rgb(155, 99, 232)',
                    'rgb(4, 162, 23)',
                    'rgb(54, 12, 235)',
                    'rgb(255, 25, 86)',
                    'rgb(255, 205, 86)'
                ],
                hoverOffset: 4
            }]
        };
        const myChartStatus = new Chart(ctxStatus, {
            type: 'pie',
            data: dataStatus,
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });*/
    </script>
</div>
