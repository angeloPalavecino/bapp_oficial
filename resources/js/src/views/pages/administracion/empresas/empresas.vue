<template>
  <div id="data-list-list-view" class="data-list-container">

  

    <vs-table ref="table" multiple v-model="selected" pagination :max-items="itemsPerPage" search :data="items">

      <div slot="header" class="flex flex-wrap-reverse items-center flex-grow justify-between">

        <div class="flex flex-wrap-reverse items-center">

          <!-- ACTION - DROPDOWN -->
          <vs-dropdown vs-trigger-click  class="cursor-pointer mr-4 mb-4">

            <div class="p-4 shadow-drop rounded-lg d-theme-dark-bg cursor-pointer flex items-center justify-center text-lg font-medium w-32">
              <span class="mr-2">Acciones</span>
              <feather-icon icon="ChevronDownIcon" svgClasses="h-4 w-4" />
            </div>

            <vs-dropdown-menu >
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
          <div class="p-3 mb-4 mr-4 rounded-lg cursor-pointer flex items-center justify-between text-lg font-medium text-base 
          text-primary border border-solid border-primary" @click="$agregarPopUp()" > <!-- @click="addNewDataSidebar = true" -->
              <feather-icon icon="PlusIcon" svgClasses="h-4 w-4" />
              <vx-tooltip color="primary" text="Agregar empresa">
              <span class="ml-2 text-base text-primary">Agregar Empresa</span>
              </vx-tooltip>
          </div>
        </div>

        <!-- POP UP -->
        <vs-popup class="holamundo"  ref="modal" :title="(modoEditar == false ? 'AGREGAR EMPRESA' : 'ACTUALIZAR EMPRESA')" 
         :active.sync="popupActive"  @close="$close($event)">
        <div class="mt-5">
      <form-wizard color="rgba(var(--vs-primary), 1)" errorColor="rgba(var(--vs-danger), 1)" 
      :title="(modoEditar == false ? 'AGREGAR EMPRESA' : 'ACTUALIZAR EMPRESA')" 
      :subtitle="(modoEditar == false ? 'Ingrese todos los campos para ingresar la empresa' : 'Modifique los campos que desee actualizar')" 
      :finishButtonText="(modoEditar == false ? 'Agregar' : 'Actualizar')" ref="wizard">
        <tab-content title="Paso 1" class="mb-5" icon="feather icon-home" :before-change="validateStep1">
          <!-- tab 1 content -->
          <form data-vv-scope="step-1">
           <div>
               <vs-divider color="primary"><h5>Datos comerciales</h5></vs-divider>
          </div>
          <div class="vx-row">
            <div class="vx-col md:w-1/2 w-full mt-2">
              <vs-input label-placeholder="Razon social" v-model="item.razon_social" class="w-full" name="razon_social" v-validate="'required'" 
             :danger="(errors.first('step-1.razon_social') ? true : false)" val-icon-danger="clear" />
              <span class="text-danger text-sm" >{{ errors.first('step-1.razon_social') }}</span>
            </div>
            <div class="vx-col md:w-1/2 w-full mt-2">
              <vs-input label-placeholder="Giro"  v-model="item.giro" class="w-full" name="giro" />
            </div>
            <div class="vx-col md:w-1/2 w-full mt-2">
              <vs-input label-placeholder="Rut"  v-model="item.rut" class="w-full" name="rut" v-validate="'required|numeric'" 
              :danger="(errors.first('step-1.rut') ? true : false)" val-icon-danger="clear" />
              <span class="text-danger text-sm" >{{ errors.first('step-1.rut') }}</span>
            </div>
            <div class="vx-col md:w-1/2 w-full mt-2">
                <vs-input  label-placeholder="Digito verificador"  v-model="item.dv" class="w-full md:w-1/2" name="dv" v-validate="'required|max:1'" 
                :danger="(errors.first('step-1.dv') ? true : false)" val-icon-danger="clear" />
              <span class="text-danger text-sm" >{{ errors.first('step-1.dv') }}</span>
            </div>
            </div>
             <div>
                <vs-divider color="primary"><h5>Tipo servicio</h5></vs-divider>
              </div>
            <div class="vx-row">
               <div class="vx-col md:w-1/2 w-full mt-2">
                <vs-select v-model="tiposervicio.tipo" class="w-full select-large" label="Tipo" name="tiposervicio_tipo" v-validate="'required'">
                      <vs-select-item :key="index" :value="item.value" :text="item.text" v-for="(item,index) in tipoOpciones" class="w-full" />
                  </vs-select>
               <span class="text-danger text-sm" >{{ errors.first('step-1.tiposervicio_tipo') }}</span>
            
              </div>
   
            <div class="vx-col md:w-1/2 w-full mt-2">
              <vs-input label-placeholder="Outsourcing" v-model="tiposervicio.outsourcing" class="w-full" name="tiposervicio_outsourcing" v-validate="'required'" 
             :danger="(errors.first('step-1.tiposervicio_outsourcing') ? true : false)" val-icon-danger="clear" />
              <span class="text-danger text-sm" >{{ errors.first('step-1.tiposervicio_outsourcing') }}</span>
            </div>

         
            <div class="vx-col md:w-1/2 w-full mt-2">
              <vs-input label-placeholder="Valor"  v-model="tiposervicio.valor" class="w-full" name="tiposervicio_valor" v-validate="'required|decimal'"
              :danger="(errors.first('step-1.tiposervicio_outsourcing') ? true : false)" val-icon-danger="clear"/>
              <span class="text-danger text-sm" >{{ errors.first('step-1.tiposervicio_valor') }}</span>
            </div>

          </div>
          </form>
        </tab-content>

        <!-- tab 2 content -->
        <tab-content title="Paso 2" class="mb-5" icon="feather icon-users" :before-change="validateStep2">
          <form data-vv-scope="step-2">
          <div>
               <vs-divider color="primary" ><h5>Responsables</h5></vs-divider>
              <div class="vx-col md:w-1/7 w-full mt-2">
                <vs-button color="primary" size="small" icon-pack="feather" icon="icon-user-plus" @click.prevent="agregarResponsable()"></vs-button>
              </div>      
          </div>
          <div :data="res" :key="indexres" v-for="(res, indexres) in responsables">
          <div class="vx-row" >
            <div class="vx-col md:w-1/3 w-full mt-2">
              <vs-input label-placeholder="Responsable"  v-model="res.name" class="w-full" name="responsable_nombre" v-validate="'required'" 
              :danger="(errors.first('step-2.responsable_nombre') ? true : false)" val-icon-danger="clear" size="small"/>
              <span class="text-danger text-sm" >{{ errors.first('step-2.responsable_nombre') }}</span>
            </div>
            <div class="vx-col md:w-1/4 w-full mt-2">
              <vs-input label-placeholder="Telefono"  v-model="res.telefono" class="w-full" name="responsable_telefono" v-validate="'required|numeric'" 
              :danger="(errors.first('step-2.responsable_telefono') ? true : false)" val-icon-danger="clear" size="small"/>
              <span class="text-danger text-sm" >{{ errors.first('step-2.responsable_telefono') }}</span>
            </div>
             <div class="vx-col md:w-1/3 w-full mt-2">
              <vs-input label-placeholder="Email"  v-model="res.email" class="w-full" name="responsable_email" v-validate="'required|email'" 
              :danger="(errors.first('step-2.responsable_email') ? true : false)" val-icon-danger="clear" size="small"/>
              <span class="text-danger text-sm" >{{ errors.first('step-2.responsable_email') }}</span>
            </div>
            <div class="mt-6  items-center">
                 <vs-button radius color="primary" type="border" icon-pack="feather" icon="icon-user-minus" size="small" 
                  @click.prevent="quitarResponsable(indexres)"></vs-button>
             
            </div>
          </div>
          </div>
          </form>
        </tab-content>
        <!-- tab 3 content -->
         <tab-content title="Paso 3" class="mb-5" icon="feather icon-map-pin" :before-change="validateStep3">
          <form data-vv-scope="step-3">
          <div>
               <vs-divider color="primary" ><h5>Sucursales</h5></vs-divider>
              <div class="vx-col md:w-1/7 w-full mt-2">
                <vs-button color="primary" size="small" icon-pack="feather" icon="icon-plus" @click.prevent="agregarSucursal()"></vs-button>
              </div>      
          </div>
          <div :data="suc" :key="indexsuc" v-for="(suc, indexsuc) in sucursales">
          <div class="vx-row" >
            <vs-divider color="primary" >Detalle</vs-divider>
            <div class="vx-col md:w-1/4 w-full mt-2">
                  <vs-input label-placeholder="Nombre"  v-model="suc.nombre" class="w-full" name="sucursal_nombre" v-validate="'required'" 
                :danger="(errors.first('step-3.sucursal_nombre') ? true : false)" val-icon-danger="clear" size="small"/>
                
              </div>
            <div class="vx-col md:w-1/4 w-full mt-2">
                <vs-input label-placeholder="Direccion"  v-model="suc.direccion" class="w-full" name="sucursal_direccion" v-validate="'required'" 
                :danger="(errors.first('step-3.sucursal_direccion') ? true : false)" val-icon-danger="clear" size="small"/>
                
              </div>
             <div class="vx-col md:w-1/6 w-full mt-6">
               <vs-radio v-model="suc.matriz" vs-value="1"  @change="foo($event.target.checked)">Matriz</vs-radio>
              </div>
            <div class="vx-col md:w-1/6 w-full mt-6">
                 <vs-button radius color="primary" type="border" icon-pack="feather" icon="icon-minus" size="small" 
                  @click.prevent="quitarSucursal(indexsuc)"></vs-button>   
            </div>
             <div class="vx-col md:w-1/3 w-full mt-2">
               <vs-input label-placeholder="Pais"  v-model="suc.pais" class="w-full" name="sucursal_pais" v-validate="'required'" 
                :danger="(errors.first('step-3.sucursal_pais') ? true : false)" val-icon-danger="clear" size="small"/>
                
              </div>
              <div class="vx-col md:w-1/3 w-full mt-2">
                 <vs-input label-placeholder="Ciudad"  v-model="suc.ciudad" class="w-full" name="sucursal_ciudad" v-validate="'required'" 
                :danger="(errors.first('step-3.sucursal_ciudad') ? true : false)" val-icon-danger="clear" size="small"/>
                
              </div>
              <div class="vx-col md:w-1/3 w-full mt-2">
                  <vs-input label-placeholder="Comuna"  v-model="suc.comuna" class="w-full" name="sucursal_comuna" v-validate="'required'" 
                :danger="(errors.first('step-3.sucursal_comuna') ? true : false)" val-icon-danger="clear" size="small"/>
             
              </div>
             
          </div>
          </div>
          </form>
        </tab-content>
          <!-- tab 4 content -->
         <tab-content title="Paso 4" class="mb-5" icon="feather icon-refresh-cw" :before-change="validateStep4">
          <form data-vv-scope="step-4">
               <div class="vx-row" >
                <div class="vx-col md:w-1/2 w-full mt-2">
                   <div>
                    <vs-divider color="primary" ><h5>Ciclo produccion</h5></vs-divider>
                  </div>
              <div class="vx-col w-full mt-2">
                
                <vs-select v-model="cicloproduccion.dias" class="w-full select-large" label="Tipo" name="cicprod_tipo" v-validate="'required'" 
                @input="changecicprod">
                  <vs-select-item :key="index" :value="item.value" :text="item.text" v-for="(item,index) in tipoCicloProduccion" class="w-full" />
                </vs-select>  
               <span class="text-danger text-sm" >{{ errors.first('step-4.cicprod_tipo') }}</span>


               <!-- <vs-select v-model="cicloproduccion.dias" class="w-full select-large" label="Tipo" name="cicprod_tipo" v-validate="'required'" 
                @input="changecicprod">
                        <vs-select-item :key="index" :value="item.value" :text="item.text" v-for="(item,index) in tipoCicloProduccion" class="w-full" />
                    </vs-select>
                <span class="text-danger text-sm" >{{ errors.first('step-4.cicprod_tipo') }}</span>-->
              
            </div>

            <div class="vx-col md:w-1/2  w-full mt-2">
                  <flat-pickr v-model="cicloproduccion.inicio" class="w-full select-large" placeholder="Desde" 
                  name="cicprod_desde" v-validate="'required'" :disabled="disabledcicpro"/>
                  <span class="text-danger text-sm" >{{ errors.first('step-4.cicprod_desde') }}</span>
              </div>
             <div class="vx-col md:w-1/2 w-full mt-2">
                  <flat-pickr v-model="cicloproduccion.fin" class="w-full select-large" placeholder="Hasta" 
                  name="cicprod_hasta" v-validate="'required'" :disabled="disabledcicpro"/>
                  <span class="text-danger text-sm" >{{ errors.first('step-4.cicprod_hasta') }}</span>
              </div>

              </div>    

                 <div class="vx-col md:w-1/2 w-full mt-2">  
                     <div>
                    <vs-divider color="primary" ><h5>Ciclo facturacion</h5></vs-divider>
                    </div>
                <div class="vx-col  w-full mt-2">

                  <vs-select v-model="ciclofacturacion.dias" class="w-full select-large" label="Tipo" name="cicfac_tipo" v-validate="'required'" @input="changecicfac">
                  <vs-select-item :key="index" :value="item.value" :text="item.text" v-for="(item,index) in tipoCiclofacturacion" class="w-full" />
                </vs-select>
               <span class="text-danger text-sm" >{{ errors.first('step-4.cicfac_tipo') }}</span>



               <!--    <vs-select v-model="ciclofacturacion.dias" class="w-full select-large" label="Tipo" name="cicfac_tipo" v-validate="'required'"
                   @input="changecicfac">
                        <vs-select-item :key="ind" :value="itm.value" :text="itm.text" v-for="(itm,ind) in tipoCiclofacturacion" class="w-full" />
                    </vs-select>
                <span class="text-danger text-sm" >{{ errors.first('step-4.cicfac_tipo') }}</span>-->
              </div>
            <div class="vx-col md:w-1/2  w-full mt-2">
                  <flat-pickr v-model="ciclofacturacion.inicio" class="w-full select-large" placeholder="Desde" 
                  name="cicfac_desde" v-validate="'required'" :disabled="disabledcicfac"/>
                  <span class="text-danger text-sm" >{{ errors.first('step-4.cicfac_desde') }}</span>
              </div>
             <div class="vx-col md:w-1/2 w-full mt-2">
                  <flat-pickr v-model="ciclofacturacion.fin" class="w-full select-large" placeholder="Hasta" 
                  name="cicfac_hasta" v-validate="'required'" :disabled="disabledcicfac"/>
                  <span class="text-danger text-sm" >{{ errors.first('step-4.cicfac_hasta') }}</span>
              </div>

                </div>  
             </div>   

          </form>
        </tab-content>
      </form-wizard>
    </div>
                
        </vs-popup>
        <!-- POP UP -->

        <!-- ITEMS PER PAGE -->
        <vs-dropdown vs-trigger-click class="cursor-pointer mb-4 mr-4">
          <div class="p-4 border border-solid d-theme-border-grey-light rounded-full d-theme-dark-bg cursor-pointer flex items-center justify-between font-medium">
            <span class="mr-2">{{ currentPage * itemsPerPage - (itemsPerPage - 1) }} - {{ items.length - currentPage * itemsPerPage > 0 ? currentPage * itemsPerPage : items.length }} of {{ items.length }}</span>
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
        <vs-th sort-key="items-razon_social">Razon Social</vs-th>
        <vs-th sort-key="items-giro">Giro</vs-th>
        <vs-th sort-key="items-rut">RUT</vs-th>
       <!--  <vs-th sort-key="users-created_at">Creado</vs-th>
        <vs-th sort-key="users-updated_at">Actualizado</vs-th>-->
        <vs-th sort-key="items-accion">Accion</vs-th>
      </template>

        <template slot-scope="{data}">
          <tbody>
            
            <vs-tr :data="tr" :key="indextr" v-for="(tr, indextr) in data">

              <vs-td>
                <p class="items-id font-medium">{{ tr.id }}</p>
              </vs-td>

              <vs-td>
                <p class="items-razon_social">{{ tr.razon_social }}</p>
              </vs-td>
              <vs-td>
                <p class="items-giro">{{ tr.giro }}</p>
              </vs-td>
              <vs-td>
                <p class="items-rut">{{ tr.rut + '-' + tr.dv }}</p>
              </vs-td>
              
              <vs-td>
             
                <div class="flex vx-col w-full sm:w-auto ml-auto mt-2 sm:mt-0">
                      <vx-tooltip color="primary" text="Editar">
                      <vs-button radius color="primary" type="border" icon-pack="feather" icon="icon-edit-2" size="small" class="ml-3" @click="editar(tr)"></vs-button>
                       </vx-tooltip>
                      <vx-tooltip color="primary" text="Eliminar">
                       <vs-button radius color="primary" type="border" icon-pack="feather" icon="icon-trash" size="small" class="ml-3" @click="$submitEliminar(tr, indextr)"></vs-button>
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
import { FormWizard, TabContent } from 'vue-form-wizard'
import 'vue-form-wizard/dist/vue-form-wizard.min.css'
import flatPickr from 'vue-flatpickr-component';

