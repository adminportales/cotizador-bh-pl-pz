<div>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const solicitarInfo = () => {
                Swal.fire({
                    title: 'Nos hace falta información',
                    text: 'Ingresa tu número de teléfono para colocarlo en las cotizaciones.',
                    input: 'number',
                    showCancelButton: true,
                    confirmButtonText: 'Enviar',
                    cancelButtonText: 'Cancelar',
                    showLoaderOnConfirm: true,
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                }).then(async (result) => {
                    if (result.value == undefined) {
                        solicitarInfo()
                    } else if (result.value.trim() !== "") {
                        if (!isNaN(result.value.trim())) {
                            @this.phone = await result.value.trim();

                            let respuesta = await @this.changePhone()
                            if (respuesta == 1) {
                                Swal.fire('La información se registró correctamente.',
                                    '',
                                    'success')
                            } else {
                                Swal.fire('¡Error al registrar la información!', '',
                                    'error')
                                solicitarInfo()
                            }
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
