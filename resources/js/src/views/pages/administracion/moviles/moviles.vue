<template>
  <div id="data-list-list-view" class="data-list-container">
  
      <!-- DOCUMENTOS -->
          <vs-popup class="holamundo"  title="Documentos Moviles" :active.sync="popupDocumento"
          @close="$close($event)">   
         
          <vs-tabs color="primary" ref="tabdocs" >
          <vs-tab label="Adjuntar"  icon-pack="feather" icon="icon-upload">
    
         
          <div class="vx-row">

              
              <div class="vx-col md:w-1/2 w-full mt-2">
                <vs-select v-model="itemDoc.tipo_documento" v-validate="'required'" label="Tipo de Documento" 
                name="tipo_documento" class="w-full"  data-vv-scope="docs">
                  <vs-select-item :key="item.id" :value="item.id+'|'+item.name" :text="item.name" v-for="item in tipodocumentos_choices"  />
                </vs-select>
                <span class="text-danger text-sm" v-show="errors.has('docs.tipo_documento')">{{ errors.first('docs.tipo_documento') }}</span> 
              </div>
              <div class="vx-col md:w-1/2 w-full mt-3">
                <flat-pickr v-model="itemDoc.fecha_vencimiento" v-validate="'required'" label="Fecha de Vencimiento" class="w-full select-large mt-5" 
                placeholder="Fecha de Vencimiento" name="fecha_vencimiento"  data-vv-scope="docs"/>
                <span class="text-danger text-sm" >{{ errors.first('docs.fecha_vencimiento') }}</span>                         
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
                        {{ trdoc.documents[0].name.split(/[.,\/-]/)[1] }}
                      </vs-td>
                      <vs-td>
                        <vs-chip :color="getStatusColor(trdoc.documents[0].fecha_vencimiento)">{{ trdoc.documents[0].fecha_vencimiento }}</vs-chip>
                      </vs-td>                  
                      <vs-td :data="data[indextrdoc].url">
                        <a style="cursor: pointer;" rel="nofollow" @click="downloadDocument(data[indextrdoc].documents[0].id, data[indextrdoc].documents[0].name)">Descargar</a>                        
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
      :title="(modoEditar == false ? 'AGREGAR MOVIL' : 'ACTUALIZAR MOVIL')"
      :active.sync="popupActive"
      @close="$close($event)"
    >
      <div class="mt-1">
          <div class="p-2">
              <div>
                <vs-divider color="primary"><h5>Moviles</h5></vs-divider>
              </div>
            
               <div class="vx-row">
                 <div class="vx-col md:w-1/2 w-full mt-2">
                      <vs-select v-model="item.driver_id" label="Asociados" name="asociados" class="w-full" v-validate="'required'" 
                      >
                        <vs-select-item :key="item.id" :value="item.id" :text="item.name" v-for="item in driver_choices"  />
                     </vs-select>
                      <span class="text-danger">{{ errors.first('asociados') }}</span>
                    </div>
                   <div class="vx-col md:w-1/2 w-full mt-2">
                      <vs-select v-model="item.empresa_id" label="Empresa" name="empresas" class="w-full" v-validate="'required'" >
                        <vs-select-item :key="item.id" :value="item.id" :text="item.razon_social" v-for="item in empresa_choices"  />
                      </vs-select>
                      <span class="text-danger text-sm" v-show="errors.has('empresas')">{{ errors.first('empresas') }}</span>          
                    </div>
                   <div class="vx-col md:w-1/2 w-full mt-2">
                      <vs-input
                        label-placeholder="N° Movil"
                        v-model="item.numero_movil"
                        class="w-full"
                        name="numero_movil"
                        v-validate="'required|numeric'"
                        :danger="(errors.first('numero_movil') ? true : false)"
                        val-icon-danger="clear"
                      />
                      <span class="text-danger">{{ errors.first('numero_movil') }}</span>
                    </div>
                    <div class="vx-col md:w-1/2 w-full mt-2">
                      <vs-input
                        label-placeholder="Tipo Vehículo"
                        v-model="item.tipo"
                        class="w-full"
                        name="tipo"
                        v-validate="'required'"
                        :danger="(errors.first('tipo') ? true : false)"
                        val-icon-danger="clear"
                      />
                      <span class="text-danger">{{ errors.first('tipo') }}</span>
                    </div>
                    <div class="vx-col md:w-1/2 w-full mt-2">
                      <vs-input
                        label-placeholder="Marca"
                        v-model="item.marca"
                        class="w-full"
                        name="marca"
                        v-validate="'required'"
                        :danger="(errors.first('marca') ? true : false)"
                        val-icon-danger="clear"
                      />
                      <span class="text-danger">{{ errors.first('marca') }}</span>
                    </div>
                    <div class="vx-col md:w-1/2 w-full mt-2">
                      <vs-input
                        label-placeholder="Modelo"
                        v-model="item.modelo"
                        class="w-full"
                        name="modelo"
                        v-validate="'required'"
                        :danger="(errors.first('modelo') ? true : false)"
                        val-icon-danger="clear"
                      />
                      <span class="text-danger">{{ errors.first('modelo') }}</span>
                    </div>
                    <div class="vx-col md:w-1/2 w-full mt-2">
                      <vs-input
                        label-placeholder="Año"
                        v-model="item.ano"
                        class="w-full"
                        name="ano"
                        v-validate="'required|numeric'"
                        :danger="(errors.first('ano') ? true : false)"
                        val-icon-danger="clear"
                      />
                      <span class="text-danger">{{ errors.first('ano') }}</span>
                    </div>
                    <div class="vx-col md:w-1/2 w-full mt-2">
                      <vs-input
                        label-placeholder="N° Motor"
                        v-model="item.motor"
                        class="w-full"
                        name="motor"
                        v-validate="'required|numeric'"
                        :danger="(errors.first('motor') ? true : false)"
                        val-icon-danger="clear"
                      />
                      <span class="text-danger">{{ errors.first('motor') }}</span>
                    </div>
                    <div class="vx-col md:w-1/2 w-full mt-2">
                      <vs-input
                        label-placeholder="N° Patente"
                        v-model="item.patente"
                        class="w-full"
                        name="patente"
                        v-validate="'required'"
                        :danger="(errors.first('patente') ? true : false)"
                        val-icon-danger="clear"
                      />
                      <span class="text-danger">{{ errors.first('patente') }}</span>
                    </div>
                    <div class="vx-col md:w-1/2 w-full mt-2">
                      <vs-input
                        label-placeholder="Color"
                        v-model="item.color"
                        class="w-full"
                        name="color"
                        v-validate="'required'"
                        :danger="(errors.first('color') ? true : false)"
                        val-icon-danger="clear"
                      />
                      <span class="text-danger">{{ errors.first('color') }}</span>
                    </div>
                    <div class="vx-col md:w-1/2 w-full mt-2">
                      <span class=" text-sm" >N° Asientos</span>
                      <vs-input-number v-model="item.asientos" 
                        name="asientos" v-validate="'required'"  min="1" 
                        icon-inc="expand_less" icon-dec="expand_more" />

                    </div>
                   <div class="vx-col md:w-1/6 w-full mt-5">
                    <vs-radio color="success" class="mt-5"  v-model="item.habilitado" vs-value="1" >Activo</vs-radio>
                    </div>
                     <div class="vx-col md:w-1/6 w-full mt-5">
                    <vs-radio color="danger" class="mt-5" v-model="item.habilitado" vs-value="0" >Inactivo</vs-radio>
                    </div>
                  </div>


     
            <div class="flex flex-wrap items-center justify-center p-6 mt-2" slot="footer">
              <vs-button v-if="modoEditar == true" class="mr-3" @click.prevent="$submitActualizar()" >ACTUALIZAR MOVIL</vs-button>
              <vs-button v-else class="mr-3" @click.prevent="$submitAgregar()">AGREGAR MOVIL</vs-button>
              
           
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
            <vx-tooltip color="primary" text="Agregar Movil">
              <span class="ml-2 text-base text-primary">Agregar Movil</span>
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
        <vs-th sort-key="items-movil">N° Movil</vs-th>
        <vs-th sort-key="items-patente">Patente</vs-th>
        <vs-th sort-key="items-asientos">N° Asientos</vs-th>
        <vs-th sort-key="items-asociado">Asociado</vs-th>
        <!-- <vs-th sort-key="items-asociado">Asociado</vs-th> -->
        <vs-th sort-key="items-accion">Accion</vs-th>
      </template>

      <template slot-scope="{data}">
       
          <vs-tr :data="tr" :key="indextr" v-for="(tr, indextr) in data">
            <vs-td>
              <p class="items-id font-medium">{{ tr.id }}</p>
            </vs-td>

            <vs-td>
              <p class="items-movil">{{ tr.numero_movil }}</p>
            </vs-td>
            <vs-td>
              <p class="items-patente">{{ tr.patente }}</p>
            </vs-td>
            <vs-td>
                <p class="items-asientos">{{ tr.asientos }}</p>
            </vs-td>
             <vs-td>
              <!--  <p class="items-asociado">{{ tr.drivers[0].name  }} {{ tr.drivers[0].apellido }}</p>-->
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
    tipo: {
      required: "El tipo es requerido"
    },
    marca: {
      required: "La marca es requerida"
    },
     modelo: {
       required: "El modelo es requerido"
     },
     ano: {
       required: "El año es requerido",
       numeric: "El año debe ser numerico"
     },
     motor: {
       required: "El motor es requerido",
       numeric: "El n° de motor debe ser numerico"
     },
     patente: {
       required: "La patente es requerida"
     },
     color: {
       required: "El color es requerido"
     },
     asientos: {
       required: "Los asientos son requerido",
     },
     numero_movil: {
       required: "Los asientos son requerido",
       numeric: "El n° de movil debe ser numerico"
     },
     asociados: {
       required: "El asociado es requerido"
     },
     empresas: {
       required: "La empresa es requerida"
     },
      tipo_documento: {
      required: "El tipo de documento es requerido",
    }, 
    fecha_vencimiento: {
      required: "La fecha de vencimiento es requerida",
    },
    documents: {
      required: "El documento es requerido",
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
      ruta: "/car/car/",
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
        asientos: 1,
      },
      modoEditar: false,
      exportData: [],
      driver_choices: [],
      empresa_choices: [],

      tipodocumentos_choices: [],      
      aux: 0,
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
    getStatusColor(fecha) {
      var factual = new Date();
      var fvencimiento = new Date(fecha);  
      if (fvencimiento.getTime() >= factual.getTime()) return "success";
      if (fvencimiento.getTime() <= factual.getTime()) return "danger";
      return "danger";
    },
    refrescaOtrosDatos() {
      //Carga Asociados
      const thisIns  = this;
      this.$http
        .get("asociado/asociado")
        .then(function(response) {
          thisIns.driver_choices = response.data.items; //thisIns.formatData(response.data.users) formatear data
        })
        .catch(function(error) {
          
         thisIns.$msjError(error);           
        });
      //Carga Tipos de documentos
      this.$http
        .get("tipodocumentos/tipodocumentos/0")
        .then(function(response) {
          thisIns.tipodocumentos_choices = response.data.items;
        })
        .catch(function(error) {
          thisIns.$msjError(error);    
        });
        
    //Carga Empresas
      this.$http
        .get("empresas/listemp/")
        .then(function(response) {
          thisIns.empresa_choices = response.data.items;
        })
        .catch(function(error) {
          thisIns.$msjError(error);    
        });

    },
    editar(item) {
     
      this.initValues();
      this.modoEditar = true;

      this.item.tipo = item.tipo;
      this.item.asientos = item.asientos;
      this.item.color = item.color;
      this.item.marca = item.marca;
      this.item.modelo = item.modelo;
      this.item.motor = item.motor;
      this.item.patente = item.patente;
      this.item.ano = item.ano;
      this.item.numero_movil = item.numero_movil;
      this.item.id = item.id;
      this.item.empresa_id = item.empresa_id;
      this.item.driver_id = item.asociados[0].driver_id;


      this.popupActive = true;
    },
    initValues() {
      //this.$refs.wizard.navigateToTab(0);
      this.item = {
        habilitado: 1,
        asientos: 1,
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
      
      this.$http.get('car/car/documents/' + item.id)
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
          formData.append('car_id', (this.dataItem.id));
          formData.append('numero_movil', (this.dataItem.numero_movil));
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
      this.$http.get('car/car/document/'+id, {responseType: 'blob'}).then(response => {
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

