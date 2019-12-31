<template>
    <main class="main">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Escritorio</a></li>
        </ol>
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card card-chart">
                                <div class="card-header">
                                    <h4>Ingresos</h4>
                                </div>
                                <div class="card-content">
                                    <div class="ct-chart">
                                        <canvas id="ingresos">
                                        </canvas>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <p>Compras de los últimos meses.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card card-chart">
                                <div class="card-header">
                                    <h4>Ventas</h4>
                                </div>
                                <div class="card-content">
                                    <div class="ct-chart">
                                        <canvas id="ventas">
                                        </canvas>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <p>Ventas de los últimos meses.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</template>

<script>
    export default {
        data(){
            return{
                // varIngreso:null,
                // charIngreso:null,
                // ingresos:[],
                // varTotalIngreso: [],
                // varMesIngreso: [],

                varVenta:null, //almacena el valor del id donde vamos a mostrar el grafico
                charVenta:null, //creará el grafico chart alimentado por valores vinculados por id del objeto canvas
                ventas:[], // que es un arreglo de datos que obtendra el listado de ventas
                numVentas: [], //Almacenamos los datos del total de cada mes
                varMesVenta: [], //Almacena los nombres de los meses que vamos a mostrar en el grafico
                Ruta: {
                    estadistica: '/estadistica'
                }
            }
        },
        methods: {
            // getIngresos(){
            //     let me = this;
            //     var url = '/dashboard';
            //     axios.get(url).then(function(response){
            //         var respuesta = response.data;
            //         me.ingresos = respuesta.ingresos;
            //         //cargamos los datos del chart
            //         me.loadIngresos();
            //     })
            //     .catch(function(error){
            //         console.log(error);
            //     });
            // },
            getVentas(){
                let me = this;
                var url = me.Ruta.estadistica + '/getEVentas';
                axios.get(url).then(function(response){
                    me.ventas = response.data.ventas;
                    //cargamos los datos del chart
                    me.loadVentas();
                })
                .catch(function(error){
                    console.log(error);
                });
            },
            // loadIngresos(){
            //     let me = this;
            //     me.ingresos.map(function(x){
            //         me.varMesIngreso.push(x.mes);
            //         me.varTotalIngreso.push(x.total);
            //     });
            //     me.varIngreso = document.getElementById('ingresos').getContext('2d');

            //     me.charIngreso = new Chart(me.varIngreso, {
            //         type: 'bar',
            //         data: {
            //             labels: me.varMesIngreso,
            //             datasets: [{
            //                 label: 'Ingresos',
            //                 data: me.varTotalIngreso,
            //                 backgroundColor: [
            //                     'rgba(255, 99, 132, 0.2)',
            //                 ],
            //                 borderColor: [
            //                     'rgba(255, 99, 132, 0.2)',
            //                 ],
            //                 borderWidth: 1
            //             }]
            //         },
            //         options: {
            //             scales: {
            //                 yAxes: [{
            //                     ticks: {
            //                         beginAtZero: true
            //                     }
            //                 }]
            //             }
            //         }
            //     });
            // },
            loadVentas(){
                let me = this;
                me.ventas.map(function(x){
                    me.varMesVenta.push(x.mes);
                    me.numVentas.push(x.num_ventas);
                });
                me.varVenta = document.getElementById('ventas').getContext('2d');

                me.charVenta = new Chart(me.varVenta, {
                    type: 'bar',
                    data: {
                        labels: me.varMesVenta,
                        datasets: [{
                            label: 'Ventas',
                            data: me.numVentas,
                            backgroundColor: [
                                'rgba(54, 162, 235, 0.2)',
                            ],
                            borderColor: [
                                'rgba(54, 162, 235, 0.2)',
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero: true
                                }
                            }]
                        }
                    }
                });
            },
            
        },
        mounted(){
                // this.getIngresos();
                this.getVentas();
        }
    }
</script>