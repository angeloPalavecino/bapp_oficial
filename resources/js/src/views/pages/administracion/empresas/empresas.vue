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
          text-primary border border-solid border-primary" @click="agregarPopUp()" > <!-- @click="addNewDataSidebar = true" -->
              <feather-icon icon="PlusIcon" svgClasses="h-4 w-4" />
              <vx-tooltip color="primary" text="Agregar empresa">
              <span class="ml-2 text-base text-primary">Agregar Empresa</span>
              </vx-tooltip>
          </div>
       </div>

        <!-- POP UP -->
        <vs-popup class="holamundo"  ref="modal" :title="(modoEditar == false ? 'AGREGAR EMPRESA' : 'ACTUALIZAR EMPRESA')" 
         :active.sync="popupActive"  @close="$close($event)">
        <div class="mt-1">
      <form-wizard color="rgba(var(--vs-primary), 1)" errorColor="rgba(var(--vs-danger), 1)" 
      :title="(modoEditar == false ? 'AGREGAR EMPRESA' : 'ACTUALIZAR EMPRESA')" 
      :subtitle="(modoEditar == false ? 'Ingrese todos los campos para ingresar la empresa' : 'Modifique los campos que desee actualizar')" 
      :finishButtonText="(modoEditar == false ? 'Agregar' : 'Actualizar')" ref="wizard">
        <tab-content title="Paso 1" class="mb-1" icon="feather icon-home" :before-change="validateStep1">
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

             <div class="vx-col md:w-1/2 w-full mt-6">
               <flat-pickr v-model="item.fecha_incorporacion" class="w-full select-large" placeholder="Fecha Incorporacion" 
                  name="fecha_incorporacion" v-validate="'required'" />
                  <span class="text-danger text-sm" >{{ errors.first('step-1.fecha_incorporacion') }}</span>
            
              </div>
               <div class="vx-col md:w-1 w-full">
              <div class="demo-alignment">
                <span>Habilitado:</span>
                <div class="flex">
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
        
        <!-- tab 2 content 
        <tab-content title="Paso 2" class="mb-5" icon="feather icon-credit-card" :before-change="validateStep2">
          <form data-vv-scope="step-2">
          <div>
               <vs-divider color="primary" ><h5>Servicio por plana</h5></vs-divider>    
          </div>
          <div class="vx-row">
             <div class="vx-col md:w-1/3 w-full mt-1">
                <vs-input label-placeholder="Valor" v-model="servicioplana.valormin" class="w-full" name="servicioplana_valormin" 
                v-validate="'required|decimal'"  size="small"
              :danger="(errors.first('step-2.servicioplana_valormin') ? true : false)" val-icon-danger="clear" />
                <span class="text-danger text-sm" >{{ errors.first('step-2.servicioplana_valormin') }}</span>
              </div>
              <div class="vx-col md:w-1/3 w-full mt-1">
                <vs-input label-placeholder="Desde" v-model="servicioplana.desde" class="w-full" name="servicioplana_desde" 
                v-validate="'required|decimal'" size="small" 
              :danger="(errors.first('step-2.servicioplana_desde') ? true : false)" val-icon-danger="clear" />
                <span class="text-danger text-sm" >{{ errors.first('step-2.servicioplana_desde') }}</span>
              </div>
              <div class="vx-col md:w-1/3 w-full mt-1">
                <vs-input label-placeholder="Hasta" v-model="servicioplana.hasta" class="w-full" name="servicioplana_hasta"
                 v-validate="'required|decimal'"  size="small"
              :danger="(errors.first('step-2.servicioplana_hasta') ? true : false)" val-icon-danger="clear" />
                <span class="text-danger text-sm" >{{ errors.first('step-2.servicioplana_hasta') }}</span>
              </div>
             
               <div class="vx-col md:w-1/3 w-full mt-1">
                <vs-input label-placeholder="Valor psj adicional" v-model="servicioplana.valorpsjadicional" class="w-full" name="servicioplana_valorpsjadicional" 
                v-validate="'required|decimal'" size="small" 
              :danger="(errors.first('step-2.servicioplana_valorpsjadicional') ? true : false)" val-icon-danger="clear" />
                <span class="text-danger text-sm" >{{ errors.first('step-2.servicioplana_valorpsjadicional') }}</span>
              </div>

              <div class="vx-col md:w-1/3 w-full mt-6">
                 <vs-radio color="success" v-model="servicioplana.tipo" vs-value="1" class="mt-1" >Programado</vs-radio>
                </div>

              <div class="vx-col md:w-1/3 w-full mt-6">
                <vs-radio color="danger" v-model="servicioplana.tipo" vs-value="0" class="mt-1">Transportado</vs-radio>
                 </div>






              <div class="vx-col md:w-1/3 w-full mt-1">
                <vs-input label-placeholder="Valor F.Zona 1" v-model="servicioplana.fz1" class="w-full" name="servicioplana_fz1" 
                v-validate="'required|decimal'"  size="small"
              :danger="(errors.first('step-2.servicioplana_fz1') ? true : false)" val-icon-danger="clear" />
                <span class="text-danger text-sm" >{{ errors.first('step-2.servicioplana_fz1') }}</span>
              </div>
               <div class="vx-col md:w-1/3 w-full mt-1">
                <vs-input label-placeholder="Valor F.Zona 2" v-model="servicioplana.fz2" class="w-full" name="servicioplana_fz2" 
                v-validate="'required|decimal'"  size="small"
              :danger="(errors.first('step-2.servicioplana_fz2') ? true : false)" val-icon-danger="clear" />
                <span class="text-danger text-sm" >{{ errors.first('step-2.servicioplana_fz2') }}</span>
              </div>
                <div class="vx-col md:w-1/3 w-full mt-1">
                <vs-input label-placeholder="Valor F.Zona 3" v-model="servicioplana.fz3" class="w-full" name="servicioplana_fz3" 
                v-validate="'required|decimal'"  size="small"
              :danger="(errors.first('step-2.servicioplana_fz3') ? true : false)" val-icon-danger="clear" />
                <span class="text-danger text-sm" >{{ errors.first('step-2.servicioplana_fz3') }}</span>
              </div>


    
          </div>

          <div>
               <vs-divider color="primary" ><h5>Servicio por pasajero</h5></vs-divider>    
          </div>
          <div class="vx-row">
               <div class="vx-col md:w-1/3 w-full mt-1">
                <vs-input label-placeholder="Valor F.Zona 1" v-model="serviciopasajero.valorfz1" class="w-full" name="serviciopasajero_valorfz1" 
                v-validate="'required|decimal'"  size="small"
              :danger="(errors.first('step-2.serviciopasajero_valorfz1') ? true : false)" val-icon-danger="clear" />
                <span class="text-danger text-sm" >{{ errors.first('step-2.serviciopasajero_valorfz1') }}</span>
              </div>
               <div class="vx-col md:w-1/3 w-full mt-1">
                <vs-input label-placeholder="Desde F.Zona 1" v-model="serviciopasajero.desdefz1" class="w-full" name="serviciopasajero_desdefz1" 
                v-validate="'required|decimal'"  size="small"
              :danger="(errors.first('step-2.serviciopasajero_desdefz1') ? true : false)" val-icon-danger="clear" />
                <span class="text-danger text-sm" >{{ errors.first('step-2.serviciopasajero_desdefz1') }}</span>
              </div>
                <div class="vx-col md:w-1/3 w-full mt-1">
                <vs-input label-placeholder="Hasta F.Zona 1" v-model="serviciopasajero.hastafz1" class="w-full" name="serviciopasajero_hastafz1" 
                v-validate="'required|decimal'"  size="small"
              :danger="(errors.first('step-2.serviciopasajero_hastafz1') ? true : false)" val-icon-danger="clear" />
                <span class="text-danger text-sm" >{{ errors.first('step-2.serviciopasajero_hastafz1') }}</span>
              </div>

              <div class="vx-col md:w-1/3 w-full mt-1">
                <vs-input label-placeholder="Valor F.Zona 2" v-model="serviciopasajero.valorfz2" class="w-full" name="serviciopasajero_valorfz2" 
                v-validate="'required|decimal'" size="small"
              :danger="(errors.first('step-2.serviciopasajero_valorfz2') ? true : false)" val-icon-danger="clear" />
                <span class="text-danger text-sm" >{{ errors.first('step-2.serviciopasajero_valorfz2') }}</span>
              </div>
               <div class="vx-col md:w-1/3 w-full mt-1">
                <vs-input label-placeholder="Desde F.Zona 2" v-model="serviciopasajero.desdefz2" class="w-full" name="serviciopasajero_desdefz2" 
                v-validate="'required|decimal'"  size="small"
              :danger="(errors.first('step-2.serviciopasajero_desdefz2') ? true : false)" val-icon-danger="clear" />
                <span class="text-danger text-sm" >{{ errors.first('step-2.serviciopasajero_desdefz2') }}</span>
              </div>
                <div class="vx-col md:w-1/3 w-full mt-1">
                <vs-input label-placeholder="Hasta F.Zona 2" v-model="serviciopasajero.hastafz2" class="w-full" name="serviciopasajero_hastafz2" 
                v-validate="'required|decimal'" size="small"
              :danger="(errors.first('step-2.serviciopasajero_hastafz2') ? true : false)" val-icon-danger="clear" />
                <span class="text-danger text-sm" >{{ errors.first('step-2.serviciopasajero_hastafz2') }}</span>
              </div>

              <div class="vx-col md:w-1/3 w-full mt-1">
                <vs-input label-placeholder="Valor F.Zona 3" v-model="serviciopasajero.valorfz3" class="w-full" name="serviciopasajero_valorfz3" 
                v-validate="'required|decimal'" size="small"
              :danger="(errors.first('step-2.serviciopasajero_valorfz3') ? true : false)" val-icon-danger="clear" />
                <span class="text-danger text-sm" >{{ errors.first('step-2.serviciopasajero_valorfz3') }}</span>
              </div>
               <div class="vx-col md:w-1/3 w-full mt-1">
                <vs-input label-placeholder="Desde F.Zona 3" v-model="serviciopasajero.desdefz3" class="w-full" name="serviciopasajero_desdefz3" 
                v-validate="'required|decimal'" size="small"
              :danger="(errors.first('step-2.serviciopasajero_desdefz3') ? true : false)" val-icon-danger="clear" />
                <span class="text-danger text-sm" >{{ errors.first('step-2.serviciopasajero_desdefz3') }}</span>
              </div>
                <div class="vx-col md:w-1/3 w-full mt-1">
                <vs-input label-placeholder="Hasta F.Zona 3" v-model="serviciopasajero.hastafz3" class="w-full" name="serviciopasajero_hastafz3" 
                v-validate="'required|decimal'" size="small"
              :danger="(errors.first('step-2.serviciopasajero_hastafz3') ? true : false)" val-icon-danger="clear" />
                <span class="text-danger text-sm" >{{ errors.first('step-2.serviciopasajero_hastafz3') }}</span>
              </div>
            </div>

                    <div>
               <vs-divider color="primary" ><h5>Servicio por KMS</h5></vs-divider>    
          </div>
          <div class="vx-row">
               <div class="vx-col md:w-1/3 w-full mt-1">
                <vs-input label-placeholder="Valor" v-model="serviciokm.valor" class="w-full" name="serviciokms_valor" 
                v-validate="'required|decimal'"  size="small"
              :danger="(errors.first('step-2.serviciokms_valor') ? true : false)" val-icon-danger="clear" />
                <span class="text-danger text-sm" >{{ errors.first('step-2.serviciokms_valor') }}</span>
              </div>
               <div class="vx-col md:w-1/3 w-full mt-1">
                <vs-input label-placeholder="Valor portico" v-model="serviciokm.valorportico" class="w-full" name="serviciokms_valorportico" 
                v-validate="'required|decimal'"  size="small"
              :danger="(errors.first('step-2.serviciokms_valorportico') ? true : false)" val-icon-danger="clear" />
                <span class="text-danger text-sm" >{{ errors.first('step-2.serviciokms_valorportico') }}</span>
              </div>
                <div class="vx-col md:w-1/3 w-full mt-1">
                <vs-input label-placeholder="Distancia" v-model="serviciokm.distancia" class="w-full" name="serviciokms_distancia" 
                v-validate="'required|decimal'"  size="small"
              :danger="(errors.first('step-2.serviciokms_distancia') ? true : false)" val-icon-danger="clear" />
                <span class="text-danger text-sm" >{{ errors.first('step-2.serviciokms_distancia') }}</span>
              </div>
              <div class="vx-col md:w-1/3 w-full mt-1">

                     <vs-input-number v-model="serviciokm.pasajeros" class="w-full mt-5"
                 name="serviciokms_pasajeros" v-validate="'required'"  min="1" 
                 icon-inc="expand_less" icon-dec="expand_more" />

    

                <span class="text-danger text-sm" >{{ errors.first('step-2.serviciokms_pasajeros') }}</span>
              </div>
              <div class="vx-col md:w-1/3 w-full mt-1">
                <vs-input label-placeholder="Tiempo" v-model="serviciokm.tiempo" class="w-full" name="serviciokms_tiempo" 
                v-validate="'required|decimal'"  size="small"
              :danger="(errors.first('step-2.serviciokms_tiempo') ? true : false)" val-icon-danger="clear" />
                <span class="text-danger text-sm" >{{ errors.first('step-2.serviciokms_tiempo') }}</span>
              </div>
               <div class="vx-col md:w-1/3 w-full mt-1">
                <vs-input label-placeholder="Baja Bandera" v-model="serviciokm.bajabandera" class="w-full" name="serviciokms_bajabandera" 
                v-validate="'required|decimal'"  size="small"
              :danger="(errors.first('step-2.serviciokms_bajabandera') ? true : false)" val-icon-danger="clear" />
                <span class="text-danger text-sm" >{{ errors.first('step-2.serviciokms_bajabandera') }}</span>
              </div>

          </div>
   
          </form>
        </tab-content>-->

        <!-- tab 3 content -->
        <tab-content title="Paso 2" class="mb-5" icon="feather icon-users" :before-change="validateStep3">
          <form data-vv-scope="step-3">
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
              :danger="(errors.first('step-3.responsable_nombre') ? true : false)" val-icon-danger="clear" size="small"/>
              <span class="text-danger text-sm" >{{ errors.first('step-3.responsable_nombre') }}</span>
            </div>
            <div class="vx-col md:w-1/4 w-full mt-2">
              <vs-input label-placeholder="Telefono"  v-model="res.telefono" class="w-full" name="responsable_telefono" v-validate="'required|numeric'" 
              :danger="(errors.first('step-3.responsable_telefono') ? true : false)" val-icon-danger="clear" size="small"/>
              <span class="text-danger text-sm" >{{ errors.first('step-3.responsable_telefono') }}</span>
            </div>
             <div class="vx-col md:w-1/3 w-full mt-2">
              <vs-input label-placeholder="Email"  v-model="res.email" class="w-full" name="responsable_email" v-validate="'required|email'" 
              :danger="(errors.first('step-3.responsable_email') ? true : false)" val-icon-danger="clear" size="small"/>
              <span class="text-danger text-sm" >{{ errors.first('step-3.responsable_email') }}</span>
            </div>
            <div class="mt-6  items-center">
                 <vs-button radius color="primary" type="border" icon-pack="feather" icon="icon-user-minus" size="small" 
                  @click.prevent="quitarResponsable(indexres)"></vs-button>
             
            </div>
          </div>
          </div>
          </form>
        </tab-content>
        <!-- tab 4 content -->
         <tab-content title="Paso 3" class="mb-5" icon="feather icon-map-pin" :before-change="validateStep4">
          <form data-vv-scope="step-4">
          <div>
               <vs-divider color="primary" ><h5>Sucursales</h5></vs-divider>
              <div class="vx-col md:w-1/7 w-full mt-2">
                <vs-button color="primary" size="small" icon-pack="feather" icon="icon-plus" @click.prevent="agregarSucursal()"></vs-button>
              </div>      
          </div>
          <vs-divider color="primary" >Detalle</vs-divider>
          <div :data="suc" :key="indexsuc" v-for="(suc, indexsuc) in sucursales">
          <div class="vx-row" >
            
            <div class="vx-col md:w-1/4 w-full mt-2">
                  <vs-input label-placeholder="Nombre"  v-model="suc.nombre" class="w-full" name="sucursal_nombre" v-validate="'required'" 
                :danger="(errors.first('step-4.sucursal_nombre') ? true : false)" val-icon-danger="clear" size="small"/>
                
              </div>
            <div class="vx-col md:w-1/3 w-full mt-2">
                <vs-input v-model="suc.direccion" type="hidden"  name="sucursal_direccion" v-validate="'required'" />

                <div class="vs-component vs-con-input-label vs-input mt-5 w-full vs-input-primary is-label-placeholder">
                <div class="vs-con-input">

                <input type="text" required ref="autocomplete" :id="indexsuc" name="sucursal_direccion" v-model="suc.direccion" 
                  :class="(suc.direccion ? 'vs-inputx vs-input--input small hasValue' : 'vs-inputx vs-input--input small')" 
                 
                  :style="(errors.first('step-4.sucursal_direccion') ? 'border: 1px solid rgba(var(--vs-danger),1)!important;' : 'border: 1px solid rgba(0, 0, 0, 0.2);')" 
                 placeholder="" />
                 
                   <span class="input-span-placeholder vs-input--placeholder small small vs-placeholder-label">
        Direccion
      </span>
                  <span v-show="errors.has('step-4.sucursal_direccion')" class="input-icon-validate vs-input--icon-validate" style="background: rgba(var(--vs-danger),.2);">
                    <i class="vs-icon notranslate icon-scale material-icons null" valiconpack="material-icons" style="color: rgba(var(--vs-danger),1);">clear</i></span>
                  
                </div>
                </div>



                
              </div>
             <div class="vx-col md:w-1/6 w-full mt-6">
               <vs-radio v-model="suc.matriz" vs-value="1" class="mt-1" @change="cambioMatriz(indexsuc)">Matriz</vs-radio>
              </div>
            <div class="vx-col md:w-1/6 w-full mt-6">
                 <vs-button radius color="primary" type="border" icon-pack="feather" icon="icon-minus" size="small" 
                  @click.prevent="quitarSucursal(indexsuc)"></vs-button>   
            </div>            
          </div>
          </div>
          </form>
        </tab-content>
          <!-- tab 5 content -->
         <tab-content title="Paso 4" class="mb-5" icon="feather icon-refresh-cw" :before-change="validateStep5">
          <form data-vv-scope="step-5">
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
               <span class="text-danger text-sm" >{{ errors.first('step-5.cicprod_tipo') }}</span>

              
            </div>

            <div class="vx-col md:w-1/2  w-full mt-2">
              <span class=" text-sm" >Desde</span>
                 <vs-input-number v-model="cicloproduccion.inicio" 
                 name="cicprod_desde" v-validate="'required'"  min="1" max="31" 
                 icon-inc="expand_less" icon-dec="expand_more" :disabled="disabledcicpro"/>
                  <!--<flat-pickr v-model="cicloproduccion.inicio" class="w-full select-large" placeholder="Desde" 
                  name="cicprod_desde" v-validate="'required'" :disabled="disabledcicpro"/>-->
                  <span class="text-danger text-sm" >{{ errors.first('step-5.cicprod_desde') }}</span>
              </div>
             <div class="vx-col md:w-1/2 w-full mt-2">
             <span class=" text-sm" >Hasta</span>
              <vs-input-number v-model="cicloproduccion.fin" 
                 name="cicprod_hasta" v-validate="'required'"  min="1" max="31"
                 icon-inc="expand_less" icon-dec="expand_more" :disabled="disabledcicpro"/>
                 <!-- <flat-pickr v-model="cicloproduccion.fin" class="w-full select-large" placeholder="Hasta" 
                  name="cicprod_hasta" v-validate="'required'" :disabled="disabledcicpro"/>-->
                  <span class="text-danger text-sm" >{{ errors.first('step-5.cicprod_hasta') }}</span>
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
               <span class="text-danger text-sm" >{{ errors.first('step-5.cicfac_tipo') }}</span>


              </div>
            <div class="vx-col md:w-1/2  w-full mt-2">
                  <span class=" text-sm" >Desde</span>
                 <vs-input-number v-model="ciclofacturacion.inicio" 
                 name="cicfac_desde" v-validate="'required'"  min="1" max="31"
                 icon-inc="expand_less" icon-dec="expand_more" :disabled="disabledcicfac"/>

                  <!--<flat-pickr v-model="ciclofacturacion.inicio" class="w-full select-large" placeholder="Desde" 
                  name="cicfac_desde" v-validate="'required'" :disabled="disabledcicfac"/>-->
                  <span class="text-danger text-sm" >{{ errors.first('step-5.cicfac_desde') }}</span>
              </div>
             <div class="vx-col md:w-1/2 w-full mt-2">
                  <span class=" text-sm" >Hasta</span>
                   <vs-input-number v-model="ciclofacturacion.fin" 
                 name="cicfac_hasta" v-validate="'required'"  min="1" max="31"
                 icon-inc="expand_less" icon-dec="expand_more" :disabled="disabledcicfac"/>

                  <!--<flat-pickr v-model="ciclofacturacion.fin" class="w-full select-large" placeholder="Hasta" 
                  name="cicfac_hasta" v-validate="'required'" :disabled="disabledcicfac"/>-->
                  <span class="text-danger text-sm" >{{ errors.first('step-5.cicfac_hasta') }}</span>
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
        fecha_incorporacion: {
            required: 'La fecha de incorporacion es requerido',
        },
        /*servicioplana_desde:{
            required: 'El campo es requerido',
            decimal: 'Debe ingresar solo numeros'
        },
         servicioplana_hasta:{
            required: 'El campo es requerido',
            decimal: 'Debe ingresar solo numeros'
        },
         servicioplana_distancia:{
            required: 'El campo es requerido',
            decimal: 'Debe ingresar solo numeros'
        },
        servicioplana_valormin:{
            required: 'El campo es requerido',
            decimal: 'Debe ingresar solo numeros'
        },
        servicioplana_valorpsjadicional:{
            required: 'El campo es requerido',
            decimal: 'Debe ingresar solo numeros'
        },
        servicioplana_fz1:{
            required: 'El campo es requerido',
            decimal: 'Debe ingresar solo numeros'
        },
        servicioplana_fz2:{
            required: 'El campo es requerido',
            decimal: 'Debe ingresar solo numeros'
        },
        servicioplana_fz3:{
            required: 'El campo es requerido',
            decimal: 'Debe ingresar solo numeros'
        },
        serviciopasajero_valorfz1:{
            required: 'El campo es requerido',
            decimal: 'Debe ingresar solo numeros'
        },
        serviciopasajero_hastafz1:{
            required: 'El campo es requerido',
            decimal: 'Debe ingresar solo numeros'
        },
        serviciopasajero_desdefz1:{
            required: 'El campo es requerido',
            decimal: 'Debe ingresar solo numeros'
        },
        serviciopasajero_valorfz2:{
            required: 'El campo es requerido',
            decimal: 'Debe ingresar solo numeros'
        },
        serviciopasajero_hastafz2:{
            required: 'El campo es requerido',
            decimal: 'Debe ingresar solo numeros'
        },
        serviciopasajero_desdefz2:{
            required: 'El campo es requerido',
            decimal: 'Debe ingresar solo numeros'
        },
        serviciopasajero_valorfz3:{
            required: 'El campo es requerido',
            decimal: 'Debe ingresar solo numeros'
        },
        serviciopasajero_hastafz3:{
            required: 'El campo es requerido',
            decimal: 'Debe ingresar solo numeros'
        },
        serviciopasajero_desdefz3:{
            required: 'El campo es requerido',
            decimal: 'Debe ingresar solo numeros'
        },
         serviciopasajero_desdefz3:{
            required: 'El campo es requerido',
            decimal: 'Debe ingresar solo numeros'
        },

         serviciokms_valor:{
            required: 'El campo es requerido',
            decimal: 'Debe ingresar solo numeros'
        },
          serviciokms_distancia:{
            required: 'El campo es requerido',
            decimal: 'Debe ingresar solo numeros'
        },
          serviciokms_bajabandera:{
            required: 'El campo es requerido',
            decimal: 'Debe ingresar solo numeros'
        },
          serviciokms_valorportico:{
            required: 'El campo es requerido',
            decimal: 'Debe ingresar solo numeros'
        },
          serviciokms_tiempo:{
            required: 'El campo es requerido',
            decimal: 'Debe ingresar solo numeros'
        },
         serviciokms_pasajeros:{
            required: 'El campo es requerido',
            decimal: 'Debe ingresar solo numeros'
        },*/
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
        sucursal_nombre : {
            required: 'El nombre es requerido',
        },
        sucursal_direccion : {
            required: 'La direccion es requerido',
        },
        cicprod_tipo:{
            required: 'El tipo es requerido',
        },
        cicprod_desde:{
            required: 'El campo es requerido',
            numeric: 'Debe ingresar solo numeros'
        },
        cicprod_hasta:{
            required: 'El campo es requerido',
            numeric: 'Debe ingresar solo numeros'
        },
        cicfac_tipo:{
            required: 'El tipo es requerido',
        },
        cicfac_desde:{
            required: 'El campo es requerido',
            numeric: 'Debe ingresar solo numeros'
        },
        cicfac_hasta:{
            required: 'El campo es requerido',
            numeric: 'Debe ingresar solo numeros'
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
      itemsOriginal: [],
      itemsPerPage: 4,
      isMounted: false,
      ite : "",
      ind : "",
      popupActive: false,
      item : {
        habilitado : 1,
      },
      modoEditar: false,      
      roles_choices: [],
      empresa_choices: [],
      exportData : [],
      //autocompletes : [],
      aux: 0,
     /* tipoOpciones: [
        {text: "Por plana", value:"1"},
        {text: "Por pasajero", value:"2"},
        {text: "Por KM.", value:"3"},
       
      ],*/
      tipoCicloProduccion: [
        {text: "Por defecto (Mes Completo)", value: 31},
        {text: "Personalizado", value: 0},       
      ],
      tipoCiclofacturacion: [
        {text: "Por 15 dias", value: 15},
        {text: "Por 30 dias", value: 30},
        {text: "Por 90 dias", value: 90},
        {text: "Personalizado", value: 0},
       
      ],
      
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
            matriz: "",
        }],

      /*serviciokm : { pasajeros:1,},
      serviciopasajero : {       
      },
      servicioplana : {
        tipo: 1,
      },*/

      ciclofacturacion : { 
          inicio:1,
          fin:1,

      },
      disabledcicfac: true,
      cicloproduccion : {
          inicio:1,
          fin:1,
      },
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
    cambioMatriz(id) {
      const varInst = this;
      for (var x in varInst.sucursales) { 
        varInst.sucursales[x].matriz = "";
      }
      varInst.sucursales[id].matriz = 1;
    },
    changecicprod(event) {
      if(this.cicloproduccion.dias == 0) {
          this.disabledcicpro = false;
      }else{
          this.disabledcicpro = true;
          this.cicloproduccion.inicio = 1;
          this.cicloproduccion.fin = 1;
          this.errors.clear();
      }
      

    },
    changecicfac(event) {
       if(this.ciclofacturacion.dias == 0) {
          this.disabledcicfac = false;
      }else{
          this.disabledcicfac = true;
          this.ciclofacturacion.inicio = 1;
          this.ciclofacturacion.fin = 1;
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
            setTimeout(() => {
                this.inicializaAutocomplete();
            }, 500);
            
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
        /*validateStep2() {
            return new Promise((resolve, reject) => {
                this.$validator.validateAll("step-2").then(result => {
                    if (result) {           
                        resolve(true)
                    } else {
                        reject("correct all values");
                    }
                })
            })
        },*/
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
                        resolve(true)
                    } else {
                        reject("correct all values");
                    }
                })
            })
        },
        validateStep5() {
            return new Promise((resolve, reject) => {
                this.$validator.validateAll("step-5").then(result => {
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
    agregarPopUp(){
  
      this.initValues();
      this.modoEditar = false; 
      this.selected = [];
      setTimeout(() => {
          this.inicializaAutocomplete();
      }, 500);
      this.popupActive=true;
      
    },
    editar(item){      
      this.initValues();
      this.modoEditar = true; 
      this.item.rut = item.rut;
      this.item.dv = item.dv;
      this.item.razon_social = item.razon_social;
      this.item.giro = item.giro;
      this.item.id = item.id;
      this.item.habilitado = item.habilitado;
      this.item.fecha_incorporacion = item.fecha_incorporacion;
      this.traeOtrosDatos(item.id);   
      this.selected = [];
      setTimeout(() => {
          this.inicializaAutocomplete();
      }, 500);
      this.popupActive=true;
      
    },

    initValues() {
      this.datos = [];
      //this.$refs.wizard.navigateToTab(0);
      this.item = {
          habilitado: 1,
          fecha_incorporacion: "",
      };
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
            matriz: "",
        }];
     /* this.serviciokm = { pasajeros:1,},
      this.serviciopasajero = {},
      this.servicioplana = {
        tipo: 1,
      },*/
      this.ciclofacturacion = {
        inicio:1,
          fin:1,
      };
      this.cicloproduccion = {
        inicio:1,
          fin:1,
      };
      this.disabledcicfac = true,
      this.disabledcicpro = true,
      this.errors.fecha_incorporacion = "";
      this.errors.clear();
      this.$refs.wizard.reset();
 
    },
    //Enviar Registros
     submitRegistros(url) {

      const thisIns = this;
      this.datos = [];
      this.datos.push(this.item);
      //this.datos.push(this.servicioplana);
      //this.datos.push(this.serviciopasajero);
      //this.datos.push(this.serviciokm);
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
                
              this.$msjError(error);        

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
      //this.datos.push(this.servicioplana);
     //this.datos.push(this.serviciopasajero);
      //this.datos.push(this.serviciokm);
      this.datos.push(this.responsables);
      this.datos.push(this.sucursales);
      this.datos.push(this.ciclofacturacion);
      this.datos.push(this.cicloproduccion);

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
                
              this.$msjError(error);          

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
            
             //this.servicioplana = response.data.servicioplana[0]
             //this.serviciopasajero = response.data.serviciopasajero[0]
             //this.serviciokm = response.data.serviciokm[0]
             this.responsables = response.data.responsables
             this.sucursales = response.data.sucursales
             this.ciclofacturacion = response.data.cicfac[0]
             this.cicloproduccion = response.data.cicpro[0]
             response.data.cicfac[0].dias === 0 ? this.disabledcicfac = false : this.disabledcicfac = true;
             response.data.cicpro[0].dias === 0 ? this.disabledcicpro = false : this.disabledcicpro = true;
             
          })
          .catch(function (error) {
            
              this.$msjError(error);           

          });
      },
    inicializaAutocomplete(){
        const refs = this.$refs.autocomplete;
        const varInst = this;
        
        for (var x in refs) {
          const autocomplete = ""
          autocomplete  = new google.maps.places.Autocomplete((refs[x]),{types: ['geocode']});
          autocomplete.itemId = x;
          google.maps.event.addListener(autocomplete, 'place_changed', function () {
                varInst.sucursales[autocomplete.itemId].direccion = autocomplete.getPlace().formatted_address;
          });
          //this.autocompletes[x] = autocomplete;         
        }
 
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

  ::-webkit-input-placeholder { /* WebKit, Blink, Edge */
      color:    #c2c6dc !important;
  }
  :-moz-placeholder { /* Mozilla Firefox 4 to 18 */
    color:    #c2c6dc !important;
    opacity:  1;
  }
  ::-moz-placeholder { /* Mozilla Firefox 19+ */
    color:    #c2c6dc !important;
    opacity:  1;
  }
  :-ms-input-placeholder { /* Internet Explorer 10-11 */
    color:    #c2c6dc !important;
  }
  ::-ms-input-placeholder { /* Microsoft Edge */
    color:    #c2c6dc !important;
  }

  ::placeholder { /* Most modern browsers support this now. */
    color:   #c2c6dc !important;
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

.vs-popup {
  width: 730px !important;
}

.pac-container {
    background-color: #FFF;
    z-index: 999999;
    position: fixed;
    display: inline-block;
    float: left;
}

</style>
