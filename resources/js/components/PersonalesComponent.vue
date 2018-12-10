<template>
    <div>
    <form
        novalidate="true"
    >
        <div class="title bg-calcula">
            <div class="col-1">
                <button class="btn-back" v-on:click="back1()">
                    <v-icon name="arrow-left" scale="1"/>
                </button>
                <div class="div-back"></div>
            </div>
            <div class="col-10">
                <h1 class="w-100">¡Tu solución Enlight está lista!</h1> 
                <h5 class="stp">Paso 2 de 2</h5>
            </div>
        </div>  
        <div class="content-calcu">
            <div class="offset-1 col-10">
                <div class="row pt-5">
                    <div class="col-md-6">
                        <h4>Proporciona tus datos para ver tu solución:</h4>  
                        <input type="text" @blur="validName()" v-bind:class="{'form-control':true, 'focus-ini':inicio_focus()}" placeholder="Nombre completo*" v-model="name" name="name" v-validate="'required'">
                        <span>{{ errors.first('name') }}</span>
                        <div class="row">
                            <div class="col-md-6">
                                <input type="email" @blur="findEmail()" v-bind:class="{'form-control':true, 'focus-ini':inicio_focus(), 'col-auto': true }" placeholder="Email*" name="email" v-model="email" v-validate="'required|email'" data-vv-as="email">
                                <span>{{ errors.first('email') }}</span>
                            </div>
                            <div class="col-md-6">
                                <input v-bind:class="{'form-control':true, 'focus-ini':inicio_focus(), 'col-auto': true }" placeholder="Teléfono*" v-model="phone" name="phone" type="text" v-validate="'required|numeric|min:7|max:12'">
                                <span>{{ errors.first('phone') }}</span>
                            </div>    
                        </div>      
                    </div>
                    <div class="offset-md-1 col-md-5 offset-sm-0 col-sm-12">
                        <br class="show_movil">
                        <h4>¿Tienes un código de promoción?</h4>
                        <h4>¡Utilízalo aquí!</h4>
                        <div v-bind:class="{'input-container2':true,'w-100':true,'active':this.active}">
                            <input type="text" @blur="findPromo" v-bind:class="{'form-code':true, 'active':this.active}" placeholder="Ingresa un código de promoción aquí" v-model="code_promo">
                            <span v-on:click="showCode">
                                <v-icon name="question-circle" scale="1.2" class="col-blue-enlight"/>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="row pt-4">
                    <div class="offset-md-3 col-md-6">
                        <button type="button" v-on:click="personalLead" class="btn btn-siguiente block" :disabled="isDisabled">Ver mi solución</button>
                    </div>     
                </div>
            </div> 
        </div>  
    </form>
        <modal name="modal-code"
            :adaptive="true"
            :max-width="600"
            :max-height="600"
            width="85%"
            height="85%">
            <div style="text-align: center">
                <div class="pt-5">
                    <h2 class="text-blue-enlight">¡Haz válida tu promoción aquí!</h2>
                </div>
                <div class="pt-3">
                    <p class="big-3 plad-5">Sólo aplica si</p> 
                        <button class="step">
                            1
                        </button>
                    <p class="big-3 plad-5">Te recomendó con nosotros uno de nuestros clientes y te compartió su <b>código de referido</b>, o</p>
                        <button class="step">
                            2
                        </button>
                    <p class="big-3 plad-5">Encontraste alguna de nuestras promociones temporales en algún medio digital o impreso</p>                        
                </div>
            </div>              
        </modal>
    </div>
