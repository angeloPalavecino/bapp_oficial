<template>
  <div id="data-list-list-view" class="data-list-container">
  
     <!-- DOCUMENTOS -->
          <vs-popup class="holamundo"  title="Documentos Asociados" :active.sync="popupDocumento"
          @close="$close($event)">   
         
          <vs-tabs color="primary" ref="tabdocs" >
          <vs-tab label="Adjuntar"  icon-pack="feather" icon="icon-upload">
         
          <div class="vx-row">
              <div class="vx-col md:w-1/2 w-full mt-2"> 
                <vs-select v-model="itemDoc.tipo_documento" v-validate="'required'" label="Tipo de Documento" name="tipo_documento"
                class="w-full"  data-vv-scope="docs">
                  <vs-select-item :key="item.id" :value="item.id+'|'+item.name" :text="item.name" v-for="item in tipodocumentos_choices"  />
                </vs-select>
                <span class="text-danger text-sm" v-show="errors.has('docs.tipo_documento')">{{ errors.first('docs.tipo_documento') }}</span> 
              </div>
              <div class="vx-col md:w-1/2 w-full mt-3">
                 <flat-pickr v-model="itemDoc.fecha_vencimiento" v-validate="'required'" label="Fecha de Vencimiento" 
                class="w-full select-large mt-5" placeholder="Fecha de Vencimiento" name="fecha_vencimiento"   data-vv-scope="docs"/>
                <span class="text-danger text-sm">{{ errors.first('docs.fecha_vencimiento') }}</span>                           
              </div>
              <div class="vx-col md:w-1/2 w-full mt-5">
               <input
                  label="Documento"
                  type="file"
                  class="w-full"
                  name="file"
                  id="file"
                  @change = "uploadData"  
                  ref="fileupload"
                  accept="application/pdf,application/msword,application/image/png,image/jpeg"           
                />
                <span class="text-sm" >Fomatos permitidos: JPG - PNG - DOC - PDF</span>
                <br/>
                <span class="text-sm" ><i>Tamaño maximo 2 MB</i></span>
              
              </div>
              <div class="vx-col md:w-1/2 w-full mt-5">
                <vs-button @click="upload('docs')" color="primary" type="filled">Adjuntar</vs-button>
              </div>
            </div>  
            <div class="vx-row">
            </div>
          </vs-tab>
          <vs-tab label="Documentos" icon-pack="feather" icon="icon-file-text">

            <vs-table max-items="4" pagination  :data="documentos_choices" ref="tabladoc">
                  <template slot="header">
                    <h3>
                      Documentos Subidos
                    </h3>
                  </template>
                  <template slot="thead">
                    <vs-th colspan="2">
                       Documento
                    </vs-th>
                    <vs-th>
                       Vencimiento
                    </vs-th>
                    <vs-th>
                      Descarga
                    </vs-th>                                     
                  </template>

                  <template slot-scope="{data}">
                    <vs-tr :key="indextrdoc" v-for="(trdoc, indextrdoc) in data" >
                      <vs-td colspan="2">
                        {{ trdoc.name.split(/[.,\/-]/)[1] }}
                      </vs-td>
                      <vs-td>
                        <vs-chip :color="getStatusColor(trdoc.fecha_vencimiento)">{{ trdoc.fecha_vencimiento }}</vs-chip>
                      </vs-td>                  
                      <vs-td :data="data[indextrdoc].url">
                        <a style="cursor: pointer;" rel="nofollow" @click="downloadDocument(data[indextrdoc].id, data[indextrdoc].name)">Descargar</a>                        
                      </vs-td>

                    </vs-tr>
                  </template>
                </vs-table>

          </vs-tab>
        </vs-tabs>
          </vs-popup>
          <!-- FIN DOCUMENTOS -->
          
    <!-- POP UP -->
    <vs-popup
      class="holamundo"
      ref="modal"
      :title="(modoEditar == false ? 'AGREGAR ASOCIADO' : 'ACTUALIZAR ASOCIADO')"
      :active.sync="popupActive"
      @close="$close($event)"
    >
      <div class="mt-1">
          <div class="p-2">
              <div>
                <vs-divider color="primary"><h5>Asociado</h5></vs-divider>
              </div>  
                <div class="vx-row">
                   <div class="vx-col md:w-auto mt-2">
                      <vs-checkbox name="conductor"  icon-pack="feather" icon="icon-check" v-model="item.conductor">Conductor</vs-checkbox>
                    </div>
                    <div class="vx-col md:w-1/2 w-full mt-2">
                    </div>
                    <div class="vx-col md:w-1/2 w-full mt-2">
                      <vs-input
                        label-placeholder="Nombres"
                        v-model="item.name"
                        class="w-full"
                        name="name"
                        v-validate="'required'"
                        :danger="(errors.first('name') ? true : false)"
                        val-icon-danger="clear"
                      />
                      <span class="text-danger">{{ errors.first('name') }}</span>
                    </div>
                    <div class="vx-col md:w-1/2 w-full mt-2">
                      <vs-input
                        label-placeholder="Apellidos"
                        v-model="item.lastname"
                        class="w-full"
                        name="lastname"
                        v-validate="'required'"
                        :danger="(errors.first('lastname') ? true : false)"
                        val-icon-danger="clear"
                      />
                      <span class="text-danger">{{ errors.first('lastname') }}</span>
                    </div>
                    <div class="vx-col md:w-1/2 w-full mt-2">
                      <vs-input
                        type="Email"
                        label-placeholder="Email"
                        v-model="item.email"
                        class="w-full"
                        name="email"
                        v-validate="'required|email'"
                        :danger="(errors.first('email') ? true : false)"
                        val-icon-danger="clear"
                      />
                      <span class="text-danger">{{ errors.first('email') }}</span>
                    </div>
                    <div class="vx-col md:w-1/2 w-full mt-2">
                      <vs-input
                        type="Telefono"
                        label-placeholder="Telefono"
                        v-model="item.telefono"
                        class="w-full"
                        name="telefono"
                        v-validate="'required|numeric'"
                        :danger="(errors.first('telefono') ? true : false)"
                        val-icon-danger="clear"
                      />
                      <span class="text-danger">{{ errors.first('telefono') }}</span>
                    </div>
                    <div class="vx-col md:w-1/2 w-full mt-2">
                      <vs-input
                        label-placeholder="Rut"
                        v-model="item.rut"
                        class="w-full"
                        name="rut"
                        v-validate="'required|alpha_dash'"
                        :danger="(errors.first('rut') ? true : false)"
                        val-icon-danger="clear"
                      />
                      <span class="text-danger">{{ errors.first('rut') }}</span>
                    </div>
                    <div class="vx-col md:w-1/2 w-full mt-2">
                      <vs-input
                        label-placeholder="Ciudad"
                        v-model="item.ciudad"
                        class="w-full"
                        name="ciudad"
                        v-validate="'required'"
                        :danger="(errors.first('ciudad') ? true : false)"
                        val-icon-danger="clear"
                      />
                      <span class="text-danger">{{ errors.first('ciudad') }}</span>
                    </div>
                    <div class="vx-col md:w-1/2 w-full mt-2">
                      <vs-input
                        label-placeholder="Comuna"
                        v-model="item.comuna"
                        class="w-full"
                        name="comuna"
                        v-validate="'required'"
                        :danger="(errors.first('comuna') ? true : false)"
                        val-icon-danger="clear"
                      />
                      <span class="text-danger">{{ errors.first('comuna') }}</span>
                    </div>
                    <div class="vx-col md:w-1/2 w-full mt-2">
                      <vs-input
                        label-placeholder="Dirección"
                        v-model="item.direccion"
                        class="w-full"
                        name="direccion"
                        v-validate="'required'"
                        :danger="(errors.first('direccion') ? true : false)"
                        val-icon-danger="clear"
                      />
                      <span class="text-danger">{{ errors.first('direccion') }}</span>
                    </div>
                    <div class="vx-col md:w-1/2 w-full mt-2">
                      <vs-input
                        label-placeholder="Numeración"
                        v-model="item.numeracion"
                        class="w-full"
                        name="numeracion"
                        v-validate="'required|numeric'"
                        :danger="(errors.first('numeracion') ? true : false)"
                        val-icon-danger="clear"
                      />
                      <span class="text-danger">{{ errors.first('numeracion') }}</span>
                    </div>
                    <div class="vx-col md:w-1/2 w-full mt-2">
                      <vs-input
                        label-placeholder="Licencias"
                        v-model="item.clase"
                        class="w-full"
                        name="clase"
                        v-validate="'required'"
                        :danger="(errors.first('clase') ? true : false)"
                        val-icon-danger="clear"
                      />
                      <span class="text-danger">{{ errors.first('clase') }}</span>
                    </div>
                   
                    
                     <div class="vx-col md:w-1/6 w-full mt-2">
                    <vs-radio color="success" class="mt-5"  v-model="item.habilitado" vs-value="1" >Activo</vs-radio>
                    </div>
                     <div class="vx-col md:w-1/6 w-full mt-2">
                    <vs-radio color="danger" class="mt-5" v-model="item.habilitado" vs-value="0" >Inactivo</vs-radio>
                    </div>
                  </div>


     
            <div class="flex flex-wrap items-center justify-center p-6 mt-2" slot="footer">
              <vs-button v-if="modoEditar == true" class="mr-3" @click.prevent="$submitActualizar()" >ACTUALIZAR ASOCIADO</vs-button>
              <vs-button v-else class="mr-3" @click.prevent="$submitAgregar()">AGREGAR ASOCIADO</vs-button>
              
           
              <vs-button type="border" color="danger" ref="btncancelar" @click.prevent="$cancelarPopUp()">CANCELAR</vs-button>
            </div>
            </div>
      </div>
    </vs-popup>
    <!-- POP UP -->

    <vs-table
      ref="table"
      multiple
      v-model="selected"
      pagination
      :max-items="itemsPerPage"
      search
      :data="items"
    >
      <div slot="header" class="flex flex-wrap-reverse items-center flex-grow justify-between">
        <div class="flex flex-wrap-reverse items-center">
          <!-- ACTION - DROPDOWN -->
          <vs-dropdown vs-trigger-click class="cursor-pointer mr-4 mb-4">
            <div
              class="p-4 shadow-drop rounded-lg d-theme-dark-bg cursor-pointer flex items-center justify-center text-lg font-medium w-32"
            >
              <span class="mr-2">Acciones</span>
              <feather-icon icon="ChevronDownIcon" svgClasses="h-4 w-4" />
            </div>

            <vs-dropdown-menu>
              <vs-dropdown-item @click.prevent="$accion(1)">
                <span>Borrar</span>
              </vs-dropdown-item>
              <vs-dropdown-item @click.prevent="$accion(2)">
                <span>Exportar</span>
              </vs-dropdown-item>
              <!--<vs-dropdown-item @click.prevent="accion(3)">
                <span>Imprimir</span>
              </vs-dropdown-item>
               <vs-dropdown-item>
                <span>Another Action</span>
              </vs-dropdown-item>-->
            </vs-dropdown-menu>
          </vs-dropdown>

          <!-- ADD NEW -->
          <div
            class="p-3 mb-4 mr-4 rounded-lg cursor-pointer flex items-center justify-between text-lg font-medium text-base text-primary border border-solid border-primary"
            @click="$agregarPopUp()"
          >
            <!-- @click="addNewDataSidebar = true" -->
            <feather-icon icon="PlusIcon" svgClasses="h-4 w-4" />
            <vx-tooltip color="primary" text="Agregar asociado">
              <span class="ml-2 text-base text-primary">Agregar Asociado</span>
            </vx-tooltip>
          </div>

        </div>

        <!-- ITEMS PER PAGE -->
        <vs-dropdown vs-trigger-click class="cursor-pointer mb-4 mr-4">
          <div
            class="p-4 border border-solid d-theme-border-grey-light rounded-full d-theme-dark-bg cursor-pointer flex items-center justify-between font-medium"
          >
            <span
              class="mr-2"
            >{{ currentPage * itemsPerPage - (itemsPerPage - 1) }} - {{ items.length - currentPage * itemsPerPage > 0 ? currentPage * itemsPerPage : items.length }} of {{ items.length }}</span>
            <feather-icon icon="ChevronDownIcon" svgClasses="h-4 w-4" />
          </div>
          <!-- <vs-button class="btn-drop" type="line" color="primary" icon-pack="feather" icon="icon-chevron-down"></vs-button> -->
          <vs-dropdown-menu>
            <vs-dropdown-item @click="itemsPerPage=4">
              <span>4</span>
            </vs-dropdown-item>
            <vs-dropdown-item @click="itemsPerPage=10">
              <span>10</span>
            </vs-dropdown-item>
            <vs-dropdown-item @click="itemsPerPage=15">
              <span>15</span>
            </vs-dropdown-item>
            <vs-dropdown-item @click="itemsPerPage=20">
              <span>20</span>
            </vs-dropdown-item>
          </vs-dropdown-menu>
        </vs-dropdown>
      </div>

      <template slot="thead">
        <vs-th sort-key="items-id">ID</vs-th>
        <vs-th sort-key="items-nombre">Nombre</vs-th>
        <vs-th sort-key="items-apellido">Apellido</vs-th>
        <vs-th sort-key="items-rut">Rut</vs-th>
        <vs-th sort-key="items-telefono">Telefono</vs-th>
        <vs-th sort-key="items-moviles">Moviles</vs-th>
        <vs-th sort-key="items-conductores">Conductores</vs-th> 
        <vs-th sort-key="items-accion">Accion</vs-th>
      </template>

      <template slot-scope="{data}">
          <vs-tr :data="tr" :key="indextr" v-for="(tr, indextr) in data">
            <vs-td>
              <p class="items-id font-medium">{{ tr.id }}</p>
            </vs-td>
            <vs-td>
              <p class="items-nombre">{{ tr.name }}</p>
            </vs-td>
            <vs-td>
              <p class="items-apellido">{{ tr.lastname }}</p>
            </vs-td>
            <vs-td>
              <p class="items-rut">{{ tr.rut }}</p>
            </vs-td>
             <vs-td>
                <p class="items-telefono">{{ tr.telefono }}</p>
            </vs-td>
             <vs-td>
                <vs-chip :color="getStatusColorMoviles(tr.cars_count)" class="items-moviles">{{ tr.cars_count  }}</vs-chip>
            </vs-td>
             <vs-td>
                <vs-chip :color="getStatusColorConductores(tr.conductores_count)" class="items-conductores">{{ tr.conductores_count  }}</vs-chip>
            </vs-td>
            <vs-td>
              <div class="flex vx-col w-full sm:w-auto ml-auto mt-2 sm:mt-0">
                <vx-tooltip color="primary" text="Editar">
                  <vs-button
                    radius
                    color="primary"
                    type="border"
                    icon-pack="feather"
                    icon="icon-edit-2"
                    size="small"
                    class="ml-3"
                    @click="editar(tr)"
                  ></vs-button>
                </vx-tooltip>
                <vx-tooltip color="primary" text="Eliminar">
                  <vs-button
                    radius
                    color="primary"
                    type="border"
                    icon-pack="feather"
                    icon="icon-trash"
                    size="small"
                    class="ml-3"
                    @click="$submitEliminar(tr, indextr)"
                  ></vs-button>
                </vx-tooltip>
                 <vx-tooltip color="primary" text="Documentos">
                  <vs-button
                    radius
                    color="primary"
                    type="border"
                    icon-pack="feather"
                    icon="icon-file" 
                    size="small"
                    class="ml-3"
                    @click="initUpload(tr)"
                  ></vs-button>
                </vx-tooltip>
              </div>
            </vs-td>
          </vs-tr>
      </template>
    </vs-table>
  </div>
