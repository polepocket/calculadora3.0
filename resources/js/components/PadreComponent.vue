
<template>    
    <div class="content">
        <dimension-component v-if="!dimension" :lead="lead" @newLead="newLead"></dimension-component>
        <personales-component  v-if="dimension&&!personal" @personalLead="personalLead" @back1="back1"></personales-component>
        <solucion-component v-if="dimension&&personal&&!solucion&&!fin" :mty="mty" :lead="lead" :lead_id="lead_id" @editLead="editLead" @sendNotes="sendNotes" @asesor="asesor" @loadMap="loadMap"></solucion-component>
        <map-component v-if="solucion&&!mapa" :lead="lead" @back2="back2" @updateDirection="updateDirection"></map-component>
        <recibo-component v-if="mapa&&!fin" :lead_id="lead_id"  @backMap="backMap" @enviarCorreo="enviarCorreo" @reciboLead="reciboLead"></recibo-component>
        <fin-component v-if="personal&&fin" :lead="lead" :message="message"></fin-component>
    </div>
</template>
    
<script>
    export default {
        data() {
            return {
                lead : {
                    cp_perfila : false,
                    cp : '',
                    lat : '',
                    lng : '',
                    category : '',
                    consumption : 0,
                    direction : '',
                    promo_convenio : '',
                    contacto_refiere: ''
                },
                mty : false,
                lead_id : '',
                message : [],
                dimension : false,
                personal : false,
                solucion : false,
                mapa : false,             
                fin : false         
            }
        },
        methods : {
            newLead(new_lead) {
                this.mty = new_lead.mty;
                this.lead.cp_perfila = new_lead.cp_perfila;
                this.lead.cp = new_lead.cp;
                this.lead.category = new_lead.category;
                this.lead.consumption = new_lead.consumption;
                this.lead.direction = new_lead.direction;
                this.lead.lat = new_lead.lat;
                this.lead.lng = new_lead.lng;
                this.dimension = true;
                this.personal = false;
            },
            personalLead(personal) {
                this.lead.name = personal.name;
                this.lead.email = personal.email;
                this.lead.phone = personal.phone;
                this.lead.promo = personal.promo;
                this.lead.contacto_refiere = personal.contacto_refiere;
                this.lead.promo_convenio = personal.promo_convenio
                axios.post('leads', this.lead).then(
                    (response) =>
                    {
                        if(this.lead.consumption < 2500 || this.lead.cp_perfila == "false")
                        {
                            this.fin = true;
                            this.message.title = 'Gracias'
                            this.message.msj = 'Te hemos enviado un correo electrónico con toda la información.'
                        }
                        this.personal = true;
                        this.lead_id = response.data.idlead[0];
                    }
                );
            },
            editLead(lead) {
                this.lead.consumption = lead.consumption;
                axios.put('leads/'+this.lead_id, this.lead).then(
                    (response) =>
                    {
                        // console.log('Editado');
                    }
                );
            },
            asesor() {
                this.fin = true
                this.message.title = 'Gracias'
                this.message.msj = 'En breve te contactará un asesor.'
            },
            loadMap() {
                this.solucion = true;
            },
            updateDirection(lead) {
                this.lead.direction = lead.direction;
                this.lead.lat = lead.lat;
                this.lead.lng = lead.lng;
                this.lead.cp_perfila = lead.cp_perfila; 
                axios.put('leads/'+this.lead_id, this.lead).then(
                    (response) =>
                    {
                        this.mapa = true;
                    }
                );
            },
            // sendNotes(lead) {
            //     let notas = {
            //         consumption : lead.consumption,
            //         consumption_tmp : lead.consumption_tmp
            //     }
            //     axios.put('/sendNotes/'+this.lead_id, this.notas).then(
            //         (response) =>
            //         {
            //             this.mapa = true;
            //         }
            //     );
            // },
            reciboLead(lead){
                this.fin = true
                this.message.title = 'Gracias'
                this.message.msj = 'En breve te contactará un asesor.'
            },
            back1() {
                this.dimension = false;
                this.personal = false;
            },
            back2() {
                this.solucion = false;
            },
            backMap() {
                this.mapa = false;
            },
            enviarCorreo() {
                this.fin = true
                this.message.title = 'Gracias'
                this.message.msj = 'Si no has recibido el correo haz clic aquí para volver a enviarlo.'
            },
        }
    }
</script>
