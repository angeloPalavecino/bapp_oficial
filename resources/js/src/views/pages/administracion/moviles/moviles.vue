<template>
  <div id="data-list-list-view" class="data-list-container">

              <!-- DOCUEMTOS -->
          <vs-popup class="holamundo"  title="Documentos Conductor" :active.sync="popupDocumento"
          @close="$close($event)">   
         
          <vs-tabs color="primary" ref="tabdocs" >
          <vs-tab label="Adjuntar"  icon-pack="feather" icon="icon-upload">
    
         
          <div class="vx-row">

              
              <div class="vx-col md:w-1/2 w-full mt-2">
                <vs-select v-model="item.tipo_documento" v-validate="'required'" label="Tipo de Documento" name="tipo_documento" class="w-full"  >
                  <vs-select-item :key="item.id" :value="item.id+'|'+item.name" :text="item.name" v-for="item in tipodocumentos_choices"  />
                </vs-select>
                <span class="text-danger text-sm" v-show="errors.has('tipo_documento')">{{ errors.first('tipo_documento') }}</span> 
              </div>
              <div class="vx-col md:w-1/2 w-full mt-3">
                <flat-pickr v-model="item.fecha_vencimiento" v-validate="'required'" label="Fecha de Vencimiento" class="w-full select-large mt-5" placeholder="Fecha de Vencimiento" name="fecha_vencimiento"  />
                <span class="text-danger text-sm" >{{ errors.first('fecha_vencimiento') }}</span>                         
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
                />
                <span class="text-sm" >Fomatos permitidos: JPG - PNG - DOC - DOCX - PDF</span>
                <span class="text-sm" ><i>Tamaño maximo 2 MB</i></span>
              
              </div>
              <div class="vx-col md:w-1/2 w-full mt-5">
                <vs-button @click="upload()" color="primary" type="filled">Adjuntar</vs-button>
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
          :title="(modoEditar == false ? 'AGREGAR CONDUCTOR' : 'ACTUALIZAR CONDUCTOR')"
          :active.sync="popupActive"
          @close="$close($event)"
        >
          <div class="mt-5">
            <form-wizard
              color="rgba(var(--vs-primary), 1)"
              errorColor="rgba(var(--vs-danger), 1)"
              :title="(modoEditar == false ? 'AGREGAR CONDUCTOR' : 'ACTUALIZAR CONDUCTOR')"
              :subtitle="(modoEditar == false ? 'Ingrese todos los campos para ingresar el conductor' : 'Modifique los campos que desee actualizar')"
              :finishButtonText="(modoEditar == false ? 'Agregar' : 'Actualizar')"
              ref="wizard"
            >
              <!-- tab 1 content -->
              <tab-content
                title="Datos Conductor"
                class="mb-5"
                icon="feather icon-user"
                :before-change="validateStep1"
              >
                <form data-vv-scope="step-1">
                  <div>
                    <vs-divider color="primary">
                      <h5>Datos Conductor</h5>
                    </vs-divider>
                  </div>
                  <div class="vx-row">
                    <div class="vx-col md:w-1/2 w-full mt-2">
                      <vs-input
                        label-placeholder="Nombres"
                        v-model="user.name"
                        class="w-full"
                        name="name"
                        v-validate="'required'"
                        :danger="(errors.first('step-1.name') ? true : false)"
                        val-icon-danger="clear"
                      />
                      <span class="text-danger">{{ errors.first('step-1.name') }}</span>
                    </div>
                    <div class="vx-col md:w-1/2 w-full mt-2">
                      <vs-input
                        label-placeholder="Apellidos"
                        v-model="user.lastname"
                        class="w-full"
                        name="lastname"
                        v-validate="'required'"
                        :danger="(errors.first('step-1.lastname') ? true : false)"
                        val-icon-danger="clear"
                      />
                      <span class="text-danger">{{ errors.first('step-1.lastname') }}</span>
                    </div>
                    <div class="vx-col md:w-1/2 w-full mt-2">
                      <vs-input
                        type="Email"
                        label-placeholder="Email"
                        v-model="user.email"
                        class="w-full"
                        name="email"
                        v-validate="'required|email'"
                        :danger="(errors.first('step-1.email') ? true : false)"
                        val-icon-danger="clear"
                      />
                      <span class="text-danger">{{ errors.first('step-1.email') }}</span>
                    </div>
                    <div class="vx-col md:w-1/2 w-full mt-2">
                      <vs-input
                        type="Telefono"
                        label-placeholder="Telefono"
                        v-model="user.telefono"
                        class="w-full"
                        name="telefono"
                        v-validate="'required|numeric'"
                        :danger="(errors.first('step-1.telefono') ? true : false)"
                        val-icon-danger="clear"
                      />
                      <span class="text-danger">{{ errors.first('step-1.telefono') }}</span>
                    </div>
                    <div class="vx-col md:w-1/2 w-full mt-2">
                      <vs-input
                        label-placeholder="Rut"
                        v-model="user.rut"
                        class="w-full"
                        name="rut"
                        v-validate="'required|alpha_dash'"
                        :danger="(errors.first('step-1.rut') ? true : false)"
                        val-icon-danger="clear"
                      />
                      <span class="text-danger">{{ errors.first('step-1.rut') }}</span>
                    </div>
                    <div class="vx-col md:w-1/2 w-full mt-2">
                      <vs-input
                        label-placeholder="Ciudad"
                        v-model="driver.ciudad"
                        class="w-full"
                        name="ciudad"
                        v-validate="'required'"
                        :danger="(errors.first('step-1.ciudad') ? true : false)"
                        val-icon-danger="clear"
                      />
                      <span class="text-danger">{{ errors.first('step-1.ciudad') }}</span>
                    </div>
                    <div class="vx-col md:w-1/2 w-full mt-2">
                      <vs-input
                        label-placeholder="Comuna"
                        v-model="driver.comuna"
                        class="w-full"
                        name="comuna"
                        v-validate="'required'"
                        :danger="(errors.first('step-1.comuna') ? true : false)"
                        val-icon-danger="clear"
                      />
                      <span class="text-danger">{{ errors.first('step-1.comuna') }}</span>
                    </div>
                    <div class="vx-col md:w-1/2 w-full mt-2">
                      <vs-input
                        label-placeholder="Dirección"
                        v-model="driver.direccion"
                        class="w-full"
                        name="direccion"
                        v-validate="'required'"
                        :danger="(errors.first('step-1.direccion') ? true : false)"
                        val-icon-danger="clear"
                      />
                      <span class="text-danger">{{ errors.first('step-1.direccion') }}</span>
                    </div>
                    <div class="vx-col md:w-1/2 w-full mt-2">
                      <vs-input
                        label-placeholder="Numeración"
                        v-model="driver.numeracion"
                        class="w-full"
                        name="numeracion"
                        v-validate="'required|numeric'"
                        :danger="(errors.first('step-1.numeracion') ? true : false)"
                        val-icon-danger="clear"
                      />
                      <span class="text-danger">{{ errors.first('step-1.numeracion') }}</span>
                    </div>
                    <div class="vx-col md:w-1/2 w-full mt-1">
                      <vs-select v-model="driver.empresa_id" label="Empresa" name="empresa" class="w-full" v-validate="'required'" >
                        <vs-select-item :key="item.id" :value="item.id" :text="item.razon_social" v-for="item in empresa_choices"  />
                      </vs-select>
                      <span class="text-danger text-sm" v-show="errors.has('step-1.empresa')">{{ errors.first('step-1.empresa') }}</span>          
                    </div>
                    <div class="vx-col md:w-1/2 w-full mt-2">
                      <vs-input
                        label-placeholder="Licencias"
                        v-model="driver.clase"
                        class="w-full"
                        name="clase"
                        v-validate="'required'"
                        :danger="(errors.first('step-1.clase') ? true : false)"
                        val-icon-danger="clear"
                      />
                      <span class="text-danger">{{ errors.first('step-1.clase') }}</span>
                    </div>
                     <div class="vx-col md:w-1/6 w-full mt-5">
                    <vs-radio color="success" class="mt-5"  v-model="user.habilitado" vs-value="1" >Activo</vs-radio>
                    </div>
                     <div class="vx-col md:w-1/6 w-full mt-5">
                    <vs-radio color="danger" class="mt-5" v-model="user.habilitado" vs-value="0" >Inactivo</vs-radio>
                    </div>
                  </div>
                </form>
              </tab-content>

              <!-- tab 2 content -->
              <tab-content
                title="Datos Movil"
                class="mb-5"
                icon="feather icon-truck"
                :before-change="validateStep2"
              >
                <form data-vv-scope="step-2">
                  <div>
                    <vs-divider color="primary">
                      <h5>Datos Movil</h5>
                    </vs-divider>
                  </div>
                  <div class="vx-row">
                    <div class="vx-col md:w-1/2 w-full mt-2">
                      <vs-input
                        label-placeholder="Tipo Vehículo"
                        v-model="car.tipo"
                        class="w-full"
                        name="tipo"
                        v-validate="'required'"
                        :danger="(errors.first('step-2.tipo') ? true : false)"
                        val-icon-danger="clear"
                      />
                      <span class="text-danger">{{ errors.first('step-2.tipo') }}</span>
                    </div>
                    <div class="vx-col md:w-1/2 w-full mt-2">
                      <vs-input
                        label-placeholder="Marca"
                        v-model="car.marca"
                        class="w-full"
                        name="marca"
                        v-validate="'required'"
                        :danger="(errors.first('step-2.marca') ? true : false)"
                        val-icon-danger="clear"
                      />
                      <span class="text-danger">{{ errors.first('step-2.marca') }}</span>
                    </div>
                    <div class="vx-col md:w-1/2 w-full mt-2">
                      <vs-input
                        label-placeholder="Modelo"
                        v-model="car.modelo"
                        class="w-full"
                        name="modelo"
                        v-validate="'required'"
                        :danger="(errors.first('step-2.modelo') ? true : false)"
                        val-icon-danger="clear"
                      />
                      <span class="text-danger">{{ errors.first('step-2.modelo') }}</span>
                    </div>
                    <div class="vx-col md:w-1/2 w-full mt-2">
                      <vs-input
                        label-placeholder="Año"
                        v-model="car.ano"
                        class="w-full"
                        name="ano"
                        v-validate="'required'"
                        :danger="(errors.first('step-2.ano') ? true : false)"
                        val-icon-danger="clear"
                      />
                      <span class="text-danger">{{ errors.first('step-2.ano') }}</span>
                    </div>
                    <div class="vx-col md:w-1/2 w-full mt-2">
                      <vs-input
                        label-placeholder="N° Motor"
                        v-model="car.motor"
                        class="w-full"
                        name="motor"
                        v-validate="'required'"
                        :danger="(errors.first('step-2.motor') ? true : false)"
                        val-icon-danger="clear"
                      />
                      <span class="text-danger">{{ errors.first('step-2.motor') }}</span>
                    </div>
                    <div class="vx-col md:w-1/2 w-full mt-2">
                      <vs-input
                        label-placeholder="N° Patente"
                        v-model="car.patente"
                        class="w-full"
                        name="patente"
                        v-validate="'required'"
                        :danger="(errors.first('step-2.patente') ? true : false)"
                        val-icon-danger="clear"
                      />
                      <span class="text-danger">{{ errors.first('step-2.patente') }}</span>
                    </div>
                    <div class="vx-col md:w-1/2 w-full mt-2">
                      <vs-input
                        label-placeholder="Color"
                        v-model="car.color"
                        class="w-full"
                        name="color"
                        v-validate="'required'"
                        :danger="(errors.first('step-2.color') ? true : false)"
                        val-icon-danger="clear"
                      />
                      <span class="text-danger">{{ errors.first('step-2.color') }}</span>
                    </div>
                    <div class="vx-col md:w-1/2 w-full mt-2">
                      <vs-input
                        label-placeholder="N° Asientos"
                        v-model="car.asientos"
                        class="w-full"
                        name="asientos"
                        v-validate="'required|numeric'"
                        :danger="(errors.first('step-2.asientos') ? true : false)"
                        val-icon-danger="clear"
                      />
                      <span class="text-danger">{{ errors.first('step-2.asientos') }}</span>
                    </div>
                  </div>
                </form>
              </tab-content>
            </form-wizard>
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
            <vx-tooltip color="primary" text="Agregar conductor">
              <span class="ml-2 text-base text-primary">Agregar Conductor</span>
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
        <vs-th sort-key="items-name">Nombre</vs-th>
        <vs-th sort-key="items-lastname">Apellido</vs-th>
        <vs-th sort-key="items-email">Email</vs-th>
        <vs-th sort-key="items-accion">Accion</vs-th>
      </template>

      <template slot-scope="{data}">
        <tbody>
          <vs-tr :data="tr" :key="indextr" v-for="(tr, indextr) in data">
            <vs-td>
              <p class="items-id font-medium">{{ tr.id }}</p>
            </vs-td>

            <vs-td>
              <p class="items-name">{{ tr.name }}</p>
            </vs-td>
            <vs-td>
              <p class="items-lastname">{{ tr.lastname }}</p>
            </vs-td>
            <vs-td>
              <p class="items-email">{{ tr.email }}</p>
            </vs-td>
            <!--  <vs-td>
                <p class="users-created_at">{{ tr.created_at }}</p>
              </vs-td>

              <vs-td>
                <p class="users-updated_at">{{ tr.updated_at }}</p>
            </vs-td>-->
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
        </tbody>
      </template>
    </vs-table>
  </div>
