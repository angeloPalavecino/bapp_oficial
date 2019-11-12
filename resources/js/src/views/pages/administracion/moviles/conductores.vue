<template>
  <div id="data-list-list-view" class="data-list-container">

    <!-- POP UP -->
    <vs-popup
      class="holamundo"
      ref="modal"
      :title="(modoEditar == false ? 'AGREGAR CONDUCTOR' : 'ACTUALIZAR CONDUCTOR')"
      :active.sync="popupActive"
      @close="$close($event)"
    >
      <div class="mt-5">
          <div class="p-5">
              <div>
                <vs-divider color="primary"><h5>Conductores</h5></vs-divider>
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
                      <vs-input
                        label-placeholder="N° Movil"
                        v-model="item.numero_movil"
                        class="w-full"
                        name="numero_movil"
                        v-validate="'required'"
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
                        v-validate="'required'"
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
                        v-validate="'required'"
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
                      <vs-input
                        label-placeholder="N° Asientos"
                        v-model="item.asientos"
                        class="w-full"
                        name="asientos"
                        v-validate="'required|numeric'"
                        :danger="(errors.first('asientos') ? true : false)"
                        val-icon-danger="clear"
                      />
                      <span class="text-danger">{{ errors.first('asientos') }}</span>
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


     
            <div class="flex flex-wrap items-center justify-center p-6" slot="footer">
              <vs-button v-if="modoEditar == true" class="mr-3" @click.prevent="$submitActualizar()" >ACTUALIZAR CONDUCTOR</vs-button>
              <vs-button v-else class="mr-3" @click.prevent="$submitAgregar()">AGREGAR CONDUCTOR</vs-button>
              
           
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
        <vs-th sort-key="items-movil">N° Movil</vs-th>
        <vs-th sort-key="items-patente">Patente</vs-th>
        <vs-th sort-key="items-tipo">Tipo</vs-th>
        <vs-th sort-key="items-asientos">N° Asientos</vs-th>
        <vs-th sort-key="items-accion">Accion</vs-th>
      </template>

      <template slot-scope="{data}">
        <tbody>
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
              <p class="items-tipo">{{ tr.tipo }}</p>
            </vs-td>
            <vs-td>
                <p class="items-asientos">{{ tr.asientos }}</p>
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
        </tbody>
      </template>
    </vs-table>
  </div>
</template>

<script>
// For custom error message
import { Validator } from "vee-validate";
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
    numero_movil: {
      required: "Los asientos son requerido",
      numeric: "La cantidad de asientos debe ser numerico"
    }
  }
};

// register custom messages
Validator.localize("en", dict);

export default {
  components: {
  },
  data() {
    return {
      ruta: "/driver/moviles/",
      selected: [],
      items: [],
      itemsOriginal: [],
      itemsPerPage: 4,
      isMounted: false,
      ite: "",
      ind: "",
      popupActive: false,
      item: {
        habilitado: 1,
      },
      modoEditar: false,
      exportData: [],
      driver_choices: [],
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
     refrescaOtrosDatos() {
      //Carga Conductores
      const thisIns  = this;
      this.$http
        .get("driver/driver")
        .then(function(response) {
          thisIns.driver_choices = response.data.items; //thisIns.formatData(response.data.users) formatear data
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
      this.item.driver_id = item.driver_id;

      this.popupActive = true;
    },
    initValues() {
      //this.$refs.wizard.navigateToTab(0);
      this.item = {
        habilitado: 1,
      };
      this.errors.clear();
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