// For custom error message
import { Validator } from 'vee-validate';
const dict = {
    custom: {
        razon_social: {
            required: 'El nombre es requerido',
        },
        rut: {
            required: 'El rut es requerido',
            numeric: 'Debe ingresar solo numeros'
        },
        dv: {
            required: 'El digito verificador es requerido',
            max: 'Debe ingresar solo 1 caracter',
        },
         responsable_nombre: {
            required: 'El nombre es requerido'
        },
         responsable_telefono: {
            required: 'El telefono es requerido',
            numeric: 'El numero de telefono debe ser valido'
        },
         responsable_email: {
            required: 'El email es requerido',
            email: 'Ingrese un email valido'
        },
        tiposervicio_outsourcing:{
            required: 'El outsourcing es requerido',
        },
        tiposervicio_tipo:{
            required: 'El tipo de servicio es requerido',     
        },
        tiposervicio_valor:{
            required: 'El valor es requerido',
            decimal: 'El valor debe ser numerico'
        },
        cicprod_tipo:{
            required: 'El tipo es requerido',
        },
        cicprod_desde:{
            required: 'La fecha desde es requerida',
        },
        cicprod_hasta:{
            required: 'La fecha hasta es requerida',
        },
        cicfac_tipo:{
            required: 'El tipo es requerido',
        },
        cicfac_desde:{
            required: 'La fecha desde es requerida',
        },
        cicfac_hasta:{
            required: 'La fecha hasta es requerida',
        }
    }
};