</template>

<script>
// For custom error message
import { Validator } from "vee-validate";
import flatPickr from 'vue-flatpickr-component';
const dict = {
  custom: {
    name: {
      required: "El nombre es requerido",
      alpha: "El nombre solo puede contener letras"
    },
    lastname: {
      required: "El apellido es requerido",
      alpha: "El apellido solo puede contener letras"
    },
    rut: {
      required: "El rut es requerido",
      alpha_dash: "Ingrese un rut valido"
    },
    ciudad: {
      required: "La ciudad es requerida",
      email: "Ingrese una ciudad valida"
    },
    comuna: {
      required: "La comuna es requerida",
      email: "Ingrese una comuna valida"
    },
    direccion: {
      required: "La dirección es requerida",
      email: "Ingrese una dirección valida"
    },
    numeracion: {
      required: "La numeración es requerida",
      numeric: "Ingrese una numeración valida"
    },
    email: {
      required: "El email es requerido",
      email: "Ingrese un emil valido"
    },
    telefono: {
      required: "El telefono es requerido",
      numeric: "El numero de telefono debe ser valido"
    },
     clase: {
      required: "La clase es requerida",
    }
  }
};

// register custom messages
Validator.localize("en", dict);

export default {
  components: {
    flatPickr
  },
  data() {
    return {
      ruta: "/asociado/asociado/",
      selected: [],
      items: [],
      itemsOriginal: [],
      itemsPerPage: 4,
      isMounted: false,
      ite: "",
      ind: "",
      popupActive: false,
      popupDocumento: false,  
      item: {
        habilitado: 1,
        conductor: false,
        dueno: true,
        driver_default:false
      },
      modoEditar: false,
      exportData: [],
      tipodocumentos_choices: [],      
      documentos_choices: [],
      dataItem:{},
      itemDoc: {}
    };
  },
  computed: {
    currentPage() {
      if (this.isMounted) {
        return this.$refs.table.currentx;
      }
      return 0;
    }
  },
  methods: {
    getStatusColorMoviles(cantidad) {
      if(cantidad > 0) return "success"
      if(cantidad == 0) return "danger"
      return "danger"
    },
    getStatusColorConductores(cantidad) {
      if(cantidad > 0) return "success"
      if(cantidad == 0) return "danger"
      return "danger"
    },
    editar(item) {
     
      this.initValues();
      this.modoEditar = true;
      this.item.id = item.id;
      this.item.email = item.email;
      this.item.name = item.name;
      this.item.lastname = item.lastname;
      this.item.rut = item.rut;
      this.item.telefono = item.telefono;
      this.item.habilitado = item.habilitado;
      this.item.ciudad = item.ciudad;
      this.item.comuna = item.comuna;
      this.item.direccion = item.direccion;
      this.item.numeracion = item.numeracion;
      this.item.clase = item.clase;
      this.item.conductor = item.conductor;
      this.item.dueno = item.dueno;
      this.item.driver_default = item.driver_default;

      this.popupActive = true;
    },
    initValues() {
      //this.$refs.wizard.navigateToTab(0);
      this.item = {
        habilitado: 1,
        conductor: false,
        dueno: true,
        driver_default:false
      };
      this.errors.clear();
      //this.modoEditar = false;
    },
    initUpload(item) {   
      const thisIns = this; 

      this.documentos_choices = [];
      
      this.itemDoc.tipo_documento = "";
      this.itemDoc.fecha_vencimiento = ""; 
      this.itemDoc.file = ""; 
      this.itemDoc.filename = ""; 
      
      //const input = this.$refs.fileupload;
      //input.type = 'file';
      //input.type = 'text';
      let myElement = document.querySelector(".line-vs-tabs");
      myElement.style.width = "95px";
      myElement.style.left = "0px";

      this.$refs.tabdocs.activeChild(0);
      this.$refs.tabdocs.changePositionLine(0);

      this.errors.clear(); 
      
      this.$http.get('asociado/asociado/documents/' + item.id)
          .then(function (response) {
            thisIns.documentos_choices = response.data.items;            
          })
          .catch(function (error) {
            thisIns.$vs.notify({
              title:'Error',
              text: "Error al traer los documentos",
              color:'danger',
              iconPack: 'feather',
              icon:'icon-alert-circle'})
      });      

       setTimeout(() => {
                
                this.popupDocumento = true;
                thisIns.dataItem = item;   
                thisIns.$refs.fileupload.value = '';

                }, 300);


      
    },

    upload($name = null){
      $name = $name == null ? true : $name;
      
       this.$validator.validateAll($name).then(result =>{
         
        if (result) { 
               
          const formData = new FormData();     
          formData.append('file', (this.itemDoc.file));
          formData.append('tipo_documento_id', (this.itemDoc.tipo_documento.split("|")[0])); 
          formData.append('tipo_documento', (this.itemDoc.tipo_documento.split("|")[1])); 
          formData.append('fecha_vencimiento', (this.itemDoc.fecha_vencimiento));  
          formData.append('driver_id', (this.dataItem.id));
          formData.append('rut', (this.dataItem.rut));
          formData.append('id', (this.dataItem.id));
          
          this.$upload(formData);
                     
        } else {
        }
      })
      
    },
   uploadData(e) {
      
      const tipo = e.target.files[0].type;
      const size = e.target.files[0].size;
     if(tipo == "image/png" || tipo == "image/jpeg" || tipo == "application/msword" || 
      tipo == "application/pdf" ){
        //|| tipo == "application/vnd.openxmlformats-officedocument.wordprocessingml.document"
        if(size <= 2000000){ //2097152
            
            this.itemDoc.file = e.target.files[0];
            this.itemDoc.filename = e.target.files[0].name;
            
            }else{
              this.$refs.fileupload.value = ''
              //const input = this.$refs.fileupload;
              //input.type = 'text';
              //input.type = 'file';

            this.$vs.notify({
                title: "Error",
                text: "El archivo no tiene el tamañano adecuado (Max. 2 MB)",
                color: "danger",
                iconPack: "feather",
                icon: "icon-alert-circle"
            });


          }
      }else{
          this.$refs.fileupload.value = '';
          //const input = this.$refs.fileupload;
          //input.type = 'text';
          //input.type = 'file';

          this.$vs.notify({
            title: "Error",
            text: "El archivo no tiene el formato correcto",
            color: "danger",
            iconPack: "feather",
            icon: "icon-alert-circle"
          });

      }
    },
    downloadDocument(id, name){
      //var download = await this.$http.get('driver/driver/document/' + id);
      //console.log(download);
      this.$http.get('asociado/asociado/document/'+id, {responseType: 'blob'}).then(response => {
            var a = document.createElement('a');
            var url = window.URL.createObjectURL(response.data);
            a.href = url;
            a.download = name;
            document.body.append(a);
            a.click();
            a.remove();
            window.URL.revokeObjectURL(url);
      }, response => {
        console.warn('error from download_contract');
        console.log(response);
        // Manage errors
        }
      );
    },
     getStatusColor(fecha) {
      var factual = new Date();
      var fvencimiento = new Date(fecha);  
      if (fvencimiento.getTime() >= factual.getTime()) return "success";
      if (fvencimiento.getTime() <= factual.getTime()) return "danger";
      return "danger";
    }, 
     refrescaOtrosDatos() {
      //Carga Tipos de documentos
      const thisIns  = this;
      this.$http
        .get("tipodocumentos/tipodocumentos/2")
        .then(function(response) {
          thisIns.tipodocumentos_choices = response.data.items;
        })
        .catch(function(error) {
          thisIns.$msjError(error);    
        });   

    }
  },
  created() {
    this.$refrescaTabla();
    this.refrescaOtrosDatos();
  },
  mounted() {
    this.isMounted = true;
  }
};
</script>

