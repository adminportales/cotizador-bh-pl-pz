@extends('layouts.app')
@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <section class="row">
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
                    {{--  --}}
                </section>
            </div>

        </div>
    </div>
@endsection


@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
    <script>
        // Chart Type
        const ctxType = 'myChart';
        const dataType = {
            labels: [
                'PROMO LIFE',
                'PROMO ZALE',
                'BH TRADEMARKET'
            ],
            datasets: [{
                label: 'Tipos de Tickets Creados',
                data: @json($dataQuoteCompany),
                backgroundColor: [
                    'rgb(255, 99, 132)',
                    'rgb(54, 162, 235)',
                    'rgb(255, 205, 86)'
                ],
                hoverOffset: 4
            }]
        };
        const myChartType = new Chart(ctxType, {
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
        const labels = @json($dataUserInfoTickets[0]);
        const data = {
            labels: labels,
            datasets: [{
                label: 'Total de Tickets',
                data: @json($dataUserInfoTickets[1]),
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
        const myChart = new Chart(ctx, {
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
        const labelsMount = @json($dataUserMountTickets[0]);
        const dataMount = {
            labels: labelsMount,
            datasets: [{
                label: 'Total de Tickets',
                data: @json($dataUserMountTickets[1]),
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
        const myChartMount = new Chart(ctxMount, {
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
@endsection
