<template>
    <div v-if="notifications.length">
        <div v-for="item in notifications" :key="item.id">
            <a class="dropdown-item d-flex align-items-center" href="#">
                <div class="mr-3">
                    <div class="icon-circle bg-success">
                        <i class="fas fa-donate text-white"></i>
                    </div>
                </div>
                <div>
                    <div class="small text-gray-500">December 7, 2019</div>
                    {{item.data.datos.c1.msj}} - {{item.data.datos.c1.numero}}
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
                    {{item.data.datos.c2.msj}} - {{item.data.datos.c2.numero}}
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
                    {{item.data.datos.c3.msj}} - {{item.data.datos.c3.numero}}
                </div>
            </a>
        </div>
    </div>
    <div v-else>
        <a><span>No tiene notificaciones</span></a>
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
            // listar: function(){
            //     //return this.notifications[0];
            //     this.arrayNotifications = Object.values(this.notifications[0]);
            //     if (this.notifications == '') {
            //         return this.arrayNotifications = [];
            //     }else{
            //         //Capturo la última notificacion agregada
            //         this.arrayNotifications = Object.values(this.notifications[0]);
            //         //Validación por índice fuera de rango
            //         if (this.arrayNotifications.length >3) {
            //             //Si el tamaño es >3 Es cuando las notificaciones son obtenidas desde el mismo servidor
            //             return Object.values(this.arrayNotifications[4]);
            //         }else{
            //             //Si el tamaño es <3 Es cuando las notificaciones son obtenidas desde el canal privado, es decir mediante laravel Echo y Pusher
            //             return Object.values(this.arrayNotifications[0]);
            //         }
            //     }
            // }
        }
    }
</script>
