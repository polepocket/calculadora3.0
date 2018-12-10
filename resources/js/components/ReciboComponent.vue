<template>
    <div class="content">
        <div class="title bg-express-grad bg-h-recibo">
            <div class="col-1">
                <button class="btn-up" v-on:click="backMap()">
                    <v-icon name="arrow-up" scale="1.5"/>
                </button> 
                <div class="div-up">
                    <span class="text-white">Volver</span>
                </div>   
            </div>
            
            <div class="col-md-10">
                <img src="img/file.png" alt="">
                <div class="offset-md-3 col-md-6">
                    <p class="text-white big-2">¡Envíanos tu recibo de luz para poder adecuar nuestra propuesta a tu forma de consumir energía!</p>
                    <label class="btn btn-file block">
                        <span>{{this.name_file}}</span>
                        <input type="file" ref="myFile" class="hidden" v-validate="'ext:jpeg,jpg,png,pdf'" data-vv-as="image" name="file" @change="previewFiles" v-on:change="onImageChange">
                    </label>    
                    <h6 class="text-white">{{ errors.first('file') }}</h6>
                </div>         
            </div>
            <div class="col-1"></div>
        </div>
        <div class="pt-4">
            <div v-if="recibo" id="recibo-footer">
                <p class="big bold">¡Excelente! <br> Haz clic para finalizar</p>                    
                <button @click="uploadImage" class="circular">
                    <v-icon name="check" scale="1.5"/>
                </button>
            </div>
            <div class="offset-md-3 col-md-6">            
                <button v-if="!recibo" class="enlace-1 w-80" v-on:click="enviarCorreo">Haz clic aquí si no cuentas con él en este momento.</button>
            </div>
        </div>
        <!-- Modal de descuento, actualmente no se muestra -->
        <modal name="modal-descuento"
            :adaptive="true"
            :max-width="700"
            :max-height="700"
            width="90%"
            height="80%">
            <div style="text-align: center">
                <div class="p-5">
                    <h2>¡No te preocupes! Aún puedes ganarte tu descuento del 10%</h2>
                </div>
                <p class="big">
                    Al hacer clic en "Enviar correo" recibirás un correo a la dirección que registraste.
                    Responde con la foto o PDF de tu recibo de luz  <b>durante las próximas 24 horas</b> y mantendrás el descuento que te  prometimos.
                </p>
                <div class="offset-md-1 col-md-10 pt-1">
                    <button class="btn btn-siguiente block" v-on:click="enviarCorreo()">Enviar correo</button>
                    <div class="pt-3">
                        <button class="enlace-1" v-on:click="backMap()">Volver</button>
                    </div>
                </div> 
            </div>              
        </modal>
    </div>
</template>

<script>
    import VueSweetalert2 from 'vue-sweetalert2';
    Vue.use(VueSweetalert2);
    import VeeValidate from 'vee-validate';
    Vue.use(VeeValidate);
    export default {
        props : ['lead_id'],
        data() {
            return {
                image: '',
                name_file : 'Adjuntar',
                file: [],
                recibo : false
            }
        },
        
        components: {
            VeeValidate
        },
        methods: {
            onImageChange(e) {
                    let files = e.target.files || e.dataTransfer.files;
                    if (!files.length)
                        return;
                    this.createImage(files[0]);
                    this.file = this.$refs.myFile.files['0'];
                    this.name_file = this.$refs.myFile.files['0'].name
                    
                    
                              
            },
            createImage(file) {
                let reader = new FileReader();
                let vm = this;
                reader.onload = (e) => {
                    vm.image = e.target.result;
                };
                reader.readAsDataURL(file);
            },
            uploadImage(){
                let formData = new FormData();
                formData.append('file', this.file);
                formData.append('name_file', this.file.name);
                formData.append('lead_id', this.lead_id);
                let config = { headers: { 'Content-Type': 'multipart/form-data' } }
                axios.post('recibo', formData, config).then(response => {
                    if(response.data.response.result.message)
                    {
                        this.$emit('reciboLead');
                    }
                    else{
                        this.$swal("¡Lo sentimos!", "Ocurrió un error al guardar tu recibo, por favor vuelve a intentarlo", "error");   
                        this.file = '';
                        this.name_file = ''
                        this.recibo = false  
                    }
                }); 
            },
            previewFiles() {
                this.file = this.$refs.myFile.files['0'].name
                this.name_file = this.file
                this.recibo = true
            },
            backMap(){
                this.$emit('backMap')
            },
            enviarCorreo(){
                this.$emit('enviarCorreo')
            }
        }
    }
</script>

<!-- 
<template>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card card-default">
                        <div class="card-header">File Upload Component</div>
                        <div class="card-body">
                           <div class="row">
                              <div class="col-md-3" v-if="image">
                                  <img :src="image" class="img-responsive" height="70" width="90">
                               </div>
                              <div class="col-md-6">
                                  <input type="file" v-on:change="onImageChange" class="form-control">
                              </div>
                              <div class="col-md-3">
                                 <button class="btn btn-success btn-block" @click="uploadImage">Upload Image</button>
                              </div>
                           </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </template>
    
    <script>
        export default {
            data(){
                return {
                    image: ''
                }
            },
            methods: {
                onImageChange(e) {
                    let files = e.target.files || e.dataTransfer.files;
                    if (!files.length)
                        return;
                    this.createImage(files[0]);
                },
                createImage(file) {
                    let reader = new FileReader();
                    let vm = this;
                    reader.onload = (e) => {
                        vm.image = e.target.result;
                    };
                    reader.readAsDataURL(file);
                },
                uploadImage(){
                    axios.post('/recibo/store',{image: this.image}).then(response => {
                       console.log(response);
                    });
                }
            }
        }
    </script> -->