<template>


     <v-layout row justify-center>
    <v-dialog v-model="dialog" persistent max-width="500">
      <template v-slot:activator="{ on }">

        <v-btn v-on="on" flat color="red lighten-2">
            Subir Archivo
        </v-btn>
        <!-- <v-btn color="primary" dark v-on="on">Upload</v-btn> -->
      </template>
      <v-card>
        <v-card-text>



            <v-flex xs12 class="px-2">
                <v-text-field label="Adjuntar Archivo (20MB max)" @click='pickFile' v-model='fileName' prepend-icon='attach_file'></v-text-field>
                <input
                    type="file"
                    style="display: none"
                    ref="file"
                    accept=".xlsx"
                    @change="onFilePicked"
                    :rules = "generalRule"
                >
            </v-flex>

            
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="secondary_green" class='white--text' @click="uploadAll()">
            Procesar
            <v-icon right>cached</v-icon>
          </v-btn>
          <v-btn color="secondary_green" class='white--text' @click="dialog = false">
            Cancelar
                <v-icon right>cancel</v-icon>
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-layout>


</template>  


<script>
  import Vue from 'vue';  
  import Vuex from 'vuex'; 
  import { mapState } from 'vuex';  

  import { EventBus } from './../eventbus.js';
  
  export default {
    data: () => ({
        dialog: true,
        fileName:'',
        imageFile:'',
        residues: [],
    }),
    methods: {
        pickFile () {
            this.$refs.file.click ()
        },
        onFilePicked (e) {
            const files = e.target.files
            if(files[0] !== undefined) {
                this.fileName = files[0].name
                if(this.fileName.lastIndexOf('.') <= 0) {
                    return
                }
                const fr = new FileReader ()
                fr.readAsDataURL(files[0])
                fr.addEventListener('load', () => {
                    this.imageUrl = fr.result
                    this.imageFile = files[0] // this is an image file that can be sent to server...
                })
            } else {
                this.fileName = ''
                this.imageFile = ''
                this.imageUrl = ''
            }
        },
        uploadAll(){

            // if (this.$refs.form.validate()){
                var declaration = {
                     declaration_id: 1 
                    
                }

                let formData = new FormData();
                let app = this;
                formData.append('data',  JSON.stringify(declaration));
                formData.append('file', this.imageFile);
                //formData.append('waste_detail', this.residues);

                axios.post('/api/declaration/upload',formData,
                    {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                    }
                })
                .then(function (resp) {
                    
                    app.$store.commit('changeWastedetail', resp.data);

                    EventBus.$emit('excelUpload', "someValue");
                    //this.residues = resp.data;
                    

                    //alert(JSON.stringify(resp.data));
                })
                .catch(function (resp) {
                    console.log(resp);
                });
                this.dialog = false;     

                      
            // }
        }
    }
}
</script>