<template>    
    <div class="content">
        <div class="title-sol bg-grad">
            <div class="div-sol">                        
                <h1 class="w-100">Tu solución Enlight</h1>
                <div class="row">
                    <div class="col-4">
                        <h2 class="w-100 text-white"><span class="w-100 text-white"> {{ this.potencia | currency('', 2)}}</span> <sup>kWp</sup></h2>
                        <h3 class="sol w-100 text-white">Potencia del sistema</h3>
                    </div>
                    <div class="col-4">
                        <h2 class="w-100 text-white"><span class="w-100 text-white"> {{ this.consumo_cubierto | currency('', 0)}}</span> <sup>%</sup></h2>
                        <h3 class="sol w-100 text-white">Consumo cubierto</h3>
                    </div>
                    <div class="col-4">
                        <h2 class="w-100 text-white"><span class="w-100 text-white"> {{ this.espacio_req }}</span> <sup>m<sup>2</sup></sup></h2>
                        <h3 class="sol w-100 text-white">Espacio aproximado</h3>
                    </div>    
                </div>
            </div>
            <div>
                <button class="btn-edit" v-on:click="show">
                    <v-icon name="pen" scale="1"/><br>
                </button>
                <div class="div-back">
                    <span class="text-white btn-edit">EDITAR</span>    
                </div>
            </div>  
        </div>  
        <div class="content-calcu">
            <div class="div-sol">
                <img src="img/logo_header.png" width="60" class="pt-3 show_movil">
                <h2 class="col-blue-enlight bold p-3 pt-4 ahorro">¡Ahorrarás {{ this.ahorro_bimestral | currency('$', 0) }} <br class="show_movil"> cada bimestre!</h2>  
                <hr class="show_movil">
                <div class="row">
                    <div class="col-md-3 col-6">
                        <h4>En un año,<br>ahorrarás:</h4>
                        <h2 class="info-ahorro text-cyan-enlight">{{ this.ahorro_anual | currency('$', 0) }}</h2>
                    </div>
                    <div class="col-md-3 col-6">
                        <h4>Recuperarás la<br>inversión en:</h4>
                        <h2 class="info-ahorro text-cyan-enlight">{{ this.roi}} años</h2>
                    </div>
                    <div class="offset-md-2 col-md-4 col-12">
                    <hr class="show_movil">
                        <h4>En los primeros 25 años del sistema,<br class="show_movil">te habrás ahorrado</h4>
                        <h2 class="info-ahorro text-blue-enlight">{{ this.ahorro_25  | currency('$', 0) }}</h2>
                    </div>
                </div>  
                <div class="offset-md-3 col-md-6 pt-4">
                    <button class="btn btn-siguiente block" v-on:click="showModalFinaliza">Finalizar</button>
                </div>           
            </div>
        </div>
        
    
        <modal name="modal-cotiza-express"
                :adaptive="true"
                :max-width="700"
                :max-height="600"
                width="90%"
                height="80%">
            <div style="text-align: center">
                <div class="pt-1 head-modal">
                    <img src="img/logo_header.png" width="80" class="pt-3">
                    <h2 class="p-4">Solicita tu cotización</h2>
                </div>
                <div class="modal-body gradient-2">
                    <p>¡Podemos presentarte tu proyecto hoy mismo!</p>
                </div>
                <div class="offset-md-1 col-md-10 pt-1">
                    <p class="modal-p">Sólo tienes que ayudarnos a encontrar tu techo y enviar tu recibo de luz.</p>
                    <button type="button" class="btn btn-enlight block" v-on:click="loadMap">Comenzar</button> <br>
                    <div class="pt-3">
                        <button class="enlace-1" v-on:click="asesor">Prefiero que me contacte un asesor.</button>
                    </div>
                </div> 
            </div>              
        </modal>

        <modal name="modal-edita-express"
                :adaptive="true"
                :max-width="650"
                :max-height="650"
                width="80%"
                height="60%">
            <div style="padding:30px; text-align: center">
            <form action="" v-on:submit.prevent="recalculo">
                <div class="pt-4">
                    <h2 class="text-blue-enlight">Edita tu cálculo...</h2>
                </div>
                <div class="pt-3">
                    <p class="big">¿Cuánto pagas de luz por bimestre?</p>
                    <div style="text-align: center;">
                        <h2 class="how focus"><span class="how">{{this.consumption | currency('$', 0) }}</span></h2>
                        <div class="offset-md-2 col-md-8">
                            <vue-slider ref="slider" v-bind="options" v-model="consumption"></vue-slider>
                        </div>
                    </div>
                </div>
                <div class="offset-md-3 col-md-6 pb-5">
                    <button type="submit" :disabled='isDisabled' class="btn btn-siguiente block">Recalcular</button>
                </div> 
            </form>
                
            </div>              
        </modal>
    </div>        
