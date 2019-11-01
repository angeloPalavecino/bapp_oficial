<template>
  <div id="data-list-list-view" class="data-list-container">

     <!-- PASAJEROS -->
          <vs-popup class="holamundo"  title="Tarifas Pasajeros" :active.sync="popupParametros"
          @close="$close($event)">    
    
    <vs-table ref="tablepar" multiple v-model="selected" pagination search :data="parametros">
         <template slot="header">
                   <vs-dropdown vs-trigger-click class="cursor-pointer mr-4 mb-4">
            <div
              class="p-4 shadow-drop rounded-lg d-theme-dark-bg cursor-pointer flex items-center justify-center text-lg font-medium w-32"
            >
              <span class="mr-2">Acciones</span>
              <feather-icon icon="ChevronDownIcon" svgClasses="h-4 w-4" />
            </div>

            <vs-dropdown-menu>
              <vs-dropdown-item @click.prevent="accion(1)">
                <span>Borrar</span>
              </vs-dropdown-item>
              <vs-dropdown-item @click.prevent="accion(2)">
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
       </template>

      <template slot="thead">
        <vs-th sort-key="items-id">ID</vs-th>
        <vs-th sort-key="items-num-pasajeros">N° Pasajeros</vs-th>
        <vs-th sort-key="items-factor">Factor</vs-th>
        <vs-th sort-key="items-valor">Valor</vs-th>
        <vs-th sort-key="items-accion">Accion</vs-th>
      </template>

        <template slot-scope="{data}">
 
            <vs-tr :data="tr" :key="indextr" v-for="(tr, indextr) in data">

              <vs-td>
                <p class="items-id font-medium">{{ tr.serviciospasajeros[0].id }}</p>
              </vs-td>

              <vs-td>
                <p class="items-num-pasajeros">{{ tr.serviciospasajeros[0].num_psj_min }} - {{ tr.serviciospasajeros[0].num_psj_max }}</p>
              </vs-td>
                <vs-td>
                <p class="items-factor">{{ tr.serviciospasajeros[0].fac_rang_min }} - {{ tr.serviciospasajeros[0].fac_rang_max }}</p>
              </vs-td>
                <vs-td>
                <p class="items-valor">{{ tr.serviciospasajeros[0].valor }}</p>
              </vs-td>
              <vs-td>
                   <div class="flex vx-col w-full sm:w-auto ml-auto mt-2 sm:mt-0">
                      <vx-tooltip color="primary" text="Editar">
                      <vs-button radius color="primary" type="border" icon-pack="feather" icon="icon-edit-2" size="small" class="ml-3" @click="editar(tr)"></vs-button>
                       </vx-tooltip>
                      <vx-tooltip color="primary" text="Eliminar">
                       <vs-button radius color="primary" type="border" icon-pack="feather" icon="icon-trash" size="small" class="ml-3" @click="submitEliminar(tr, indextr)"></vs-button>
                       </vx-tooltip>
                 </div>
            </vs-td>
            </vs-tr>
       
        </template>
    </vs-table>
         
            

       
          </vs-popup>
          <!-- FIN PASAJEROS -->

           <!-- POP UP -->
        <vs-popup class="popuptarifa"  ref="modal" :title="(modoEditar == false ? 'AGREGAR TARIFA' : 'ACTUALIZAR TARIFA')" 
         :active.sync="popupActive"  @close="$close($event)">
        <div>
                <vs-divider color="primary"><h5>Tarifas Pasajeros</h5></vs-divider>
              </div>
          <div class="vx-row">
            <div class="vx-col md:w-1/2 w-full mt-2">
            <span class=" text-sm" >Empresa</span>
                  <vs-select v-model="item.empresa_id"  ref="empresa" name="empresa" class="w-full" 
                v-validate="'required'" :disabled="disabledempresas">
                <vs-select-item :key="item.id" :value="item.id" :text="item.razon_social" v-for="item in empresa_choices"  />
              </vs-select>
              <span class="text-danger text-sm" v-show="errors.has('empresa')">{{ errors.first('empresa') }}</span>
          </div>
          <div class="vx-col md:w-1/2 w-full mt-2">
           
            </div>
            <div class="vx-col md:w-1/2 w-full mt-4">
            <span class=" text-sm" >N° Pasajeros Min.</span>
              <vs-input-number v-model="item.num_psj_min" 
                 name="num_psj_min" v-validate="'required'"  class="w-full" min="1" 
                 icon-inc="expand_less" icon-dec="expand_more" />

              <span class="text-danger text-sm" v-show="errors.has('num_psj_min')">{{ errors.first('num_psj_min') }}</span>

            </div>
            <div class="vx-col md:w-1/2 w-full mt-4">
            <span class=" text-sm" >N° Pasajeros Max.</span>
            <vs-input-number v-model="item.num_psj_max" 
                 name="num_psj_max" v-validate="'required'"  class="w-full" min="1" 
                 icon-inc="expand_less" icon-dec="expand_more" />

              <span class="text-danger text-sm" v-show="errors.has('num_psj_max')">{{ errors.first('num_psj_max') }}</span>
            </div>
            <div class="vx-col md:w-1/2 w-full mt-2">
              <vs-input label-placeholder="Factor Rango Min."  v-model="item.fac_rang_min" class="w-full" name="fac_rang_min" v-validate="'required|decimal'" 
              :danger="(errors.first('fac_rang_min') ? true : false)" val-icon-danger="clear" />
              <span class="text-danger text-sm" v-show="errors.has('fac_rang_min')">{{ errors.first('fac_rang_min') }}</span>
            </div>
            <div class="vx-col md:w-1/2 w-full mt-2">
                <vs-input  label-placeholder="Factor Rango Max."  v-model="item.fac_rang_max" class="w-full" name="fac_rang_max" v-validate="'required|decimal'" 
                :danger="(errors.first('fac_rang_max') ? true : false)" val-icon-danger="clear" />
              <span class="text-danger text-sm" v-show="errors.has('fac_rang_max')">{{ errors.first('fac_rang_max') }}</span>
            </div>
            <div class="vx-col md:w-1/2 w-full mt-2">
              <vs-input label-placeholder="Valor" icon-pack="feather" icon="icon-dollar-sign" icon-no-border v-model="item.valor" class="w-full" name="valor"
               v-validate="'required|decimal'" 
             :danger="(errors.first('valor') ? true : false)" val-icon-danger="clear" />
              <span class="text-danger text-sm" v-show="errors.has('valor')">{{ errors.first('valor') }}</span>
            </div>
             <div class="vx-col md:w-1/2 w-full mt-2">
              </div>
            <div class="vx-col md:w-1/2 w-full mt-2">
              <vs-input label-placeholder="Factor Rango Fuera Zona 1"  v-model="item.fac_rang_fz1" class="w-full" name="fac_rang_fz1" v-validate="'required|decimal'" 
              :danger="(errors.first('fac_rang_fz1') ? true : false)" val-icon-danger="clear" />
              <span class="text-danger text-sm" v-show="errors.has('fac_rang_fz1')">{{ errors.first('fac_rang_fz1') }}</span>
            </div>
            <div class="vx-col md:w-1/2 w-full mt-2">
                      <vs-input  label-placeholder="Valor Fuera Zona 1" icon-pack="feather" icon="icon-dollar-sign" icon-no-border v-model="item.valor_fz1" class="w-full" 
                      name="valor_fz1" v-validate="'required|decimal'" 
              :danger="(errors.first('valor_fz1') ? true : false)" val-icon-danger="clear" />
              <span class="text-danger text-sm" v-show="errors.has('valor_fz1')">{{ errors.first('valor_fz1') }}</span>
            </div>
            <div class="vx-col md:w-1/2 w-full mt-2">
                <vs-input  label-placeholder="Factor Rango Fuera Zona 2"  v-model="item.fac_rang_fz2" class="w-full" name="fac_rang_fz2" v-validate="'required|decimal'" 
                :danger="(errors.first('fac_rang_fz2') ? true : false)" val-icon-danger="clear" />
              <span class="text-danger text-sm" v-show="errors.has('fac_rang_fz2')">{{ errors.first('fac_rang_fz2') }}</span>
                   </div>

            <div class="vx-col md:w-1/2 w-full mt-2">
              <vs-input label-placeholder="Valor Fuera Zona 2" icon-pack="feather" icon="icon-dollar-sign" icon-no-border v-model="item.valor_fz2" class="w-full" 
              name="valor_fz2" v-validate="'required|decimal'" 
             :danger="(errors.first('valor_fz2') ? true : false)" val-icon-danger="clear" />
              <span class="text-danger text-sm" v-show="errors.has('valor_fz2')">{{ errors.first('valor_fz2') }}</span>
            </div>
              <div class="flex flex-wrap items-center justify-center p-6" slot="footer">
              <vs-button v-if="modoEditar == true" class="mr-3" @click.prevent="$submitActualizar()" >ACTUALIZAR TARIFA</vs-button>
              <vs-button v-else class="mr-3" @click.prevent="$submitAgregar()">AGREGAR TARIFA</vs-button>
              
           
              <vs-button type="border" color="danger" ref="btncancelar" @click.prevent="$cancelarPopUp()">CANCELAR</vs-button>
            </div>

          </div>
                
        </vs-popup>
        <!-- POP UP -->

    <vs-table ref="table" pagination :max-items="itemsPerPage" search :data="items">

      <div slot="header" class="flex flex-wrap-reverse items-center flex-grow justify-between">

        <div class="flex flex-wrap-reverse items-center">

          <!-- ADD NEW -->
          <div class="p-3 mb-4 mr-4 rounded-lg cursor-pointer flex items-center justify-between text-lg font-medium text-base 
          text-primary border border-solid border-primary" @click="$agregarPopUp()" > <!-- @click="addNewDataSidebar = true" -->
              <feather-icon icon="PlusIcon" svgClasses="h-4 w-4" />
              <vx-tooltip color="primary" text="Agregar empresa">
              <span class="ml-2 text-base text-primary">Agregar Tarifa</span>
              </vx-tooltip>
          </div>
       </div>
 
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
        <vs-th sort-key="items-rut">RUT</vs-th>
        <vs-th sort-key="items-cantidad">Parametros</vs-th>
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
                <p class="items-rut">{{ tr.rut }}</p>
              </vs-td>
              <vs-td>
                <vs-chip :color="getStatusColor(tr.cantidad)" class="items-cantidad">{{ tr.cantidad  }}</vs-chip>
              </vs-td>
              
              <vs-td>
             
                <div class="flex vx-col w-full sm:w-auto ml-auto mt-2 sm:mt-0">
                      <vx-tooltip color="primary" text="Parametros">
                      <vs-button radius color="primary" type="border" icon-pack="feather" icon="icon-search" size="small" class="ml-3" @click="listadoParametros(tr)"></vs-button>
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
// For custom error message
import { Validator } from 'vee-validate';
const dict = {
    custom: {
        num_psj_min: {
            required: 'El campo es requerido',
        },
        num_psj_max: {
            required: 'El campo es requerido',
        },
        fac_rang_min: {
            required: 'El campo es requerido',
            decimal: 'El valor debe ser numerico',
        },
        fac_rang_max: {
            required: 'El campo es requerido',
            decimal: 'El valor debe ser numerico',
        },
        valor: {
            required: 'El campo es requerido',
            decimal: 'El valor debe ser numerico',
        },
        fac_rang_fz1: {
            required: 'El campo es requerido',
            decimal: 'El valor debe ser numerico',
        },
        valor_fz1: {
            required: 'El campo es requerido',
            decimal: 'El valor debe ser numerico',
        },
        fac_rang_fz2: {
            required: 'El campo es requerido',
            decimal: 'El valor debe ser numerico',
        },
        valor_fz2: {
            required: 'El campo es requerido',
            decimal: 'El valor debe ser numerico',
        }
    }
};

