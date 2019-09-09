<template>
  <div id="data-list-list-view" class="data-list-container">
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

        <!-- POP UP -->
        <vs-popup
          fullscreen
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
                    <div class="vx-col md:w-1/2 w-full mt-5">
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
                    <div class="vx-col md:w-1/2 w-full mt-5">
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
                    <div class="vx-col md:w-1/2 w-full mt-5">
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
                    <div class="vx-col md:w-1/2 w-full mt-5">
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
                    <div class="vx-col md:w-1/2 w-full mt-5">
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
                    <div class="vx-col md:w-1/2 w-full mt-5">
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
                    <div class="vx-col md:w-1/2 w-full mt-5">
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
                    <div class="vx-col md:w-1/2 w-full mt-5">
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
                    <div class="vx-col md:w-1/2 w-full mt-5">
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
                    <div class="vx-col md:w-1/2 w-full mt-5">
                      <vs-input
                        label="Tipo Vehículo"
                        v-model="car.tipo"
                        class="w-full"
                        name="tipo"
                        v-validate="'required'"
                        :danger="(errors.first('step-2.tipo') ? true : false)"
                        val-icon-danger="clear"
                      />
                      <span class="text-danger">{{ errors.first('step-2.tipo') }}</span>
                    </div>
                    <div class="vx-col md:w-1/2 w-full mt-5">
                      <vs-input
                        label="Marca"
                        v-model="car.marca"
                        class="w-full"
                        name="marca"
                        v-validate="'required'"
                        :danger="(errors.first('step-2.marca') ? true : false)"
                        val-icon-danger="clear"
                      />
                      <span class="text-danger">{{ errors.first('step-2.marca') }}</span>
                    </div>
                    <div class="vx-col md:w-1/2 w-full mt-5">
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
                    <div class="vx-col md:w-1/2 w-full mt-5">
                      <vs-input
                        label="Año"
                        v-model="car.ano"
                        class="w-full"
                        name="ano"
                        v-validate="'required'"
                        :danger="(errors.first('step-2.ano') ? true : false)"
                        val-icon-danger="clear"
                      />
                      <span class="text-danger">{{ errors.first('step-2.ano') }}</span>
                    </div>
                    <div class="vx-col md:w-1/2 w-full mt-5">
                      <vs-input
                        label="N° Motor"
                        v-model="car.motor"
                        class="w-full"
                        name="motor"
                        v-validate="'required'"
                        :danger="(errors.first('step-2.motor') ? true : false)"
                        val-icon-danger="clear"
                      />
                      <span class="text-danger">{{ errors.first('step-2.motor') }}</span>
                    </div>
                    <div class="vx-col md:w-1/2 w-full mt-5">
                      <vs-input
                        label="N° Patente"
                        v-model="car.patente"
                        class="w-full"
                        name="patente"
                        v-validate="'required'"
                        :danger="(errors.first('step-2.patente') ? true : false)"
                        val-icon-danger="clear"
                      />
                      <span class="text-danger">{{ errors.first('step-2.patente') }}</span>
                    </div>
                    <div class="vx-col md:w-1/2 w-full mt-5">
                      <vs-input
                        label="Color"
                        v-model="car.color"
                        class="w-full"
                        name="color"
                        v-validate="'required'"
                        :danger="(errors.first('step-2.color') ? true : false)"
                        val-icon-danger="clear"
                      />
                      <span class="text-danger">{{ errors.first('step-2.color') }}</span>
                    </div>
                    <div class="vx-col md:w-1/2 w-full mt-5">
                      <vs-input
                        label="N° Asientos"
                        v-model="car.asientos"
                        class="w-full"
                        name="asientos"
                        v-validate="'required'"
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
      required: "Los asientos son requerido"
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
      item: {},
      driver: {},
      car: {},
      user: {},
      modoEditar: false,
      roles_choices: [],
      empresa_choices: [],
      exportData: [],
      aux: 0
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
              this.$submitAgregar();
            } else {
              this.$submitActualizar();
            }

            resolve(true);
          } else {
            reject("correct all values");
          }
        });
      });
    },
    getStatusColor(status) {
      if (status == 1) return "success";
      if (status == 0) return "danger";
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
    },
    editar(item) {
      console.log(item);
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
      this.user = {};
      this.errors.clear();
      this.$refs.wizard.reset();
      //this.modoEditar = false;
    },
    successUpload() {
      return true;
      // this.$vs.notify({
      //   color: "success",
      //   title: "Upload Success",
      //   text: "Lorem ipsum dolor sit amet, consectetur"
      // });
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

