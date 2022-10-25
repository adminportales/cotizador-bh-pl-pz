<div>
    DFROJI
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const solicitarInfo = () => {
                Swal.fire({
                    title: 'Nos hace falta informacion',
                    text: 'Ingresa tu numero de telefono para colocarlo en las cotizaciones.',
                    input: 'number',
                    showCancelButton: true,
                    confirmButtonText: 'Enviar',
                    cancelButtonText: 'Cancelar',
                    showLoaderOnConfirm: true,
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                }).then((result) => {
                    if (result.value == undefined) {
                        solicitarInfo()
                    } else if (result.value.trim() !== "") {
                        if (!isNaN(result.value.trim())) {
                            let result = @this.changePhone("result.value.trim()")
                            result.then(response => {
                                if (response == 1) {
                                    Swal.fire('La informacion se registro correctamente', '',
                                        'success')
                                }
                            }, () => {
                                Swal.fire('¡Error al registrar la informacion!', '', 'error')
                                solicitarInfo()
                            })
                            return
                        } else {
                            solicitarInfo()
                        }
                    } else {
                        solicitarInfo()
                    }
                    if (!result.isConfirmed) {
                        solicitarInfo()
                    }
                })
            }
            solicitarInfo()
        })
    </script>
</div>
