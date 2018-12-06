
<template>
    <div class="content" :fin="fin">
        <div class="bg-express-grad-2b bg-h-all">
            <div class="offset-sm-3 col-sm-6 pt-5">
                <h1 class="titulo-fin">¡{{this.message.title}}!</h1>
            </div>
            <p class="fin text-white p-4">{{this.message.msj}}</p>
            <div class="offset-sm-3 offset-1 col-sm-6 col-10 bg-white p-4">
                <p class="fin">¡Síguenos en nuestras redes!</p>
                <div class="offset-sm-3 col-sm-6 row">
                    <div class="col-4">
                        <a href=""><img src="img/insta1.png" alt=""></a>
                    </div>
                    <div class="col-4">
                        <a href=""><img src="img/twitter1.png" alt=""></a>
                    </div>
                    <div class="col-4">
                        <a href=""><img src="img/fb1.png" alt=""></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

    <script>
        new Vue({
            el: '#menu',
            data: {
                fin: true
            }
        });
        export default {
            props : ['message','lead'],
            data(){
                return {
                   fin : true,
                   titulo : '',
                   mensaje : ''
                } 

            },
            mounted() {
                console.log(this.lead)
            }
        }
    </script>
    