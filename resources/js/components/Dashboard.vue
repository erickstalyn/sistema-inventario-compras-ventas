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
                        <div class="col-md-12">
                            <div class="card card-chart">
                                <div class="card-header">
                                    <h4>Número de ventas mensuales</h4>
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
            getEstadistica(numero){
                let me = this;
                let url;
                switch (numero) {
                    case 1:
                        url = me.Ruta.estadistica + '/getEVentas';
                        axios.get(url).then(function(response){
                            me.ventas = response.data.ventas;
                            //cargamos los datos del chart
                            me.mostrar(1);
                        })
                        .catch(function(error){
                            console.log(error);
                        });
                        break;
                }
            },
            mostrar(numero){
                let me = this;
                switch (numero) {
                    case 1:
                        me.ventas.map(function(x){
                            me.varMesVenta.push(me.fix(1, x.mes));
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
                        break;
                }
            },
            fix(numero, data = ''){
                let fixed = '';
                switch (numero) {
                    case 1:
                        let meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Setiembre', 'Octubre', 'Noviembre', 'Diciembre'];
                        fixed = meses[data-1];
                        break;
                }
                return fixed;
            }
            
        },
        mounted(){
                // this.getIngresos();
                this.getEstadistica(1);
        }
    }
</script>