// register custom messages
Validator.localize('en', dict);

export default {
  components: { 
    FormWizard,
    TabContent,
    flatPickr
  },
  data() {
    return {
      ruta:'/empresas/empresas/',
      selected: [],
      datos: [],
      items: [],
      itemsPerPage: 4,
      isMounted: false,
      ite : "",
      ind : "",
      popupActive: false,
      item : {},
      modoEditar: false,      
      roles_choices: [],
      empresa_choices: [],
      exportData : [],
      aux: 0,
      tipoOpciones: [
        {text: "Por plana", value:"1"},
        {text: "Por pasajero", value:"2"},
        {text: "Por KM.", value:"3"},
       
      ],
      tipoCicloProduccion: [
        {text: "Por defecto (30 dias)", value: 30},
        {text: "Personalizado", value: 0},       
      ],
      tipoCiclofacturacion: [
        {text: "Por dia", value: 1},
        {text: "Por 15 dias", value: 15},
        {text: "Por 30 dias", value: 30},
        {text: "Por 90 dias", value: 90},
        {text: "Personalizado", value: 0},
       
      ],
      tiposervicio : {},
      responsables : [{ 
            id: "",
            empresa_id: "",
            name: "",
            email: "",
            telefono: "",
        }],
      sucursales : [{ 
            id: "",
            empresa_id: "",
            nombre: "",
            direccion: "",
            pais: "",
            ciudad: "",
            comuna: "",
            numeracion: "",
            matriz: "",
        }],

      
      ciclofacturacion : { },
      disabledcicfac: true,
      cicloproduccion : {},
      disabledcicpro: true,

    }
  },
  computed: {
    currentPage() {
      if(this.isMounted) {
        return this.$refs.table.currentx
      }
      return 0
    },
  },
  methods: {
    changecicprod(event) {
      if(this.cicloproduccion.dias == 0) {
          this.disabledcicpro = false;
      }else{
          this.disabledcicpro = true;
          this.cicloproduccion.inicio = "";
          this.cicloproduccion.fin = "";
          this.errors.clear();
      }
      

    },
    changecicfac(event) {
       if(this.ciclofacturacion.dias == 0) {
          this.disabledcicfac = false;
      }else{
          this.disabledcicfac = true;
          this.ciclofacturacion.inicio = "";
          this.ciclofacturacion.fin = "";
          this.errors.clear();
      }
    
    },
        agregarResponsable() {
            this.responsables.push({});
        },
        quitarResponsable(id) {
          if(this.responsables.length > 1){
            this.responsables.splice(id, 1)
           }
        },
        agregarSucursal() {
            this.sucursales.push({});
        },
        quitarSucursal(id) {
          if(this.sucursales.length > 1){
            this.sucursales.splice(id, 1)
           }
        },
    validateStep1() {
            return new Promise((resolve, reject) => {
                this.$validator.validateAll('step-1').then(result => {
                    if (result) {
                        resolve(true)
                    } else {
                        reject("correct all values");
                    }
                })
            })
        },
        validateStep2() {
            return new Promise((resolve, reject) => {
                this.$validator.validateAll("step-2").then(result => {
                    if (result) {           
                        resolve(true)
                    } else {
                        reject("correct all values");
                    }
                })
            })
        },
         validateStep3() {
            return new Promise((resolve, reject) => {
                this.$validator.validateAll("step-3").then(result => {
                    if (result) {          
                        resolve(true)
                    } else {
                        reject("correct all values");
                    }
                })
            })
        }, 
        validateStep4() {
            return new Promise((resolve, reject) => {
                this.$validator.validateAll("step-4").then(result => {
                    if (result) {
                      if(this.modoEditar == false){
                          const url = this.ruta + 'store';
                          this.submitRegistros(url);
                      }else{
                          const url = this.ruta + this.item.id;
                          this.submitActualizaRegistros(url);
                      }
                        
                        resolve(true)
                    } else {
                        reject("correct all values");
                    }
                })
            })
        },
    editar(item){      
      this.initValues();
      this.modoEditar = true; 
      this.item.rut = item.rut;
      this.item.dv = item.dv;
      this.item.razon_social = item.razon_social;
      this.item.giro = item.giro;
      this.item.id = item.id;
      this.traeOtrosDatos(item.id);
      this.selected = [];
      this.popupActive=true;
    },

    initValues() {
      this.datos = [];
      //this.$refs.wizard.navigateToTab(0);
      this.item = {};
      this.responsables = [{ 
            id: "",
            empresa_id: "",
            name: "",
            email: "",
            telefono: "",
        }];
      this.sucursales = [{ 
            id: "",
            empresa_id: "",
            nombre: "",
            direccion: "",
            pais: "",
            ciudad: "",
            comuna: "",
            numeracion: "",
            matriz: "",
        }];
      this.tiposervicio = {};
      this.ciclofacturacion = {};
      this.cicloproduccion = {};
      this.disabledcicfac = true,
      this.disabledcicpro = true,
      this.errors.clear();
      this.$refs.wizard.reset();
 
    },
    //Enviar Registros
     submitRegistros(url) {

      const thisIns = this;
      this.datos = [];
      this.datos.push(this.item);
      this.datos.push(this.tiposervicio);
      this.datos.push(this.responsables);
      this.datos.push(this.sucursales);
      this.datos.push(this.ciclofacturacion);
      this.datos.push(this.cicloproduccion);

      this.$validator.validateAll().then(result =>{
        if (result) {

         this.$http.post(url, this.datos) //this.item
            .then((res) =>{

              this.$vs.loading({ type: 'radius' , scale: 0.6 });
                this.$refrescaTabla();
                this.initValues();
                this.modoEditar = false; 
                this.popupActive=false;
                setTimeout(() => {
                  this.$vs.loading.close()
                }, 500);

              thisIns.$vs.notify({
                title:'Exito',
                text: 'El registro se ha creado con exito!.',
                color:'success',
                iconPack: 'feather',
                icon:'icon-alert-circle'})               
                
                
            })
            .catch(function (error) {

                thisIns.$vs.notify({
                  title:'Error',
                  text: error,
                  color:'danger',
                  iconPack: 'feather',
                  icon:'icon-alert-circle'})
            });
        } else {
        }
      })
    },    
    //Actuliza Registros
     submitActualizaRegistros(url) {

      const thisIns = this;
      this.datos = [];
      this.datos.push(this.item);
      this.datos.push(this.tiposervicio);
      this.datos.push(this.responsables);
      this.datos.push(this.sucursales);
      this.datos.push(this.ciclofacturacion);
      this.datos.push(this.cicloproduccion);
    return;
      this.$validator.validateAll().then(result =>{
        if (result) {

         this.$http.put(url, this.datos) //this.item
            .then((res) =>{

              this.$vs.loading({ type: 'radius' , scale: 0.6 });
                this.$refrescaTabla();
                this.initValues();
                this.modoEditar = false; 
                this.popupActive=false;
                setTimeout(() => {
                  this.$vs.loading.close()
                }, 500);

              thisIns.$vs.notify({
                title:'Exito',
                text: 'El registro se ha actualizado con exito!.',
                color:'success',
                iconPack: 'feather',
                icon:'icon-alert-circle'})               
                
                
            })
            .catch(function (error) {

                thisIns.$vs.notify({
                  title:'Error',
                  text: error,
                  color:'danger',
                  iconPack: 'feather',
                  icon:'icon-alert-circle'})
            });
        } else {
        }
      })
    },
    traeOtrosDatos(id) {
        //Carga datos empresa   
        const thisIns = this;
        const url = this.ruta + 'otros/'+ this.item.id; 
        //thisIns.treeData = {};
        this.$http.get(url)
          .then((response) =>{
                     
             this.tiposervicio = response.data.tiposervicio[0]
             this.responsables = response.data.responsables
             this.sucursales = response.data.sucursales
             this.ciclofacturacion = response.data.cicfac
             this.cicloproduccion = response.data.cicpro
             response.data.cicfac.dias === 0 ? this.disabledcicfac = false : this.disabledcicfac = true;
             response.data.cicpro.dias === 0 ? this.disabledcicpro = false : this.disabledcicpro = true;

          })
          .catch(function (error) {
            thisIns.$vs.notify({
              title:'Error',
              text: error,
              color:'danger',
              iconPack: 'feather',
              icon:'icon-alert-circle'})
          });
      },
      foo(id) {
            console.log(id);
        },
  },
  created() {
      this.$refrescaTabla();       
  },
  mounted() {
    this.isMounted = true;
  }
}
</script>
<style src="flatpickr/dist/flatpickr.css"></style>
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

      .vs-table--search{
        padding-top: 0;

        .vs-table--search-input {
          padding: 0.9rem 2.5rem;
          font-size: 1rem;

          &+i {
            left: 1rem;
          }

          &:focus+i {
            left: 1rem;
          }
        }
      }
    }

    .vs-table {
      border-collapse: separate;
      border-spacing: 0 1.3rem;
      padding: 0 1rem;

      tr{
          box-shadow: 0 4px 20px 0 rgba(0,0,0,.05);
          td{
            padding: 20px;
            &:first-child{
              border-top-left-radius: .5rem;
              border-bottom-left-radius: .5rem;
            }
            &:last-child{
              border-top-right-radius: .5rem;
              border-bottom-right-radius: .5rem;
            }
          }
          td.td-check{
            padding: 20px !important;
          }
      }
    }

    .vs-table--thead{
      th {
        padding-top: 0;
        padding-bottom: 0;

        .vs-table-text{
          text-transform: uppercase;
          font-weight: 600;
        }
      }
      th.td-check{
        padding: 0 15px !important;
      }
      tr{
        background: none;
        box-shadow: none;
      }
    }

    .vs-table--pagination {
      justify-content: center;
    }
  }
}

.vs-popup {
  width: 720px !important;
}

</style>
