<template>
  <div id="data-list-list-view" class="data-list-container">

         <!-- MOVILES -->
   <vs-popup class="holamundo"  ref="modalMoviles" title="Moviles" :active.sync="popupMoviles">    
     
        <vs-table ref="tablemoviles" max-items="10" @input="handleSelectedMoviles" multiple v-model="item.moviles" pagination search :data="moviles_choices">
           <template slot="header">
                    <h3>
                      Moviles
                    </h3>
            </template>
          <template slot="thead">
            <vs-th >ID</vs-th>
            <vs-th >N° Movil</vs-th>
            <vs-th >Patente</vs-th>
          </template>
          <template slot-scope="{data}">
            <tbody>
                <vs-tr :data="trmov" :key="indextrmov" v-for="(trmov, indextrmov) in data">
                  <vs-td>
                    <p>{{ trmov.id }}</p>
                  </vs-td>
                  <vs-td>
                    <p>{{ trmov.numero_movil }}</p>
                  </vs-td>
                  <vs-td>
                    <p>{{ trmov.patente }}</p>
                  </vs-td>             
                </vs-tr>  
            </tbody> 
            </template>
        </vs-table>
    
    </vs-popup>
          <!-- FIN MOVILES -->

              <!-- POP UP -->
        <vs-popup class="holamundo"  ref="modal" :title="(modoEditar == false ? 'AGREGAR USUARIO' : 'ACTUALIZAR USUARIO')" 
         :active.sync="popupActive"   @close="$close($event)">
        <div class="mt-5">
      <form-wizard color="rgba(var(--vs-primary), 1)" errorColor="rgba(var(--vs-danger), 1)" 
      :title="(modoEditar == false ? 'AGREGAR USUARIO' : 'ACTUALIZAR USUARIO')" 
      :subtitle="(modoEditar == false ? 'Ingrese todos los campos para ingresar el usuario' : 'Modifique los campos que desee actualizar')" 
      :finishButtonText="(modoEditar == false ? 'Agregar' : 'Actualizar')" ref="wizard">
        <tab-content title="Paso 1" class="mb-5" icon="feather icon-user" :before-change="validateStep1">

          <!-- tab 1 content -->
          <form data-vv-scope="step-1">
            <div>
                <vs-divider color="primary"><h5>Datos Usuarios</h5></vs-divider>
              </div>
          <div class="vx-row">
            <div class="vx-col md:w-1/2 w-full mt-2">
              <vs-input label-placeholder="Nombre" v-model="item.name" class="w-full" name="name" v-validate="'required|alpha_spaces'" 
             :danger="(errors.first('step-1.name') ? true : false)" val-icon-danger="clear" />
              <span class="text-danger text-sm">{{ errors.first('step-1.name') }}</span>
            </div>
            <div class="vx-col md:w-1/2 w-full mt-2">
              <vs-input label-placeholder="Apellido"  v-model="item.lastname" class="w-full" name="lastname" v-validate="'required|alpha_spaces'" 
              :danger="(errors.first('step-1.lastname') ? true : false)" val-icon-danger="clear" />
              <span class="text-danger text-sm">{{ errors.first('step-1.lastname') }}</span>
            </div>
            <div class="vx-col md:w-1/2 w-full mt-2">
              <vs-input type="Email" label-placeholder="Email"  v-model="item.email" class="w-full" name="email" v-validate="'required|email'" 
              :danger="(errors.first('step-1.email') ? true : false)" val-icon-danger="clear" />
              <span class="text-danger text-sm">{{ errors.first('step-1.email') }}</span>
            </div>
            <div class="vx-col md:w-1/2 w-full mt-2">
                <vs-input  label-placeholder="Rut"  v-model="item.rut" class="w-full" name="rut" v-validate="'required|alpha_dash'" 
                :danger="(errors.first('step-1.rut') ? true : false)" val-icon-danger="clear" />
              <span class="text-danger text-sm">{{ errors.first('step-1.rut') }}</span>
            </div>
          </div>
          </form>
        </tab-content>

        <!-- tab 2 content -->
        <tab-content title="Paso 2" class="mb-5" icon="feather icon-briefcase" :before-change="validateStep2">
          <form data-vv-scope="step-2">
            <div>
                <vs-divider color="primary"><h5>Informacion adicional</h5></vs-divider>
            </div>
          <div class="vx-row">
            <div class="vx-col md:w-1/2 w-full mt-2">
              <vs-select v-model="item.roles" label="Roles" name="roles" class="w-full" v-validate="'required'" @input="setSelected">
                <vs-select-item :key="item.id" :value="item.id" :text="item.name" v-for="item in roles_choices"  />
              </vs-select>
              <span class="text-danger text-sm" v-show="errors.has('step-2.roles')">{{ errors.first('step-2.roles') }}</span>
            </div>
             <div class="vx-col md:w-1/2 w-full mt-2">
                <vs-select v-model="item.empresa_id" label="Empresa" ref="empresa" name="empresa" class="w-full" 
                v-validate="'required'" :disabled="(item.roles > 2 ? false : true)" >
                <vs-select-item :key="item.id" :value="item.id" :text="item.razon_social" v-for="item in empresa_choices"  />
              </vs-select>
              <span class="text-danger text-sm" v-show="errors.has('step-2.empresa')">{{ errors.first('step-2.empresa') }}</span>
          
             </div>
            <div class="vx-col md:w-1/2 w-full mt-3">
                    <vs-input label-placeholder="Telefono" v-model="item.telefono" class="w-full" name="telefono" 
                    v-validate="'required|numeric'" 
              :danger="(errors.first('step-2.telefono') ? true : false)" val-icon-danger="clear"/>
              <span class="text-danger text-sm">{{ errors.first('step-2.telefono') }}</span>
             </div>
            <div class="vx-col md:w-auto w-full mt-3" >
            <div  v-show="item.roles === 2">
            <span style="font-size: 12px;">Moviles</span>
           <!--  <vs-button radius color="primary" type="border" icon-pack="feather" :disabled="(item.roles === 2 ? false : true)" 
              icon="icon-truck" size="medium" @click="listadoMoviles()"></vs-button>-->

               <div class="p-3 mb-4 mr-4 rounded-lg cursor-pointer flex items-center justify-between text-lg font-medium text-base 
          text-primary border border-solid border-primary" @click="listadoMoviles"  > <!-- @click="addNewDataSidebar = true" -->
              <feather-icon icon="TruckIcon" svgClasses="h-4 w-4" />
              </div>
          </div>
            </div>

            <div class="vx-col md:w-1/3  w-full mt-8" v-show="item.roles === 2">
          
                <vs-chip :color="getStatusColorMovil(item.moviles.length)" class="items-status-moviles mt-2">{{ item.moviles.length > 0 ? item.moviles.length : 0  }}</vs-chip>
                  <div class="mt-2">
                    <span style="font-size: 13px;" >  Moviles seleccionados</span>
                    <vs-input v-validate="(item.roles == 2 ? 'min_value:1' : '')" type="hidden" name="moviles" v-model="movilescount"/>
                    <span class="text-danger text-sm">{{ errors.first('step-2.moviles') }}</span>
                  </div>
        
            </div>
            <div class="vx-col md:w-1/2 w-full mt-2">
                  <vs-input type="password" v-validate="(modoEditar == false ? 'required|min:8' : 'min:8')" ref="password" label-placeholder="Password" name="password" 
                  v-model="item.password"  class="w-full" 
                  :danger="(errors.first('step-2.password') ? true : false)" val-icon-danger="clear"/>
                  <span class="text-danger text-sm" v-show="errors.has('step-2.password')" >{{ errors.first('step-2.password') }}</span>
                </div>
              <div class="vx-col md:w-1/2 w-full mt-2">
                  <vs-input type="password" v-validate="(modoEditar == false ? 'required|min:8|confirmed:password' : 'min:8|confirmed:password')" data-vv-as="password" label-placeholder="Confirmar Password" 
                  name="password_confirmation"  class="w-full" v-model="item.password_confirmation"
                  :danger="(errors.first('step-2.password_confirmation') ? true : false)" val-icon-danger="clear"/>
                  <span class="text-danger text-sm" v-show="errors.has('step-2.password_confirmation')">{{ errors.first('step-2.password_confirmation') }}</span>
           
            </div>
         <div class="vx-col md:w-1 w-full">
          <div class="demo-alignment">
            <span>Habilitado:</span>
            <div class="flex">
              <!--<vs-checkbox v-model="usuario.habilitado" name="habilitado">Habilitado</vs-checkbox>-->
              <ul class="centerx">
               <li>
                <vs-radio color="success" v-model="item.habilitado" vs-value="1" >Activo</vs-radio>
              </li>
              <li>
                <vs-radio color="danger" v-model="item.habilitado" vs-value="0" >Inactivo</vs-radio>
              </li>
              </ul>
            </div>
          </div>
          </div>
          
          </div>
          </form>
        </tab-content>
      </form-wizard>
    </div>
                
        </vs-popup>
        <!-- POP UP -->
  

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
              <vx-tooltip color="primary" text="Agregar usuario">
              <span class="ml-2 text-base text-primary">Agregar Usuario</span>
              </vx-tooltip>
          </div>
        </div>

       <div class="drowoptions" >  
       <!-- FILTRO -->
        <vs-dropdown vs-trigger-click class="cursor-pointer mb-4 mr-4">
          <div class="p-4 border border-solid d-theme-border-grey-light rounded-full d-theme-dark-bg cursor-pointer flex items-center justify-between font-medium">
            <span class="mr-2">Ver | Estado</span>
            <feather-icon icon="ChevronDownIcon" svgClasses="h-4 w-4" />
          </div>
          <!-- <vs-button class="btn-drop" type="line" color="primary" icon-pack="feather" icon="icon-chevron-down"></vs-button> -->
          <vs-dropdown-menu>
               <vs-dropdown-item @click.prevent="$filtrar('habilitado', null)">
                <span>Todos</span>
              </vs-dropdown-item>
               <vs-dropdown-item @click.prevent="$filtrar('habilitado', 1)">
                <span>Activo</span>
              </vs-dropdown-item>
              <vs-dropdown-item @click.prevent="$filtrar('habilitado', 0)">
                <span>Inactivo</span>
              </vs-dropdown-item>
          </vs-dropdown-menu>
        </vs-dropdown>

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

      </div>

      <template slot="thead">
        <vs-th sort-key="items-id">ID</vs-th>
        <vs-th sort-key="items-name">Nombre</vs-th>
        <vs-th sort-key="items-lastname">Apellido</vs-th>
        <vs-th sort-key="items-email">Email</vs-th>
        <vs-th sort-key="items-rol">Rol</vs-th>
		    <vs-th sort-key="items-status">Estado</vs-th>
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
                <p class="items-name">{{ tr.name }}</p>
              </vs-td>
              <vs-td>
                <p class="items-lastname">{{ tr.lastname }}</p>
              </vs-td>
              <vs-td>
                <p class="items-email">{{ tr.email }}</p>
              </vs-td>
              
              <vs-td>
                <p class="items-rol">{{ tr.roles[0].name }}</p>
              </vs-td>
				      <vs-td>
                <vs-chip :color="getStatusColor(tr.habilitado)" class="items-status">{{ tr.habilitado == 1 ? 'Activo' : 'Inactivo'  }}</vs-chip>
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

