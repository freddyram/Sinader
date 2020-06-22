<template>
  <v-row justify="center">
    <v-dialog
      v-model="dialog"
      width="500"
    >
      <v-card>
        <v-toolbar  color="secondary_green" dark>
            <v-btn icon dark @click="dialog = false">
              <v-icon>close</v-icon>
            </v-btn>
            <v-toolbar-title >Carga Excel</v-toolbar-title>
 <!--               <v-btn @click='EventSubir' color="main_green">Subir Excel</v-btn > 
  -->       
        </v-toolbar>
            <v-layout>
                <v-card>
                    <v-file-input show-size counter chips multiple label="Arquivo Geral" ref="myfile" v-model="files"></v-file-input>
                </v-card>
                <v-spacer></v-spacer>
                    <v-btn @click='EventSubir' color="main_green">Subir Excel</v-btn > 
            </v-layout>
      </v-card>
    </v-dialog>
  </v-row>
</template>

<script>
  import Vue from 'vue';  
  import Vuex from 'vuex'; 
  import { mapState } from 'vuex';  
  
  import { EventBus } from './../eventbus.js';

  export default {
    data: () => ({
        dialog: true, 
        }),
    methods: {
        EventSubir(){
            let formData = new FormData();
            formData.append('file', this.file);
            axios
                .post( '/api/monthwaste',
                    formData, {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    }
                ).then(function(){
                    console.log('SUCCESS!!');
                })
                .catch(function(){
                    console.log('FAILURE!!');
                });
        },
        handleFileUpload(){
            this.file = this.$refs.file.files[0];
        }
    }
};
</script>