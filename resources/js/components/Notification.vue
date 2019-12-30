<template>
    <div v-if="notifications.length">
        <div v-for="item in listar" :key="item.id">
            <a class="dropdown-item d-flex align-items-center" href="#">
                <div class="mr-3">
                    <div class="icon-circle bg-success">
                        <i class="fas fa-donate text-white"></i>
                    </div>
                </div>
                <div >
                    <div class="small text-gray-500">Ventas de hoy</div>
                    <h6>{{item.c1.nombre}}
                    <span class="badge badge-success">{{item.c1.numero}}</span></h6>
                </div>
            </a>
            <a class="dropdown-item d-flex align-items-center" href="#">
                <div class="mr-3">
                    <div class="icon-circle bg-success">
                        <i class="fas fa-donate text-white"></i>
                    </div>
                </div>
                <div>
                    <div class="small text-gray-500">December 7, 2019</div>
                    <h6>{{item.c2.nombre}}
                    <span class="badge badge-success">{{item.c2.numero}}</span></h6>
                </div>
            </a>
            <a class="dropdown-item d-flex align-items-center" href="#">
                <div class="mr-3">
                    <div class="icon-circle bg-success">
                        <i class="fas fa-donate text-white"></i>
                    </div>
                </div>
                <div>
                    <div class="small text-gray-500">December 7, 2019</div>
                    <h6>{{item.c3.nombre}}
                    <span class="badge badge-success">{{item.c3.numero}}</span></h6>
                </div>
            </a>
        </div>
    </div>
    <div v-else>
        <a class="dropdown-item text-center small text-gray-900" href="#">No tiene notificaciones</a>
    </div>
</template>

<script>
    export default {
        props: ['notifications'],
        data(){
            return{
                arrayNotifications: []
            }
        },
        computed:{
            listar: function(){
                // return this.notifications[0];
                this.arrayNotifications = Object.values(this.notifications[0]);
                if (this.notifications == '') {
                    return this.arrayNotifications = [];
                }else{
                    //Capturo la última notificacion agregada
                    this.arrayNotifications = Object.values(this.notifications[0]);
                    //Validación por índice fuera de rango
                    if (this.arrayNotifications.length >3) {
                        //Si el tamaño es >3 Es cuando las notificaciones son obtenidas desde el mismo servidor es decir por la consulta por axios
                        return Object.values(this.arrayNotifications[4]);
                    }else{
                        //Si el tamaño es <3 Es cuando las notificaciones son obtenidas desde el canal privado, es decir mediante laravel Echo y Pusher
                        return Object.values(this.arrayNotifications[0]);
                    }
                }
            }
        }
    }
</script>
