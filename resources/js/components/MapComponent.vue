
<template>    
    <div class="content">
        <div class="title bg-express-grad">
            <div class="col-1">
                <button class="btn-back" v-on:click="back2">
                    <v-icon name="arrow-left" scale="1"/>
                </button>    
            </div>
            <div class="col-10">
                <p v-if="showIndication" class="big-2 text-white w-100">Arrastra el pin hasta el techo donde colocaremos tu sistema</p>
            </div>
        </div>
        <GmapMap id="map" class="map"
            :center="marker"
            map-type-id="satellite"
            :options="optionsMap"
        >
            <GmapMarker
                :options="optionsMarker",
                :position="marker"
                @drag="updateCoordinates"
                />
            </GmapMarker>
        </GmapMap>
        <div class="pt-5 pb-3">
            <h3 class="w-100 pt-3" v-if="hideMode&&!editMode">¿Esta no es tu dirección?</h3>
            <gmap-autocomplete
                v-if="editMode"
                @place_changed="setPlace"
                class="form-express" >
            </gmap-autocomplete>
            <p v-else class="big"> {{this.direction}} 
                <span v-if="!editMode" v-on:click="onClickEdit">
                    <v-icon name="pen" class="text-blue-enlight" scale="1.5"/>   
                </span>  
            </p>  
            <div v-if="!hideMode" class="pt-2">
                <span class="h3">¿Estás seguro que ese es tu techo? </span> <br class="show_movil">
                <button v-on:click="updateDirection" class="circular m-3">
                    <v-icon name="check" scale="1.5"/>
                </button>    
            </div>
        </div>
    </div>
    
</template>

<script>
    import * as VueGoogleMaps from 'vue2-google-maps'

    Vue.use(VueGoogleMaps, {
    load: {
            key: 'AIzaSyBSZF6bTP5Gd9o8jYFrDpFFzGovhEoXK5M',
            libraries: 'places', 
        },
    
    })
    // Vue.component('infoWindow', VueGoogleMaps.InfoWindow)
    export default {
        props : ['lead'],
        mounted() {
            this.geolocate()
        },
        name: "GoogleMap",
        data() {
            return {
                cp_perfila : false,
                lat: this.lead.lat,
                lng: this.lead.lng,
                marker: {
                    lat: this.lead.lat,
                    lng: this.lead.lng
                },
                places: [],
                currentPlace: null,
                direction : this.lead.direction,
                editMode : false,                
                hideMode : true,    
                showIndication : true,      
                optionsMap : {
                    title: 'Este es mi mapa',
                    scrollwheel: false, 
                    mapTypeControl: false,
                    tilt:0,                                
                    scaleControl: false,
                    streetViewControl: false,
                    overviewMapControl: false,
                    zoom:19,
                    style:"width: 100%;"
                },
                optionsMarker : {
                    clickable: true,
                    draggable: true,
                    alignment: "center",
                    icon: 'img/marker-move.png',
                    // label : {'text': 'Arrastra el pin hasta el techo donde colocaremos tu sistema', 'color': 'white', 'fontSize':'40px', 'fontFamily':'Gibson'}
                }
            }
        },
        computed: {
            isDisabled: function(){ 
                return (!this.editar);
            }
        },
        methods: {
            onClickEdit()
            {
                this.editMode = true;
                this.hideMode = true;
            },
            setPlace(place) {
                var code_postal="";
                var numero = "";
                this.currentPlace = place;
                this.optionsMarker = {
                    clickable: false,
                    draggable: true,
                    icon: 'img/marker-green.png'
                }
                this.components = place.address_components;
                // this.components.map(function(value, key) {
                //     if(value.types[0] == "postal_code"){
                //         cp = value.short_name;
                //     }                    
                // });                
                // axios.get('/codigos/'+cp).then((response => {
                //     this.perfila = response.data.length
                // }))      
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
                            this.code_postal = code_postal
                            this.cp_perfila = response.data.atendido
                        }))     
                    }else{
                        this.cp_perfila = false
                    }
                    this.addMarker();
                    this.showIndication = true;
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
            },
            addMarker() {
                this.optionsMarker = {
                    clickable: false,
                    draggable: true,
                    icon: 'img/marker-move.png'
                }
                if (this.currentPlace) {
                    this.lat = this.currentPlace.geometry.location.lat()
                    this.lng = this.currentPlace.geometry.location.lng()
                    this.marker = {
                        lat: this.lat,
                        lng: this.lng
                    };
                    this.places.push(this.currentPlace);
                    this.currentPlace = null;
                }
            },
            geolocate: function() {
                this.marker = {
                    lat: this.lat,
                    lng: this.lng
                }
                this.addMarker
            },
            updateCoordinates(location) {
                this.hideMode = false;
                this.showIndication = false;
                this.lat = location.latLng.lat()
                this.lng = location.latLng.lng()
                this.optionsMarker = {
                    clickable: false,
                    draggable: true,
                    icon: 'img/marker-green.png',
                    label : ''
                }
            },
            updateDirection(){
                let lead  = {
                    direction : this.direction,
                    lat : this.lat,
                    lng : this.lng,
                    cp_perfila : this.cp_perfila
                }
                this.$emit('updateDirection',lead);
            },
            back2(){
                this.$emit('back2');
            }
        }
    }
</script>