// register custom messages
Validator.localize('en', dict);
export default {
  components: { 
  },
  data() {
    return {
      ruta:'/tarifas/pasajeros/',
      selected: [],
      items: [],
      itemsPerPage: 4,
      isMounted: false,
      ite : "",
      ind : "",
      popupActive: false,
      popupParametros: false,    
      item : {
        num_psj_min: 1,
        num_psj_max: 1,
      },
      parametros : [],
      modoEditar: false,      
      exportData : [],
      aux: 0,
      empresa_choices: [],
      disabledempresas: false,

    }
  },
  computed: {
    currentPage() {
      if(this.isMounted) {
        return this.$refs.table.currentx
        return this.$refs.tablepar.currentx
      }
      return 0
    },
  },
  methods: {
    editar(item){
      //this.initValues();
      this.modoEditar = true; 
      this.item.id = item.id;
      this.item.num_psj_min = item.serviciospasajeros[0].num_psj_min;
      this.item.num_psj_max = item.serviciospasajeros[0].num_psj_max;
      this.item.fac_rang_min = item.serviciospasajeros[0].fac_rang_min;
      this.item.fac_rang_max = item.serviciospasajeros[0].fac_rang_max;
      this.item.valor = item.serviciospasajeros[0].valor;
      this.item.fac_rang_fz1 = item.serviciospasajeros[0].fac_rang_fz1;
      this.item.valor_fz1 = item.serviciospasajeros[0].valor_fz1;
      this.item.fac_rang_fz2 = item.serviciospasajeros[0].fac_rang_fz2;
      this.item.valor_fz2 = item.serviciospasajeros[0].valor_fz2;
      this.item.empresa_id = item.empresa_id;
      this.disabledempresas = true;
      this.selected = [];
      this.popupActive=true;
    },
    initValues() {

      if(this.modoEditar== true)  {
        const idEmpresa = this.item.empresa_id;
        this.selected = [];
        this.resfrescaDatosListado(idEmpresa);
        
      }
      this.item = { 
        num_psj_min: 1,
        num_psj_max: 1,
        empresa_id:'',
      };
      this.errors.clear();
      this.disabledempresas = false;

    },
    listadoParametros(tr) {
      
      this.parametros = [];
      const idEmpresa = tr.id;

      this.resfrescaDatosListado(idEmpresa);  

      setTimeout(() => {
                this.popupParametros = true;
      }, 300);

    },
    getStatusColor(status) {
      if(status > 0) return "success"
      if(status == 0) return "danger"
      return "danger"
    },
    resfrescaDatosListado(idEmpresa){
       const thisIns = this; 
       const url = thisIns.ruta;
       this.$http.get(url + 'listado/' + idEmpresa)
          .then(function (response) {
            thisIns.parametros = response.data.items;         
          })
          .catch(function (error) {
            thisIns.$vs.notify({
              title:'Error',
              text: "Error al traer los parametros",
              color:'danger',
              iconPack: 'feather',
              icon:'icon-alert-circle'})
      });   
      
    },
    refrescaOtrosDatos() {
        const thisIns = this; 
        //Carga Empresa
          this.$http.get('empresas/empresas')
          .then(function (response) {
            thisIns.empresa_choices = response.data.items 
          })
          .catch(function (error) {
              var textError;
             if(error.response.status == 300) { 
                textError = error.response.data.message;
               }else{
                textError = error;
              }

               thisIns.$vs.notify({
                  title:'Error',
                  text: textError,
                  color:'danger',
                  iconPack: 'feather',
                  icon:'icon-alert-circle'})         

          });
      },
      accion(seleccion) {
      switch (seleccion) {
        case 1:
            this.aux = this.selected.length;
            if(this.aux > 0){


                this.$vs.dialog({
                  
                  type: 'confirm',
                  color: 'danger',
                  title: `Borrado masivo`,
                  text: `Esta seguro que desea eliminar todos lo registros seleccionados?`,
                  accept: this.aceptaBorradoMasivo,
                  cancel: this.$cancelarBorradoMasivo,
              
                })
              
              }else{

                this.$vs.dialog({
                    color:'danger',
                    title: 'Error',
                    text: 'Debe seleccionar al menos un registro para realizar esta accion.',
                     iconPack: 'feather',
                    icon:'icon-alert-circle'
                  })

              }
            break;
        case 2:
             //Arreglar datos excel
            const datos = this.parametros;
            this.$csvExport(datos);
            break;          
        default:
           return;
    }
    },
    //Borrado Registro
    submitEliminar(x, index) {
        this.ite = x;
        this.ind = index;
        this.selected = [];
        this.$vs.dialog({
                type: 'confirm',
                color: 'danger',
                title: `Eliminar registro`,
                text: `Esta seguro que desea eliminar el registro seleccionado?`,
                accept: this.aceptaBorrado,
                cancel: this.cancelarBorrado,
              
            })
      },
    //Acepta Borrado
    aceptaBorrado() {
       const thisIns = this;
       const url = thisIns.ruta + this.ite.id;
       const idEmpresa = this.ite.empresa_id;
        this.$http.delete(url)
          .then(()=>{
            this.$vs.loading({ type: 'radius' , scale: 0.6 });
              this.$refrescaTabla();
              this.selected = [];
              this.resfrescaDatosListado(idEmpresa); 

              setTimeout(() => {
                  this.$vs.loading.close()
              }, 500);
              

             this.$vs.notify({
              title:'Exito',
              text: 'El registro fue eliminado exitosamente',
              color:'success',
              iconPack: 'feather',
              icon:'icon-alert-circle'})

              delete this.ite;
              delete this.ind;

          })
          .catch(function (error) {
              var textError;
              if(error.response.status == 300) { 
                textError = error.response.data.message;
               }else{
                textError = error;
              }

               thisIns.$vs.notify({
                  title:'Error',
                  text: textError,
                  color:'danger',
                  iconPack: 'feather',
                  icon:'icon-alert-circle'})         


              delete this.ite;
              delete this.ind;
          });
     },
      //Cancela Borrado
    cancelarBorrado() {
          delete this.ite;
          delete this.ind;
          this.selected = [];
    },
    //Acepta el borrado masivo
    aceptaBorradoMasivo() {
        const thisIns = this;
        const registrosSeleccionados = this.selected;
        const url = thisIns.ruta + 'borrar';
        const idEmpresa = this.ite.empresa_id;

        this.$http.post(url, registrosSeleccionados)
          .then(()=>{
            thisIns.$vs.loading({ type: 'radius' , scale: 0.6 });
              this.$refrescaTabla();
              this.selected = [];
              this.resfrescaDatosListado(idEmpresa);

              setTimeout(() => {
                  this.$vs.loading.close()
              }, 500);
              

             thisIns.$vs.notify({
              title:'Exito',
              text: 'Los registros han sido eliminado exitosamente',
              color:'success',
              iconPack: 'feather',
              icon:'icon-alert-circle'})


          })
          .catch(function (error) {
           var textError;
           if(error.response.status == 300) { 
                textError = error.response.data.message;
               }else{
                textError = error;
              }

               thisIns.$vs.notify({
                  title:'Error',
                  text: textError,
                  color:'danger',
                  iconPack: 'feather',
                  icon:'icon-alert-circle'})         
     
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
}
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
  width: 830px !important;
}

.con-vs-dropdown--menu {
    z-index: 58000 !important;
}

.con-vs-dialog {
    z-index: 56005 !important;
}

.popuptarifa {
    z-index: 57005 !important;
}


</style>