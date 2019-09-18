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
              <vx-tooltip color="primary" text="Agregar rol">
              <span class="ml-2 text-base text-primary">Agregar Rol</span>
              </vx-tooltip>
          </div>
        </div>

        <!-- POP UP -->
        <vs-popup class="holamundo"  ref="modal" :title="(modoEditar == false ? 'AGREGAR ROL' : 'ACTUALIZAR ROL')"  :active.sync="popupActive"  
         @close="$close($event)">
            <div class="p-5">
              <div>
                <vs-divider color="primary"><h5>Roles</h5></vs-divider>
              </div>
              <!-- NAME -->
              <vs-input v-validate="'required|alpha_dash'" label-placeholder="Nombre" name="name" v-model="item.name" 
              :danger="(errors.first('name') ? true : false)" val-icon-danger="clear" class="mt-2 w-full" />
              <span class="text-danger text-sm" v-show="errors.has('name')">{{ errors.first('name') }}</span>

              <!-- PERMISOS
              <vs-select
                      multiple
                      class="selectExample"
                      label="Permisos"
                      v-model="select2"
                      >
                      <div :key="index" v-for="item,index in options1">
                        <vs-select-group :title="item.title" v-if="item.group">
                          <vs-select-item :key="index" :value="item.value" :text="item.text"  v-for="item,index in item.group"/>
                        </vs-select-group>
                      </div>
                  </vs-select> -->
                  <br/>
                <label class="typo__label" style="font-size: 12px;">Permisos</label>
                  <br/><br/> 
                <vs-table :data="options" pagination   max-items="7" class="permisos">

                      <template slot="thead">
                        <vs-th>Modulo</vs-th>
                        <vs-th>Agregar</vs-th>
                        <vs-th>Crear</vs-th>
                        <vs-th>Inicio</vs-th>
                        <vs-th>Actualizar</vs-th>
                        <vs-th>Ver</vs-th>
                        <vs-th>Eliminar</vs-th>
                        <vs-th>Editar</vs-th>
                      </template>

                      <template slot-scope="{data}">
                        <vs-tr :key="indextr" v-for="(tr, indextr) in data">
                          
                          <vs-td :data="data[indextr].modulos">
                            {{ data[indextr].modulos }}
                          </vs-td>
                                                              
                            <vs-td v-for="(td, index) in data[indextr].roles" :key="index">
                              <vs-checkbox name="permisos" v-validate="'required'" icon-pack="feather" icon="icon-check" v-model="item.permisos" :vs-value="td.id"/>
                            </vs-td>
                     
                        </vs-tr>
                      </template>

                    </vs-table>

               <div>
              <!--  <label class="typo__label" style="font-size: 12px;">Permisos</label>
                <multiselect v-model="item.permisos" :options="options" :multiple="true" 
                group-values="roles" group-label="modulos" :group-select="true" v-validate="'required'" 
                track-by="id" label="name" :searchable="false" :show-labels="true" name="permisos" placeholder="Seleccione una opcion" 
                :close-on-select="false" >
                <template slot="selection" slot-scope="{ values, search, isOpen }"><span class="multiselect__single" 
                v-if="values.length &amp;&amp; !isOpen">{{ values.length }} opciones seleccionadas</span></template>
                
                </multiselect>-->
          
                <span class="text-danger text-sm" v-show="errors.has('permisos')">{{ errors.first('permisos') }}</span>
              
                
              </div>   

              <!--<br/> 
              <div class="grid-layout-container alignment-block">
              <span class="text-sm">Permisos</span>
                <template>
                <v-tree  name="permisos" ref='tree' :canDeleteRoot="false" :data='treeData' 
                :draggable='false'  :halfcheck='true' :multiple="true"/>
              </template>
             
               <vs-row vs-align="flex-end" vs-type="flex" vs-justify="center" vs-w="12">
                    <vs-col vs-type="flex" vs-justify="center" vs-align="center" vs-w="2">
                    Modulo
                    </vs-col>
                    <vs-col vs-type="flex" vs-justify="center" vs-align="center" vs-w="2">
                     Ver
                    </vs-col>
                    <vs-col vs-type="flex" vs-justify="center" vs-align="center" vs-w="2">
                     Crear
                    </vs-col>
                    <vs-col vs-type="flex" vs-justify="center" vs-align="center" vs-w="2">
                     Editar
                    </vs-col>
                    <vs-col vs-type="flex" vs-justify="center" vs-align="center" vs-w="2">
                     Eliminar
                    </vs-col>
                    <vs-col vs-type="flex" vs-justify="center" vs-align="center" vs-w="2">
                     Detalle
                    </vs-col>
                  </vs-row>
                  
                   <template>
              <v-select-tree :data='treeData' v-model='initSelected' :multiple="true"/>
            </template>
              </div>-->

             



           
            <div class="flex flex-wrap items-center justify-center p-6" slot="footer">
              <vs-button v-if="modoEditar == true" class="mr-3" @click.prevent="$submitActualizar()" >ACTUALIZAR ROL</vs-button>
              <vs-button v-else class="mr-3" @click.prevent="$submitAgregar()">AGREGAR ROL</vs-button>
              
           
              <vs-button type="border" color="danger" ref="btncancelar" @click.prevent="$cancelarPopUp()">CANCELAR</vs-button>
            </div>
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
        <vs-th sort-key="items-name">Nombre</vs-th>
       <!--  <vs-th sort-key="roles-created_at">Creado</vs-th>
        <vs-th sort-key="roles-updated_at">Actualizado</vs-th>-->
        <vs-th sort-key="items-accion">Accion</vs-th>
      </template>

        <template slot-scope="{data}">
          <tbody>
            <vs-tr :data="tr" :key="indextr" v-for="(tr, indextr) in data">

              <vs-td>
                <p class="items-id font-medium">{{ tr.id }}</p>
              </vs-td>
               <vs-td>
                <p class="items-rol">{{ tr.name }}</p>
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
import Multiselect from 'vue-multiselect'
export default {
  components: { Multiselect 
  },
  data() {
    return {
      ruta:'/roles/roles/',
      selected: [],
      items: [],
      itemsPerPage: 4,
      isMounted: false,
      ite : "",
      ind : "",
      popupActive: false,
      item : {
        permisos:[],
      },
      modoEditar: false,      
      exportData : [],
      permisos : [],
      aux: 0,
      options: [],
 

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
     refrescaOtrosDatos() {
        //Carga Roles   
        const thisIns = this; 
        //thisIns.treeData = {};
        this.$http.get('permisos/permisos')
          .then(function (response) {
            //thisIns.permisos = response.data.permisos //thisIns.formatData(response.data.users) formatear data
             thisIns.options = response.data.permisos_aux
            
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
    arrayColumn(array, columnName) {
        return array.map(function(value,index) {
        return value[columnName];
      })
    },
    editar(item){      
      
      this.initValues();
      this.modoEditar = true; 
      //console.log(item.roles[0].id);
      this.item.name = item.name;
      this.item.id = item.id;
      //Nuevo
      //this.item.permisos = item.permisos;
      const ids = this.arrayColumn(item.permisos, 'id');
      this.item.permisos = ids;
      //Nuevo
      this.selected = [];
      this.popupActive=true;
    },    
    initValues() {
      this.item = {
        permisos:[],
      };
      this.errors.clear();
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
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>


<style lang="scss">
.selectExample {
  margin: 0px;
}
.con-select-example {
  display: flex;
  align-items: center;
  justify-content: center;
  margin: 10px;
}
.con-select .vs-select {
  width: 100%;
  margin: 20px;
}
@media (max-width: 550px) {
  .con-select {
    flex-direction: column;
  }
  .con-select .vs-select {
    width: 100%
  }
}
.multiselect__tags{
      border: 1px solid rgba(0, 0, 0, 0.2) !important;
      font-size: 12px;
       z-index: 999999 !important;
}

.multiselect__single {
     color: #adadad !important;
     font-size: 12px !important;
      z-index: 999999 !important;
}

.multiselect__placeholder {
     color: #adadad !important;
      z-index: 999999 !important;
    font-size: 12px !important;
}


.multiselect--active {
   z-index: 999999 !important;
  
}
.multiselect__option {  
    cursor: pointer !important;
    overflow: hidden !important;
    z-index: 999999 !important;
    text-overflow: ellipsis !important;
    transition: all .2s ease;
        font-size: 12px; 
    
}
.permisos {
  .vs-checkbox {
      justify-content: center !important; 
    }
      th .vs-table-text {
        justify-content: center !important;
  }
}
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
</style>