<style lang="scss">
#data-list-list-view {
  .vs-con-table {
    .vs-table--header {
      display: flex;
      flex-wrap: wrap-reverse;
      margin-left: 1.5rem;
      margin-right: 1.5rem;
      > span {
        display: flex;
        flex-grow: 1;
      }

      .vs-table--search {
        padding-top: 0;

        .vs-table--search-input {
          padding: 0.9rem 2.5rem;
          font-size: 1rem;

          & + i {
            left: 1rem;
          }

          &:focus + i {
            left: 1rem;
          }
        }
      }
    }

    .vs-table {
      border-collapse: separate;
      border-spacing: 0 1.3rem;
      padding: 0 1rem;

      tr {
        box-shadow: 0 4px 20px 0 rgba(0, 0, 0, 0.05);
        td {
          padding: 20px;
          &:first-child {
            border-top-left-radius: 0.5rem;
            border-bottom-left-radius: 0.5rem;
          }
          &:last-child {
            border-top-right-radius: 0.5rem;
            border-bottom-right-radius: 0.5rem;
          }
        }
        td.td-check {
          padding: 20px !important;
        }
      }
    }

    .vs-table--thead {
      th {
        padding-top: 0;
        padding-bottom: 0;

        .vs-table-text {
          text-transform: uppercase;
          font-weight: 600;
        }
      }
      th.td-check {
        padding: 0 15px !important;
      }
      tr {
        background: none;
        box-shadow: none;
      }
    }

    .vs-table--pagination {
      justify-content: center;
    }
  }
}
</style>

