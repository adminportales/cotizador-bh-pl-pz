<div>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const solicitarInfo = () => {
                Swal.fire({
                    title: 'Nos hace falta informacion',
                    text: 'Selecciona la empresa a la que perteneces.',
                    input: 'select',
                    inputOptions: {
                        '1': 'BH Trademarket',
                        '2': 'Promo Life',
                        '3': 'Promo Zale'
                    },
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
                            @this.company = await result.value.trim();

                            let respuesta = await @this.changeCompany()
                            if (respuesta == 1) {
                                Swal.fire('La informacion se registro correctamente',
                                    '',
                                    'success')
                            } else {
                                Swal.fire('¡Error al registrar la informacion!', '',
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
