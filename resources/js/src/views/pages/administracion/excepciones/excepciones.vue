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
              <vx-tooltip color="primary" text="Agregar excepcion">
              <span class="ml-2 text-base text-primary">Agregar Excepcion</span>
              </vx-tooltip>
          </div>
        </div>

         <!-- POP UP -->
        <vs-popup class="holamundo"  ref="modal" :title="(modoEditar == false ? 'AGREGAR EXCEPCION' : 'ACTUALIZAR EXCEPCION')"  
        :active.sync="popupActive"  @close="$close($event)">
            <div class="p-5">
               <div>
                <vs-divider color="primary"><h5>Excepciones</h5></vs-divider>
              </div>
              <!-- RUT -->
              <vs-input v-validate="'required'" label-placeholder="Rut" name="rut" v-model="item.rut" 
              :danger="(errors.first('rut') ? true : false)" val-icon-danger="clear" class="mt-5 w-full" />
              <span class="text-danger text-sm" v-show="errors.has('rut')" >{{ errors.first('rut') }}</span>
              <!-- DIRECCION -->
              <vs-input v-validate="'required'" type="hidden" name="direccion" v-model="item.direccion"/>
              

                <div class="vs-component vs-con-input-label vs-input mt-5 w-full vs-input-primary is-label-placeholder">
                <div class="vs-con-input">

                <input type="text" required ref="autocomplete"  name="direccion" v-model="item.direccion" 
                  :class="(item.direccion ? 'vs-inputx vs-input--input normal hasValue' : 'vs-inputx vs-input--input normal')" 
                 
                  :style="(errors.first('direccion') ? 'border: 1px solid rgba(var(--vs-danger),1)!important;' : 'border: 1px solid rgba(0, 0, 0, 0.2);')" 
                 placeholder=""  >

                   <span class="input-span-placeholder vs-input--placeholder normal normal vs-placeholder-label">
        Direccion
      </span>
                  <span v-show="errors.has('direccion')" class="input-icon-validate vs-input--icon-validate" style="background: rgba(var(--vs-danger),.2);">
                    <i class="vs-icon notranslate icon-scale material-icons null" valiconpack="material-icons" style="color: rgba(var(--vs-danger),1);">clear</i></span>
                
               
                
                </div>
                <span class="text-danger text-sm" v-show="errors.has('direccion')" >{{ errors.first('direccion') }}</span>
                </div>


            <div class="flex flex-wrap items-center justify-center p-6" slot="footer">
              <vs-button v-if="modoEditar == true" class="mr-3" @click.prevent="$submitActualizar()" >ACTUALIZAR EXCEPCION</vs-button>
              <vs-button v-else class="mr-3" @click.prevent="$submitAgregar()">AGREGAR EXCEPCION</vs-button>
              
           
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
        <vs-th sort-key="items-rut">Rut</vs-th>
        <vs-th sort-key="items-direccion">Direccion</vs-th>
        <vs-th sort-key="items-accion">Accion</vs-th>
      </template>

        <template slot-scope="{data}">
          <tbody>
            <vs-tr :data="tr" :key="indextr" v-for="(tr, indextr) in data">

              <vs-td>
                <p class="items-id font-medium">{{ tr.id }}</p>
              </vs-td>

              <vs-td>
                <p class="items-rut">{{ tr.rut }}</p>
              </vs-td>
                 <vs-td>
                <p class="items-rut">{{ tr.direccion }}</p>
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

export default {
  components: { 
  },
  data() {
    return {
      ruta:'/excepciones/excepciones/',
      selected: [],
      items: [],
      itemsOriginal: [],
      itemsPerPage: 4,
      isMounted: false,
      ite : "",
      ind : "",
      popupActive: false,
      item : {},
      modoEditar: false,      
      exportData : [],
      aux: 0,

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
   asignaDireccion() {
     //console.log(this.autocomplete.getPlace().formatted_address);
     this.item.direccion = this.autocomplete.getPlace().formatted_address;
    },
     editar(item){      
      this.initValues();
      this.modoEditar = true; 
      this.item.id = item.id;
      this.item.rut = item.rut;
      this.item.direccion = item.direccion;
      this.item.lat = item.lat;
      this.item.lng = item.lng;
      this.selected = [];
      this.popupActive=true;
    },
    initValues() {

      this.item = {};
      this.errors.clear();

    },
  },
  created() {
      this.$refrescaTabla();
  },
  mounted() {
    this.isMounted = true;
    this.autocomplete = new google.maps.places.Autocomplete((this.$refs.autocomplete),{
      types: ['geocode']
      
      });
    this.autocomplete.addListener('place_changed', this.asignaDireccion);
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

.pac-container {
    background-color: #FFF;
    z-index: 999999;
    position: fixed;
    display: inline-block;
    float: left;
}
</style>