</template>

<script>
import { FormWizard, TabContent } from "vue-form-wizard";
import "vue-form-wizard/dist/vue-form-wizard.min.css";
import flatPickr from 'vue-flatpickr-component';


// For custom error message
import { Validator } from "vee-validate";
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
    empresa: {
      required: "La empresa es requerida",
    },
    email: {
      required: "El email es requerido",
      email: "Ingrese un emil valido"
    },
    telefono: {
      required: "El telefono es requerido",
      numeric: "El numero de telefono debe ser valido"
    },
    tipo: {
      required: "El tipo es requerido"
    },
    tipoDocumento: {
      required: "El tipo de documento es requerido"
    },
    marca: {
      required: "La marca es requerida"
    },
    modelo: {
      required: "El modelo es requerido"
    },
    ano: {
      required: "El ano es requerido"
    },
    motor: {
      required: "El motor es requerido"
    },
    patente: {
      required: "La patente es requerida"
    },
    color: {
      required: "El color es requerido"
    },
    asientos: {
      required: "Los asientos son requerido",
      numeric: "La cantidad de asientos debe ser numerico"
    },
    clase: {
      required: "La clase es requerida",
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
    FormWizard,
    TabContent,
    flatPickr

  },
  data() {
    return {
      ruta: "/driver/driver/",
      selected: [],
      items: [],
      itemsPerPage: 4,
      isMounted: false,
      ite: "",
      ind: "",
      popupActive: false,
      popupDocumento: false,      
      item: {},
      driver: {},
      car: {},
      user: {
        habilitado:1,
      },
      modoEditar: false,
      exportData: [],
      empresa_choices: [],
      tipodocumentos_choices: [],      
      aux: 0,
      documentos_choices: [],

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
    validateStep1() {
      return new Promise((resolve, reject) => {
        this.$validator.validateAll("step-1").then(result => {
          if (result) {
            resolve(true);
          } else {
            reject("correct all values");
          }
        });
      });
    },
    validateStep2() {
      return new Promise((resolve, reject) => {
        this.$validator.validateAll("step-2").then(result => {
          if (result) {
            this.item.driver = this.driver;
            this.item.user = this.user;
            this.item.car = this.car;
            
            if (this.modoEditar == false) {
              this.$submitAgregar("step-2");
            } else {
              this.$submitActualizar("step-2");
            }

            resolve(true);
          } else {
            reject("correct all values");
          }
        });
      });
    },
    getStatusColor(fecha) {
      var factual = new Date();
      var fvencimiento = new Date(fecha);  
      if (fvencimiento.getTime() >= factual.getTime()) return "success";
      if (fvencimiento.getTime() <= factual.getTime()) return "danger";
      return "danger";
    },
    refrescaOtrosDatos() {
      //Carga Roles
      const thisIns = this;
      this.$http
        .get("roles/roles")
        .then(function(response) {
          thisIns.roles_choices = response.data.items; //thisIns.formatData(response.data.users) formatear data
        })
        .catch(function(error) {
          thisIns.$vs.notify({
            title: "Error",
            text: error,
            color: "danger",
            iconPack: "feather",
            icon: "icon-alert-circle"
          });
        });
      //Carga Empresa
      this.$http
        .get("empresas/empresas")
        .then(function(response) {
          thisIns.empresa_choices = response.data.items; //thisIns.formatData(response.data.users) formatear data
        })
        .catch(function(error) {
          thisIns.$vs.notify({
            title: "Error",
            text: error,
            color: "danger",
            iconPack: "feather",
            icon: "icon-alert-circle"
          });
        });
      
      //Charge Type Documents
      this.$http
        .get("tipodocumentos/tipodocumentos")
        .then(function(response) {
          thisIns.tipodocumentos_choices = response.data.items;
        })
        .catch(function(error) {
          thisIns.$vs.notify({
            title: "Error",
            text: error,
            color: "danger",
            iconPack: "feather",
            icon: "icon-alert-circle"
          });
        });   
           
    },
    editar(item) {
      //console.log(item);
      this.initValues();
      this.modoEditar = true;
      this.user.email = item.email;
      this.user.name = item.name;
      this.user.lastname = item.lastname;
      this.user.rut = item.rut;
      this.user.telefono = item.telefono;
      this.user.habilitado = item.habilitado;
      
      this.driver.ciudad = item.ciudad;
      this.driver.comuna = item.comuna;
      this.driver.direccion = item.direccion;
      this.driver.numeracion = item.numeracion;
      this.driver.empresa_id = item.empresa_id;
      this.driver.clase = item.clase;

      this.car.tipo = item.cars[0].tipo;
      this.car.asientos = item.cars[0].asientos;
      this.car.color = item.cars[0].color;
      this.car.marca = item.cars[0].marca;
      this.car.modelo = item.cars[0].modelo;
      this.car.motor = item.cars[0].motor;
      this.car.patente = item.cars[0].patente;
      this.car.ano = item.cars[0].ano;
      this.car.id = item.cars[0].id;

      this.user.id = item.id;

      this.popupActive = true;
    },
    initValues() {
      //this.$refs.wizard.navigateToTab(0);
      this.item = {};
      this.car = {};
      this.user = {
        habilitado:1,
      };
      this.driver = {};
      this.errors.clear();
      this.$refs.wizard.reset();
      //this.modoEditar = false;
    },
    initUpload(item) {   
      const thisIns = this; 

      this.documentos_choices = [];
      
      this.item.tipo_documento = "";
      this.item.fecha_vencimiento = ""; 
      this.item.file = ""; 
      this.item.filename = ""; 
      
      //const input = this.$refs.fileupload;
      //input.type = 'file';
      //input.type = 'text';
      let myElement = document.querySelector(".line-vs-tabs");
      myElement.style.width = "95px";
      myElement.style.left = "0px";

      this.$refs.tabdocs.activeChild(0);
      this.$refs.tabdocs.changePositionLine(0);

      this.errors.clear(); 
      
      this.$http.get('driver/driver/documents/' + item.id)
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
                this.dataItem = item;   
                thisIns.$refs.fileupload.value = '';

                }, 300);


      
    },

    upload($name = null){
      $name = $name == null ? true : $name;
       this.$validator.validateAll($name).then(result =>{
        if (result) {          
          const formData = new FormData();     
          formData.append('file', (this.item.file));
          formData.append('tipo_documento_id', (this.item.tipo_documento.split("|")[0])); 
          formData.append('tipo_documento', (this.item.tipo_documento.split("|")[1])); 
          formData.append('fecha_vencimiento', (this.item.fecha_vencimiento));  
          formData.append('driver_id', (this.dataItem.cars[0].driver_id));
          formData.append('rut', (this.dataItem.rut));
          this.$upload(formData);
                     
        } else {
        }
      })
      
    },
    uploadData(e) {
      
      const tipo = e.target.files[0].type;
      const size = e.target.files[0].size;
     if(tipo == "image/png" || tipo == "image/jpeg" || tipo == "application/msword" || 
      tipo == "application/pdf" || tipo == "application/vnd.openxmlformats-officedocument.wordprocessingml.document"){
        
        if(size <= 2000000){ //2097152
            
            this.item.file = e.target.files[0];
            this.item.filename = e.target.files[0].name;
            
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
      this.$http.get('driver/driver/document/'+id, {responseType: 'blob'}).then(response => {
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

