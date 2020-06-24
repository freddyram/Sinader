<template>
  <div class="text-center">
    <v-dialog
      v-model="dialog"
      width="500"
    >
<!--       <template v-slot:activator="{ on }">
        <v-btn
          color="red lighten-2"
          dark
          v-on="on"
        >
          Click Me
        </v-btn>
      </template> -->

      <v-card>
        <v-card-title
          class="headline grey lighten-2"
          primary-title
        >
          Crear Empresa de Transporte
        </v-card-title>

        <v-card-text>
            <v-form ref="form"  lazy-validation>
            <v-layout>
                <v-flex  xs12 class="px-1">

                    <v-text-field ref="rut" v-model="rut" :rules='rutRule' @change='validateRut' label="Rut Empresa o Persona Natural"></v-text-field>
                </v-flex>
            </v-layout>    
                        
            <v-layout>
                <v-flex  xs12 class="px-1">

                    <v-text-field :rules='notnullRule' v-model="name" label="Nombre Empresa o Persona Natural"></v-text-field>

                </v-flex>
            </v-layout>   

            <v-layout>
                <v-flex  xs12 class="px-1">
                    <v-text-field :rules='notnullRule' v-model="plate" label="Patente"></v-text-field>
                </v-flex>
            </v-layout> 
            </v-form>
        </v-card-text>

        <v-divider></v-divider>

        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn
            color="main_green"
            class='white--text'
            @click="save"
            >
            Guardar
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>

<script>

  
  import { EventBus } from './../eventbus.js';


  export default {
    data () {
      return {

        notnullRule: [(v) => !!v || "Campo requerido"],
        rutRule: [(v) => !!v || "Campo requerido", (v) => /^[0-9]+[-|‐]{1}[0-9kK]{1}$/.test(v) || "Formato incorrecto"],


        checkbox:false,
        dialog: true,
        rut:'',
        name:'',
        plate:'',
        }
      },

    created () {
        this.initialize();
    },

    methods: {
        initialize(){
            alert('Validación SII, pendiente')
        },

        validateRut (rutCompleto) {
            var app = this;
            if (/^[0-9]+[-|‐]{1}[0-9kK]{1}$/.test( rutCompleto )){
                var tmp     = rutCompleto.split('-');
                var digv    = tmp[1]; 
                var rut     = tmp[0];
                if ( digv == 'K' ) digv = 'k' ;
                if  (this.dv(rut) != digv ){
                    alert('Rut Incorrecto');
                    this.rut = '';
                    this.$refs.rut.focus();
                }
            }
        },
        dv (T){
            var M=0,S=1;
            for(;T;T=Math.floor(T/10))
                S=(S+T%10*(9-M++%6))%11;
            return S?S-1:'k';
        },

        save(){
            var tmp     = this.rut.split('-');
            var digv    = tmp[1]; 
            var rut     = tmp[0];


            if (this.$refs.form.validate()){
                var carrier = {    
                rut: rut,  
                dv: digv,  
                name : this.name, 
                plate: this.plate, 
                establishment_id: this.$store.getters.establishment.id
                }

                alert(JSON.stringify(carrier));

                // axios.post('/api/carrier/savenotregistered', carrier)
                //     .then(function (resp) {    
                //         EventBus.$emit('saveTransport', 'someValue');
                //     })
                //     .catch(function (resp) {
                //         console.log(resp);
                //         alert(resp);
                // });

                this.dialog = false;
            }


        }
    }
  }

  
</script>