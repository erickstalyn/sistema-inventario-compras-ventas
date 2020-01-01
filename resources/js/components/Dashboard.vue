<template>
    <main class="main">
        <!-- <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Escritorio</a></li>
        </ol> -->
        <!-- <div class="container-fluid"> -->
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
                                        <canvas id="estadistica">
                                        </canvas>
                                    </div>
                                </div>
                                <!-- <div class="card-footer">
                                    <p>Ventas de los últimos meses.</p>
                                </div> -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <!-- </div> -->
    </main>
</template>

<script>
    export default {
        props: ['num_mostrar'],
        data(){
            return{
                lugarMuestra: null,
                charVenta:null, //creará el grafico chart alimentado por valores vinculados por id del objeto canvas
                ventas:[], // que es un arreglo de datos que obtendra el listado de ventas
                // numMostrar: this.num_mostrar,
                Ruta: {
                    estadistica: '/estadistica'
                }
            }
        },
        methods: {
            showEstadistica(numero){
                let me = this;
                let url;
                switch (numero) {
                    case 1:
                        url = me.Ruta.estadistica + '/getEVentas';
                        axios.get(url).then(function(response){
                            me.ventas = response.data.ventas;
                            //cargamos los datos del chart
                            me.generateGraphic(1);
                        })
                        .catch(function(error){
                            console.log(error);
                        });
                        break;
                }
            },
            generateGraphic(numero){
                let me = this;
                let ejeX = [], ejeY = [];
                switch (numero) {
                    case 1:
                        me.ventas.map(function(x){
                            ejeX.push(me.fix(1, x.mes));
                            ejeY.push(x.num_ventas);
                        });
                        // let varVenta = document.getElementById('estadistica').getContext('2d');
                        me.lugarMuestra = document.getElementById('estadistica').getContext('2d');
                        me.charVenta = new Chart(me.lugarMuestra, {
                            type: 'line',
                            data: {
                                labels: ejeX,
                                datasets: [{
                                    label: 'Ventas',
                                    data: ejeY,
                                    borderColor: ['rgba(16, 52, 218, 0.8)'],
                                    fill: false,
                                    steppedLine: false,
                                    pointBackgroundColor: ['rgba(58, 18, 0, 0.3)'],
                                    pointBorderWidth: [2],
                                    pointRadius: 8
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
            // this.getEstadistica(this.numMostrar);
            this.showEstadistica(this.num_mostrar);
        }
    }
</script>