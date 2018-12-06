<template>
    <div class="content">        
        <div class="title bg-express-grad-2" style="height: 35vh;">
            <div class="offset-md-3 col-md-6">
                <h1 class="w-cien" id="titulo-agendar">¡Listo, ya tienes tu 10% de <br>descuento!</h1>     
            </div>    
        </div>
        <div class="mt-4 p-2">
            <p class="big-2">Agenda la presentación de tu cotización en línea y <br>convierte ese 10% en 15%:</p>
            <div class="row p-2">
                <div class="offset-sm-3 col-sm-2 col-6 pb-2">
                    <input type="calendar" class="w-100">
                </div>
                <div class="col-sm-2 col-6 pb-2">
                    <input type="number" class="w-100">
                </div>
                <div class="col-sm-2 col-12">
                    <button class="btn btn-enlight-2 block">Agendar</button>
                </div>    
            </div>
            <div class="mt-4">
                <a class="enlace-1" href="">Ningún horario me acomoda.</a>
                <a class="enlace-1" href="">Modificar mi horario.</a>
            
            </div>
            <div class="offset-sm-3 col-sm-6 mt-2">
                <button class="btn btn-enlight block">Finalizar</button>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