</template>
    
    <script>
        import VueSweetalert2 from 'vue-sweetalert2';
        Vue.use(VueSweetalert2);
        import VModal from 'vue-js-modal' 
        Vue.use(VModal)
        // Vue.use(VeeValidate);

        export default {
            // props : ['lead'],
            components: {
                VModal
            },  
            data(){
                return {
                    active : false,
                    dimension : undefined,
                    name : undefined,
                    email : undefined,
                    phone : undefined,

                    code_promo : undefined,
                    bandera : 0,
                    contacto_refiere : undefined,
                    promo_convenio : undefined,
                }
            },
            //Método para habilitar/deshabilitar botón Siguiente hasta completar todos los campos
            computed: {
                isDisabled: function(){ 
                    return (!this.name || !this.email || !this.phone || this.errors.items.length > 0);
                },
            },
            methods: {
                validName() {
                    var re = /^[a-zA-Z]{2,}\s[a-zA-Z]{2,}/
                    if(!re.test(this.name)){        
                        // errors.name.push('Es requerido un nombre y apellido');
                    }
                },
                findEmail() {
                    if(this.email != '' && this.email != undefined) {
                        this.bandera = 1;
                        axios.get('leads/'+this.email).then(
                            (response) => {
                                if (!response.data.status) {         
                                    this.$swal({
                                        title: 'Lo sentimos',
                                        text: "El correo "+this.email+" ha sido utilizado previamente, para continuar ingresa otro correo.",
                                        type: 'error',
                                        showCancelButton: false,
                                        confirmButtonColor: '#3085d6',
                                        confirmButtonText: 'Aceptar'
                                    }).then((result) => {
                                        if (result.value) {
                                            this.bandera = 2
                                            this.email = '';
                                        }
                                    }) 
                                }else{ this.bandera = 2; }
                            }
                        )   
                    }
                },
                findPromo(){
                    if(this.code_promo != '' && this.code_promo != undefined)
                    {
                        this.bandera = 1
                        axios.get('promos/'+this.code_promo).then(
                            (response) => {
                                if (!response.data.status) {         
                                    this.$swal({
                                        title: 'Lo sentimos',
                                        text: "El código ingresado no existe; por favor, ingresa un código válido.",
                                        type: 'error',
                                        showCancelButton: false,
                                        confirmButtonColor: '#3085d6',
                                        confirmButtonText: 'Aceptar'
                                    }).then((result) => {
                                        if (result.value) {
                                            this.bandera = 2
                                            this.code_promo = '';
                                            this.promo_convenio = '';
                                            this.contacto_refiere = ''
                                            this.active = false
                                        }
                                    }) 
                                } 
                                if(response.data.promocion){
                                    if(!response.data.vigente)
                                    {          
                                        this.$swal({
                                            title: 'Lo sentimos',
                                            text: "Esta promoción ya no está vigente. Ingresa un código distinto.",
                                            type: 'error',
                                            showCancelButton: false,
                                            confirmButtonColor: '#3085d6',
                                            confirmButtonText: 'Aceptar'
                                        }).then((result) => {
                                            if (result.value) {
                                                this.bandera = 2
                                                this.code_promo = '';
                                                this.promo_convenio = '';
                                                this.contacto_refiere = '';
                                                this.active = false
                                            }
                                        })    
                                    }
                                    else{     
                                        this.$swal({
                                            title: 'Felicidades',
                                            text: "Has sido registrado bajo la promoción " + response.data.nombre_promo,
                                            type: 'info',
                                            showCancelButton: false,
                                            confirmButtonColor: '#3085d6',
                                            confirmButtonText: 'Aceptar'
                                        }).then((result) => {
                                            if (result.value) {
                                                this.bandera = 2
                                                this.code_promo = response.data.nombre_promo
                                                this.promo_convenio = response.data.nombre_promo;
                                                this.contacto_refiere = '';
                                                this.active = true
                                            }
                                        }) 
                                    }
                                }
                                else if(response.data.referido){
                                    this.$swal({
                                        title: 'Felicidades',
                                        text: "¡Has sido registrado como referido de " + response.data.contacto +"!",
                                        type: 'info',
                                        showCancelButton: false,
                                        confirmButtonColor: '#3085d6',
                                        confirmButtonText: 'Aceptar'
                                    }).then((result) => {
                                        if (result.value) {
                                            this.bandera = 2
                                            this.code_promo = response.data.nombre_promo
                                            this.promo_convenio = '';
                                            this.contacto_refiere = response.data.contacto;
                                            this.active = true
                                        }
                                    }) 
                                }
                            }
                        )    
                    }
                    else{
                        this.bandera = 0
                    }
                    
                },
                inicio_focus(){
                    return(this.name == undefined && this.email == undefined && this.phone == undefined)
                },
                personalLead(){
                    if(this.errors.items.length == 0)
                    {
                        // if (!this.errors.first('name') && !this.errors.first('email')  && !this.errors.first('phone') ) {
                            let lead  = {
                                name : this.name,
                                email : this.email,
                                phone : this.phone,
                                promo_convenio : this.promo_convenio,
                                contacto_refiere : this.contacto_refiere,
                                code_promo : this.code_promo
                            }
                            if(this.bandera == 0 || this.bandera == 2 )
                            {   
                                this.$emit('personalLead',lead);
                            }
                        // }               
                    }
                             
                },
                showCode() {
                    this.$modal.show('modal-code')
                },
                back1(){
                    this.$emit('back1');
                }
            }
        }
    </script>
    