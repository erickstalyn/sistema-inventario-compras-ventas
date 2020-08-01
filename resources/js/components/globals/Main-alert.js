import Swal from 'sweetalert2'

const mainAlert = Swal.mixin({
    position: "top-end",
    toast: true,
    showConfirmButton: false,
    timer: 4500,
    timerProgressBar: true,
    onOpen: (toast) => {
      toast.addEventListener('mouseenter', Swal.stopTimer)
      toast.addEventListener('mouseleave', Swal.resumeTimer)
    },
    showClass: {
      popup: 'animate__animated animate__bounceInLeft animate__fast',
      backdrop: ''
    },
    hideClass: {
      popup: 'animate__animated animate__bounceOutRight',
    }
});

export default mainAlert;
