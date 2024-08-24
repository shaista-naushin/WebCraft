@extends('layout-backend.master')

@section('header_menu')
    <h5 class="p-0 m-0">Dashboard</h5>
@stop

@section('styles')
    <style>
        .chart-six {
            margin-left: -5px;
            height: 150px;
        }

        @media (min-width: 576px) {
            .chart-six {
                height: 200px;
            }
        }

        @media (min-width: 768px) {
            .chart-six {
                height: 250px;
            }
        }

        @media (min-width: 992px) {
            .chart-six {
                height: 300px;
            }
        }
    </style>
@endsection
@section('content')
    <div>
        <h1 class="h3 mb-4 text-gray-800">Dashboard</h1>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h5 class="m-0 font-weight-bold text-primary">Analytics</h5>
            </div>
            <div class="card-body">
                <div class="row row-xs">
                    <div class="col-sm-6 col-lg-3">
                        <div class="card card-body">
                            <h6>Total Pages</h6>
                            <div class="d-flex d-lg-block d-xl-flex align-items-end">
                                <h3>{{$totalPages}}</h3>
                                <p><span>Total </span></p>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6 col-lg-3">
                        <div class="card card-body">
                            <h6>Pages This Week</h6>
                            <div class="d-flex d-lg-block d-xl-flex align-items-end">
                                <h3>{{$pagesThisWeek}}</h3>
                                <p><span>{{$pagesWeekChange}}%</span></p>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6 col-lg-3">
                        <div class="card card-body">
                            <h6>Form Data</h6>
                            <div class="d-flex d-lg-block d-xl-flex align-items-end">
                                <h3>{{$totalFormData}}</h3>
                                <p><span>Total </span></p>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6 col-lg-3">
                        <div class="card card-body">
                            <h6>Form Data This Week</h6>
                            <div class="d-flex d-lg-block d-xl-flex align-items-end">
                                <h3>{{$formDataThisWeek}}</h3>
                                <p><span>{{$formDataWeekChange}}% </span></p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-12">
                        <div class="chart-six">
                            <canvas id="chartBar1"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    @json($pagesChart)
    <script src="/lib/chart.js/Chart.bundle.min.js"></script>
    <script type="text/javascript">
        $(function () {
            'use strict'

            let ctx1 = document.getElementById('chartBar1').getContext('2d');
            let ctxChart1 = new Chart(ctx1, {
                type: 'bar',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                    datasets: [{
                        label: 'Pages',
                        data: @json($pagesChart),
                        backgroundColor: '#66a4fb'
                    }, {
                        label: 'Form Data',
                        data: @json($formDataChart),
                        backgroundColor: '#65e0e0'
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    legend: {
                        display: false,
                        labels: {
                            display: false
                        }
                    },
                    scales: {
                        xAxes: [{
                            display: false
                        }],
                        yAxes: [{
                            gridLines: {
                                color: '#ebeef3'
                            },
                            ticks: {
                                fontColor: '#8392a5',
                                fontSize: 10,
                                beginAtZero: true,
                                userCallback: function(label, index, labels) {
                                    // when the floored value is the same as the value we have a whole number
                                    if (Math.floor(label) === label) {
                                        return label;
                                    }
                                },
                                max: {{max([max($formDataChart), max($pagesChart)]) * 2}}
                            }
                        }]
                    }
                }
            });
        });
    </script>
@endsection
