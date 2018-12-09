<template>
    <div>
        <form action="" v-on:submit.prevent="newLead()">
            <div class="title bg-calcula">
                <div class="offset-1 col-10">
                    <h1 class="w-100">¡Calcula cuánto podrías ahorrar con Enlight!</h1>     
                    <h5 class="stp">Paso 1 de 2</h5>
                </div>   
            </div>  
            <div class="content-calcu">
                <div class="offset-1 col-10 form-dimension">
                    <div class="row">
                        <div class="col-md-4 col-sm-12 ptm-5">
                            <h4>¿Dónde quieres tu sistema solar?</h4>
                            <button type="button" class="btn btn-category"  
                                v-on:click="val_category('Residencial')" 
                                value="Residencial"
                                @click="selected = 'Residencial'"
                                :class="{selected:'Residencial' == selected}"
                                >Casa</button>
                            <a href="https://enlight.mx/cotizador-empresarial" class="btn btn-category"  
                                v-on:click="val_category('Empresa')"
                                @click="selected = 'Empresa'"
                                :class="{selected:'Empresa' == selected}"
                                value="Empresa">Empresa
                            </a>
                        </div>
                        <div class="col-md-4 col-sm-12 ptm-4">
                            <h4>¿Cuánto pagas de luz por bimestre?</h4>
                            <h2 class="how focus"><span class="how">{{this.consumption | currency('$', 0) }}</span></h2>
                            <vue-slider class="p-3" ref="slider" v-bind="options" v-model="consumption"></vue-slider>
                        </div>
                        <div class="col-md-4 col-sm-12">
                            <h4>Ingresa la dirección de tu inmueble:</h4>
                            <div class="input-container">
                                <gmap-autocomplete
                                    v-model="direction"
                                    name="direccion"
                                    @place_changed="setPlace"
                                    class="form-express" >
                                </gmap-autocomplete>
                                <!-- <input type="text" placeholder="Dirección" name="direction" v-model="direction"> -->
                                <span v-on:click="showWhy">
                                    <v-icon name="question-circle" scale="1.2" class="col-blue-enlight"/>
                                </span>
                            </div>
                        </div>                
                    </div> 
                    <div class="row ptm-4 pbm-4">
                        <div class="offset-md-3 col-md-6">
                            <button type="submit" :disabled='isDisabled' class="btn btn-siguiente block">Siguiente</button>
                        </div>  
                    </div>
                </div>                 
            </div>
        </form>
        <modal name="modal-why"
            :adaptive="true"
            :max-width="500"
            :max-height="500"
            width="85%"
            height="85%">
            <div style="text-align: center">
                <h3 class="m-title text-blue-enlight pt-4">¿Para qué necesitamos tu dirección?</h3>
                <div class="p-3">
                    <img src="assets('img/direccion.png')">
                    
                    <p class="big pt-3" style="text-align:justify">La ubicación de tu inmueble nos permitirá validar que Enlight tenga cobertura en tu zona y calcular la cantidad de luz solar que recibe tu techo para definir el número de paneles solares que necesitas.</p>
                </div>
            </div>              
        </modal>
    </div>
</template>

<script>
    import VueSweetalert2 from 'vue-sweetalert2';
    Vue.use(VueSweetalert2);
    import vueSlider from 'vue-slider-component';
    import Vue2Filters from 'vue2-filters';
    import * as VueGoogleMaps from 'vue2-google-maps'
    import VModal from 'vue-js-modal' 
        
    Vue.use(VModal)
    Vue.use(VueGoogleMaps, {
    load: {
            key: 'AIzaSyBSZF6bTP5Gd9o8jYFrDpFFzGovhEoXK5M',
            libraries: 'places', 
        },
    })
    Vue.use(Vue2Filters);
    export default {
        props : ['lead'],
        components: {
            vueSlider,
            VModal
        },
        plugins: [
            '~/plugins/vue2-filters'
        ],
        data(){
            return {
                mty : false,
                activo : true,
                cp_perfila : '',
                cp : '',
                places: [],
                currentPlace: null,
                selected: this.lead.category,
                lat : this.lead.lat,
                lng : this.lead.lng,
                category : this.lead.category,
                consumption : this.lead.consumption,
                direction : this.lead.direction,
                numero : undefined,
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
        //Método para habilitar/deshabilitar botón Siguiente hasta completar todos los campos
        computed: {
            isDisabled: function(){ 
                return (!this.category || this.consumption == 0 || !this.direction || !this.activo);
            }
        },
        methods: {
            showWhy() {
                this.$modal.show('modal-why')
            },
            val_category(value)
            {
                return this.category = value;
            },
            setPlace(place) {
                this.currentPlace = place
                this.components = place.address_components;
                var code_postal="";
                var numero = "";
                var mty = "";
                this.components.map(function(value, key) {
                    if(value.types[0] == "administrative_area_level_1"){
                        if(value.short_name == 'N.L.') { mty = true; } else { mty = false}
                    }        
                });
                this.mty = mty;
                this.components.map(function(value, key) {
                    if(value.types[0] == "postal_code"){
                        code_postal = value.short_name
                    }           
                });
                this.components.map(function(value, key) {
                    if(value.types[0] == "street_number"){
                        numero = value.short_name
                    }        
                });
                if(numero != "" && code_postal != ''){
                    this.activo = true;
                    if(code_postal != ''){
                        axios.get('/codigos/'+code_postal).then((response => {
                            this.cp = code_postal
                            this.cp_perfila = response.data.atendido
                        }))     
                    }else{
                        this.cp_perfila = false
                    }
                }
                else{
                    this.$swal({
                        title: 'Dirección incompleta',
                        text: "Asegúrate de incluir el número de la casa",
                        type: 'error',
                        showCancelButton: false,
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'Aceptar'
                    }).then((result) => {
                        this.activo = false;
                    }) 
                }
                 
                this.direction = this.currentPlace.formatted_address
                this.lat = this.currentPlace.geometry.location.lat()
                this.lng = this.currentPlace.geometry.location.lng()   
            },
            newLead(){
                if (this.category && this.consumption && this.direction) {
                    let lead  = {
                        mty : this.mty,
                        cp : this.cp,
                        category : this.category,
                        consumption : this.consumption,
                        direction : this.direction,
                        lat : this.lat,
                        lng : this.lng,
                        dimension : true,
                        cp_perfila : this.cp_perfila
                    }
                    this.$emit('newLead',lead);
                }
            }
        }
    }
</script>