</template>
        
        
<script>
        import vueSlider from 'vue-slider-component';
        import VModal from 'vue-js-modal'         
        Vue.use(VModal)
        Vue.use(vueSlider)    
        export default {
            props : ['lead','lead_id','mty'],
            components: {
                vueSlider,
                VModal
            },
            data(){
                return {
                    data : {},
                    potencia : '',
                    consumo_cubierto : '',
                    espacio_req : '',
                    ahorro_bimestral : '',
                    ahorro_anual : '',
                    roi : '',
                    ahorro_25 : '',
                    // lead_id : this.lead_id,
                    // mty : this.mty,
                    consumption_tmp: this.lead.consumption,
                    consumption : this.lead.consumption,
                    // consumption : 2500,
                    options : {
                        width: "100%",
                        height: 8,
                        dotSize: 20,
                        min: 0,
                        max: 20000,
                        interval : 100,
                        disabled: false,
                        show: true,
                        tooltip: "never",
                        tooltipDir: [
                        "bottom",
                        "top"
                        ],
                        piecewise: false,
                        style: {
                        "marginBottom": "30px"
                        },
                        bgStyle: {
                        "backgroundColor": "#fff",
                        "boxShadow": "inset 0.5px 0.5px 3px 1px rgba(0,0,0,.36)"
                        },
                        sliderStyle: [],
                        tooltipStyle: [
                            {},
                            {
                                "backgroundColor": "#B6B9BC",
                                "borderColor": "#B6B9BC"
                            }
                        ],
                        processStyle: {
                            "backgroundImage": "-webkit-linear-gradient(left, #6CFD02, #FFE660, #FD3A00)"
                        }
                    }
                }
            },
            mounted() {
                this.data.consumption = this.consumption
                this.data.lead_id = this.lead_id
                this.data.mty = this.mty
                axios.post('/solution', this.data).then(
                    (response) =>
                    {
                        this.potencia = response.data.potencia_sistema
                        this.consumo_cubierto = response.data.consumo_cubierto
                        this.espacio_req = response.data.espacio
                        this.ahorro_bimestral = response.data.ahorro_bimestral
                        this.ahorro_anual = response.data.ahorro_anual
                        this.roi = response.data.roi
                        this.ahorro_25 = response.data.ahorro_25
                    }
                );
            },
            computed: {
                isDisabled: function(){ 
                    return (!this.consumption);
                }
            },
            methods: {
                show () {
                    this.$modal.show('modal-edita-express');
                },
                hide () {
                    this.$modal.hide('modal-edita-express');
                },
                showModalFinaliza () {
                    if(this.consumption > this.consumption_tmp)
                    {
                        let lead  = {
                            consumption : this.consumption
                        }
                        this.$emit('editLead',lead);    
                    }
                    this.$modal.show('modal-cotiza-express');                
                },
                recalculo () {
                    this.data.consumption = this.consumption
                    this.data.lead_id = this.lead_id
                    this.data.mty = this.mty
                    axios.post('/solution', this.data).then(
                        (response) =>
                        {
                            this.$modal.hide('modal-edita-express');
                            this.potencia = response.data.potencia_sistema
                            this.consumo_cubierto = response.data.consumo_cubierto
                            this.espacio_req = response.data.espacio
                            this.ahorro_bimestral = response.data.ahorro_bimestral
                            this.ahorro_anual = response.data.ahorro_anual
                            this.roi = response.data.roi
                            this.ahorro_25 = response.data.ahorro_25
                        }
                    );
                    if(this.consumption < 2500)
                    {
                        this.$swal({
                            title: '',
                            text: "Ingresa un número mayor a $2,500",
                            type: 'error',
                            showCancelButton: false,
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'Aceptar'
                        }).then((result) => {
                            this.consumption = this.consumption_tmp
                        })  
                    }
                },
                hideModalFinaliza () {
                    this.$modal.hide('modal-cotiza-express');
                },
                loadMap(){
                    this.$emit('loadMap');
                },
                asesor(){
                    this.$modal.hide('modal-cotiza-express');
                    this.$emit('asesor');
                }
            }
        }
    </script>
    