// For custom error message
import { Validator } from 'vee-validate';
const dict = {
    custom: {
        name: {
            required: 'El nombre es requerido',
            alpha_spaces: "El nombre solo puede contener letras"
        },
        lastname: {
            required: 'El apellido es requerido',
            alpha_spaces: "El apellido solo puede contener letras"
        },
        rut: {
            required: 'El rut es requerido',
            alpha_dash: "Ingrese un rut valido"
        },
        email: {
            required: 'El email es requerido',
            email: "Ingrese un email valido"
        },
        roles: {
            required: 'El rol es requerido',
        },
        empresa: {
            required: 'La empresa es requerido',
        },
        telefono: {
            required: 'El telefono es requerido',
            numeric: "El numero de telefono debe ser valido"
        },
        password: {
            required: 'La password es requerida',
            min: "La password debe tener minimo 8 caracteres",
        },
        password_confirmation: {
            required: 'Debe ingresar la confirmacion de la password',
            min: "La confirmacion de la password debe tener minimo 8 caracteres",
            confirmed: "Las password debe ser iguales"
        },
        moviles: {
            min_value: "Debe seleccionar al menos 1 movil",
        }
    }
};

// register custom messages
Validator.localize('en', dict);

export default {
  components: { 
    FormWizard,
    TabContent
  },
  data() {
    return {
      ruta:'/users/users/',
      selected: [],
      items: [],
      itemsOriginal: [],
      itemsPerPage: 4,
      isMounted: false,
      ite : "",
      ind : "",
      popupActive: false,
      popupMoviles: false, 
      item : {
        habilitado:1,
        moviles: []
      },
      modoEditar: false,      
      roles_choices: [],
      empresa_choices: [],
      moviles_choices : [],
      exportData : [],
      aux: 0,    
      movilescount:0, 

    }
  },
  computed: {
    currentPage() {
      if(this.isMounted) {
        return this.$refs.table.currentx
        return this.$refs.tablemoviles.currentx        
      }
      return 0
    },
  },
  methods: {
    setSelected(value) {
            if(value <= 2){
             this.item.empresa_id = 1;
              if(value != 2 ){//&& this.modoEditar == false
                this.item.moviles = [];
                this.movilescount = 0;
              }  
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
                      if(this.modoEditar == false){
                          this.$submitAgregar();
                      }else{
                          this.$submitActualizar();
                      }
                        
                        resolve(true)
                    } else {
                        reject("correct all values");
                    }
                })
            })
        },
    getStatusColor(status) {
      if(status == 1) return "success"
      if(status == 0) return "danger"
      return "danger"
    },
     getStatusColorMovil(status) {
      if(status > 0) return "success"
      if(status == 0) return "danger"
      return "danger"
    },
     listadoMoviles() {
       setTimeout(() => {
                this.popupMoviles = true;
      }, 300);
    },
    handleSelectedMoviles(tr) {
      this.movilescount = this.item.moviles.length;
    },
     refrescaOtrosDatos() {
        //Carga Roles   
        const thisIns = this; 
        this.$http.get('roles/roles')
          .then(function (response) {
            thisIns.roles_choices = response.data.items //thisIns.formatData(response.data.users) formatear data
          })
          .catch(function (error) {
            
            thisIns.$msjError(error);            

          });
        //Carga Empresa
          this.$http.get('users/empresas')
          .then(function (response) {
            thisIns.empresa_choices = response.data.items //thisIns.formatData(response.data.users) formatear data
          })
          .catch(function (error) {
            
            thisIns.$msjError(error);          

          });

          //Carga Moviles
          this.$http.get('/driver/cars')
          .then(function (response) {
            thisIns.moviles_choices = response.data.items //thisIns.formatData(response.data.users) formatear data
            
          })
          .catch(function (error) {
            
            thisIns.$msjError(error);          

          });
      },
    editar(item){      
      const thisIns = this; 
      this.initValues();
      this.modoEditar = true; 
      this.item.empresa_id = item.empresa_id;
      this.item.email = item.email;
      this.item.name = item.name;
      this.item.lastname = item.lastname;
      this.item.rut = item.rut;
      this.item.telefono = item.telefono;
      this.item.habilitado = item.habilitado;
      this.item.roles = item.roles[0].id;
      this.item.id = item.id;
    
      //Carga Moviles usuario
          this.$http.get('/users/cars/' + item.id)
          .then(function (response) {

            var aux = thisIns.moviles_choices;
            response.data.items.forEach(function(element) {
              
              thisIns.item.moviles.push(aux.find(x => x.id === element.id));
              
            });

            thisIns.movilescount = thisIns.item.moviles.length;
            //thisIns.item.moviles = response.data.items;
          
          })
          .catch(function (error) {
            
            thisIns.$msjError(error);          

          });
      
      //this.selected = [];

      setTimeout(() => {
          this.popupActive=true;
      }, 500);
      
    },

    initValues() {

      //this.$refs.wizard.navigateToTab(0);
      this.item = {
        habilitado:1,
        moviles: [],
      };
      this.movilescount = 0;
      this.item.roles = '';
      this.item.empresa_id = '';
      this.errors.clear();
      this.$refs.wizard.reset();
      //this.modoEditar = false;    
    },
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

.drowoptions{
align-items: left !important;

}

</style>
