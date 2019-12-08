<template>
  <div id="data-list-list-view" class="data-list-container">

   <!-- PATRONES -->
   <vs-popup class="holamundo"  ref="modalpatrones" title="Listado de patrones" :active.sync="popupPatrones">    
     
        <vs-table ref="tablepatrones" max-items="10" @input="handleSelectedPatrones" multiple pagination search 
        :data="patrones_choices" v-model="selectedPatrones">
           <template slot="header">
                    <h3>
                      Listado de patrones
                    </h3>
            </template>
          <template slot="thead">
            <vs-th >ID</vs-th>
            <vs-th >Codigo</vs-th>
            <vs-th >Descripcion</vs-th>
          </template>
          <template slot-scope="{data}"> 
                <vs-tr :data="trpat" :key="indextrpat" v-for="(trpat, indextrpat) in data">
                  <vs-td>
                    <p>{{ trpat.id }}</p>
                  </vs-td>
                  <vs-td>
                    <p>{{ trpat.codigo }}</p>
                  </vs-td>
                  <vs-td>
                    <p>{{ trpat.descripcion }}</p>
                  </vs-td>             
                </vs-tr>  
            </template>
        </vs-table>
    
    </vs-popup>
          <!-- FIN PATRONES -->

           <!-- POP UP -->
        <vs-popup class="holamundo"  ref="modal" :title="(modoEditar == false ? 'AGREGAR PATRON' : 'ACTUALIZAR PATRON')"  
        :active.sync="popupActive"   @close="$close($event)">
            <div class="p-5">
              <div>
                <vs-divider color="primary"><h5>Patrones</h5></vs-divider>
              </div>
              <div class="vx-row">
              <div class="vx-col md:w-1/3  w-full mt-2">
                    <!-- DESCRIPCION -->
                    <vs-input v-validate="'required'" label-placeholder="Descripcion" name="descripcion" v-model="item.descripcion" 
                    :danger="(errors.first('descripcion') ? true : false)" val-icon-danger="clear" class="mt-2 w-full" />
                    <span class="text-danger text-sm" v-show="errors.has('descripcion')">{{ errors.first('descripcion') }}</span>
                
              </div>
               <div class="vx-col md:w-1/3  w-full mt-2">
                    <!-- CODIGO -->
                    <vs-input v-validate="'required'" label-placeholder="Codigo" name="codigo" v-model="item.codigo" 
                    :danger="(errors.first('codigo') ? true : false)" val-icon-danger="clear" class="mt-2 w-full" />
                    <span class="text-danger text-sm" v-show="errors.has('codigo')">{{ errors.first('codigo') }}</span>
                
              </div>
              <div class="vx-col md:w-auto w-full ">
                    <!-- COLOR -->
                    <span class=" text-sm" >Color </span>
                    <input v-validate="'required'" name="color" v-model="item.color" type="color" 
                    :danger="(errors.first('color') ? true : false)" val-icon-danger="clear" class="w-full" />
                    <span class="text-danger text-sm" v-show="errors.has('color')">{{ errors.first('color') }}</span>

              </div>
              <div class="vx-col md:w-auto  w-full mt-4">
              <div class="p-3 mb-4 mr-4 rounded-lg cursor-pointer flex items-center justify-between text-lg font-medium text-base 
          text-primary border border-solid border-primary" @click="listadoPatrones"  > <!-- @click="addNewDataSidebar = true" -->
              <feather-icon icon="SearchIcon" svgClasses="h-4 w-4" />
              </div>
             </div>
              </div> 
               <!-- LAT -->
              <vs-input v-validate="'required'" type="hidden" name="lat" v-model="item.lat" />
                 <!-- LNG -->
              <vs-input v-validate="'required'" type="hidden" name="lng" v-model="item.lng" />

               <span class="text-danger text-sm" v-show="errors.has('lat') || errors.has('lng')">{{ errors.first('lat') }}</span>
            
            <div class="mt-5">
                  <div id="map"></div>
                </div>
             
             
     
            <div class="flex flex-wrap items-center justify-center p-6" slot="footer">
              <vs-button v-if="modoEditar == true && $can('patrones.update')" class="mr-3" @click.prevent="$submitActualizar()" >ACTUALIZAR PATRON</vs-button>
              <vs-button v-else-if="$can('patrones.store')" class="mr-3" @click.prevent="$submitAgregar()">AGREGAR PATRON</vs-button>
              
           
              <vs-button type="border" color="danger" ref="btncancelar" @click.prevent="$cancelarPopUp()">CANCELAR</vs-button>
            </div>
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
              <vs-dropdown-item v-if="$can('patrones.destroy')" @click.prevent="$accion(1)">
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
          <div v-if="$can('patrones.create')" class="p-3 mb-4 mr-4 rounded-lg cursor-pointer flex items-center justify-between text-lg font-medium text-base 
          text-primary border border-solid border-primary" @click="$agregarPopUp()" > <!-- @click="addNewDataSidebar = true" -->
              <feather-icon icon="PlusIcon" svgClasses="h-4 w-4" />
              <vx-tooltip color="primary" text="Agregar observacion">
              <span class="ml-2 text-base text-primary">Agregar Patron</span>
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
        <vs-th sort-key="items-descripcion">Descripcion</vs-th>
        <vs-th sort-key="items-codigo">Codigo</vs-th>
        <vs-th sort-key="items-accion">Accion</vs-th>
      </template>

        <template slot-scope="{data}">
          
            <vs-tr :data="tr" :key="indextr" v-for="(tr, indextr) in data">

              <vs-td>
                <p class="items-id font-medium">{{ tr.id }}</p>
              </vs-td>
                <vs-td>
                <p class="items-codigo">{{ tr.descripcion }}</p>
              </vs-td>
              <vs-td>
                <p class="items-descripcion">{{ tr.codigo  }}</p>
              </vs-td>
              <vs-td>
                   <div class="flex vx-col w-full sm:w-auto ml-auto mt-2 sm:mt-0">
                      <vx-tooltip color="primary" text="Editar">
                      <vs-button v-if="$can('patrones.edit')" radius color="primary" type="border" icon-pack="feather" icon="icon-edit-2" size="small" class="ml-3" @click="editar(tr)"></vs-button>
                       </vx-tooltip>
                      <vx-tooltip color="primary" text="Eliminar">
                       <vs-button v-if="$can('patrones.destroy')" radius color="primary" type="border" icon-pack="feather" icon="icon-trash" size="small" class="ml-3" @click="$submitEliminar(tr, indextr)"></vs-button>
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
import { Validator } from 'vee-validate';
const dict = {
    custom: {
       codigo: {
            required: 'El codigo es requerido',
        },
        color: {
            required: 'El color es requerido',
        },
        descripcion: {
            required: 'La descripcion es requerida',
        },
        lat: {
            required: 'El patron es requerido',
        },
        lng: {
            required: 'El patron es requerido',
        },
    }
};

// register custom messages
Validator.localize('en', dict);
export default {
  components: { 
  },
  data() {
    return {
      ruta:'/patrones/patrones/',
      selected: [],
      selectedPatrones: [],
      markersPat : [],
      items: [],
      itemsOriginal: [],
      itemsPerPage: 4,
      isMounted: false,
      ite : "",
      ind : "",
      popupActive: false,
      popupPatrones: false, 
      item : {
        lat: '', 
        lng: '',
        color: '#ffff00'
      },
      modoEditar: false,      
      exportData : [],
      aux: 0,
      center: { lat: -33.4573876, lng: -70.6440017 },
      zoom: 11,
      map: null, 
      element: null,
      patrones_choices: [],
      tipObj : null,
      offset : {
          x: 20,
          y: 20
      }

    }
  },
  computed: {
    currentPage() {
      if(this.isMounted) {
        return this.$refs.table.currentx
        //return this.$refs.tablepatrones.currentx
      }
      return 0
    },
  },
  methods: {
     editar(item){      
      this.initValues();
      this.modoEditar = true; 
      this.item.id = item.id;
      this.item.descripcion = item.descripcion;
      this.item.color = item.color;
      this.item.codigo = item.codigo;
      this.item.lat = item.lat;
      this.item.lng = item.lng;
      this.poligono();
      this.selected = [];
      this.popupActive=true;
    },
    initValues() {

      this.item = {
        lat: '', 
        lng: '',
        color: '#ffff00'
      };
      
      if (this.element) {
          this.element.setMap(null);
          this.errors.clear();
      }

      if (this.map) {
          this.map.setZoom(this.zoom);
          this.map.setCenter(this.center);
      }

      this.clearOverlaysPat();
      this.refrescaOtrosDatos();
      this.errors.clear();

    },
    initMap:function() {
      const thisIns = this;
      var lataux = "";
      var lngaux = "";
      var center = this.center;
      var zoom = this.zoom;
      var map = new google.maps.Map(document.getElementById('map'), {zoom: zoom, center: center});
      thisIns.map = map;

      var drawingManager = new google.maps.drawing.DrawingManager({
            drawingControl: true,
            drawingControlOptions: {
                position: google.maps.ControlPosition.TOP_CENTER,
                drawingModes: [google.maps.drawing.OverlayType.POLYGON]
            },
            polygonOptions: {
                fillColor: '#ffff00',
                fillOpacity: 0.1,
                strokeWeight: 0.8,
                clickable: false,
                zIndex: 1,
                editable: true
            }

        });

      drawingManager.setMap(thisIns.map);


        google.maps.event.addListener(drawingManager, 'overlaycomplete', function (event) {
            if (thisIns.element) {
                thisIns.element.setMap(null);
                thisIns.item.lat = '';
                thisIns.item.lng = '';
            }
            drawingManager.setDrawingMode(null);
            thisIns.element = event.overlay;
            if (event.type == google.maps.drawing.OverlayType.POLYGON) {
                lataux = "";
                lngaux = "";
                for (var i = 0; i < thisIns.element.getPath().getLength(); i++) {
                    lataux += thisIns.element.getPath().getAt(i).lat().toFixed(5) + ",";
                    lngaux += thisIns.element.getPath().getAt(i).lng().toFixed(5) + ",";
                }
                thisIns.item.lat = lataux.substring(0, lataux.length - 1);
                thisIns.item.lng = lngaux.substring(0, lngaux.length - 1);
              

                google.maps.event.addListener(thisIns.element, 'click', function (e) {
                    var path = thisIns.element.getPath();
                    path.removeAt(e.vertex);
                    lataux = "";
                    lngaux = "";
                    for (var i = 0; i < thisIns.element.getPath().getLength(); i++) {
                        lataux += thisIns.element.getPath().getAt(i).lat().toFixed(5) + ",";
                        lngaux += thisIns.element.getPath().getAt(i).lng().toFixed(5) + ",";
                    }
                   thisIns.item.lat = lataux.substring(0, lataux.length - 1);
                   thisIns.item.lng = lngaux.substring(0, lngaux.length - 1);

                });
                google.maps.event.addListener(thisIns.element.getPath(), 'set_at', function () {
                    lataux = "";
                    lngaux = "";
                    for (var i = 0; i < thisIns.element.getPath().getLength(); i++) {
                        lataux += thisIns.element.getPath().getAt(i).lat().toFixed(5) + ",";
                        lngaux += thisIns.element.getPath().getAt(i).lng().toFixed(5) + ",";
                    }

                    thisIns.item.lat = lataux.substring(0, lataux.length - 1);
                    thisIns.item.lng = lngaux.substring(0, lngaux.length - 1);

                });

                google.maps.event.addListener(thisIns.element.getPath(), 'insert_at', function () {
                    lataux = "";
                    lngaux = "";
                    for (var i = 0; i < thisIns.element.getPath().getLength(); i++) {
                        lataux += thisIns.element.getPath().getAt(i).lat().toFixed(5) + ",";
                        lngaux += thisIns.element.getPath().getAt(i).lng().toFixed(5) + ",";
                    }
                     thisIns.item.lat = lataux.substring(0, lataux.length - 1);
                     thisIns.item.lng = lngaux.substring(0, lngaux.length - 1);
                });



            }

        });

         google.maps.event.addListener(drawingManager, "drawingmode_changed", function() {
            if ((drawingManager.getDrawingMode() == google.maps.drawing.OverlayType.POLYGON) && 
                (thisIns.element != null))
                thisIns.element.setMap(null);
                thisIns.item.lat = '';
                thisIns.item.lng = '';
        });



    },
    poligono() {
           const thisIns = this;
           var lataux = "";
           var lngaux = "";
           var bounds = new google.maps.LatLngBounds();
           var latitud =  thisIns.item.lat.split(",");
           var longitud = thisIns.item.lng.split(",");
           var color = thisIns.item.color;
					 var paths = [];
					
                     for (var i = 0; i < latitud.length; i++) {

                         var latcoord = parseFloat(latitud[i]);
                         var lngcoord = parseFloat(longitud[i]);
                         paths[i] = new google.maps.LatLng(latcoord.toFixed(5), lngcoord.toFixed(5));
                         bounds.extend(paths[i]);
			         }
                     thisIns.element = new google.maps.Polygon({
                         paths: paths,
                         fillColor: color,
                         fillOpacity: 0.1,
                         strokeWeight: 0.8,
                         clickable: false,
                         zIndex: 1,
                         editable: true
                     });
                     
                     
                google.maps.event.addListener(thisIns.element, 'click', function (e) {
                    var path = thisIns.element.getPath();
                    path.removeAt(e.vertex);
                    lataux = "";
                    lngaux = "";
                    for (var i = 0; i < thisIns.element.getPath().getLength(); i++) {
                        lataux += thisIns.element.getPath().getAt(i).lat().toFixed(5) + ",";
                        lngaux += thisIns.element.getPath().getAt(i).lng().toFixed(5) + ",";
                    }
                     thisIns.item.lat = lataux.substring(0, lataux.length - 1);
                     thisIns.item.lng = lngaux.substring(0, lngaux.length - 1);
               
                });
                google.maps.event.addListener(thisIns.element.getPath(), 'set_at', function () {
                    lataux = "";
                    lngaux = "";
                    for (var i = 0; i < thisIns.element.getPath().getLength(); i++) {
                        lataux += thisIns.element.getPath().getAt(i).lat().toFixed(5) + ",";
                        lngaux += thisIns.element.getPath().getAt(i).lng().toFixed(5) + ",";
                    }

                     thisIns.item.lat = lataux.substring(0, lataux.length - 1);
                     thisIns.item.lng = lngaux.substring(0, lngaux.length - 1);
                });

                google.maps.event.addListener(thisIns.element.getPath(), 'insert_at', function () {
                    lataux = "";
                    lngaux = "";
                    for (var i = 0; i < thisIns.element.getPath().getLength(); i++) {
                        lataux += thisIns.element.getPath().getAt(i).lat().toFixed(5) + ",";
                        lngaux += thisIns.element.getPath().getAt(i).lng().toFixed(5) + ",";
                    }
                     thisIns.item.lat = lataux.substring(0, lataux.length - 1);
                     thisIns.item.lng = lngaux.substring(0, lngaux.length - 1);
                });

                  
                    thisIns.element.setMap(thisIns.map);
                    thisIns.map.fitBounds(bounds);
                    thisIns.map.setZoom(12);
                     
					 
    },
    clearOverlaysPat() {
            const thisIns = this;
            
            if(thisIns.markersPat.length > 0){
              thisIns.markersPat.forEach((value, index) => {
                  value.setMap(null);
              });
              
            }
            thisIns.markersPat = [];

            
    },
    handleSelectedPatrones(tr) {
      
      this.clearOverlaysPat();
      this.cargaPatrones(this.selectedPatrones);
     
    },
    cargaPatrones(patrones){
           const thisIns = this;
           var latitud;
           var longitud;
           var color;
					 var paths = [];
           var marker;
           
      		 patrones.forEach((value, index) => {
					        var auxlat = value['lat'];
                  var auxlng = value['lng'];
                  var color = value['color'];
                  var id = value['id'];
                  var descripcion = value['descripcion'];
              
                  var latitud = auxlat.split(",");
                  var longitud = auxlng.split(",");
  				        var paths = [];
                  //var bounds = new google.maps.LatLngBounds();

                for (var x = 0; x < latitud.length; x++) {
					    
					        var latcoord = parseFloat(latitud[x]);
                  var lngcoord = parseFloat(longitud[x]);
                  paths[x] = new google.maps.LatLng(latcoord.toFixed(5), lngcoord.toFixed(5));
                  //bounds.extend(paths[x]);
                }
					      
					      marker = new google.maps.Polygon({
                    paths: paths,
                    fillColor: color,
                    fillOpacity: 0.1,
                    strokeWeight: 0.8,
                    //clickable:true,
                    id: id,
                    name: descripcion,
                    map: thisIns.map
                });

               /* marker.center = bounds.getCenter();

                var infowindow = new google.maps.InfoWindow();
             
                marker.addListener('click', (function(content) {
                    return function() {
                      // set the content
                      infowindow.setContent(content.name);
                      // set the position
                      infowindow.setPosition(this.center);
                      // open it
                      infowindow.open(thisIns.map);
                    }
                })(marker));*/




                   google.maps.event.addListener(marker, 'mouseover', function (e) {
                      thisIns.injectTooltip(e, this.name);
                  });


                  google.maps.event.addListener(marker, 'mousemove', function (e) {
                          thisIns.moveTooltip(e);
                      });

                      google.maps.event.addListener(marker, 'mouseout', function (e) {
                          thisIns.deleteTooltip(e);
                      });
              
                    thisIns.markersPat.push(marker);  
					     
					  });

    },

    listadoPatrones() {
       setTimeout(() => {
                this.popupPatrones = true;
      }, 300);
    },
     refrescaOtrosDatos() {
      //Carga Patrones
      const thisIns  = this;
      this.$http
        .get("patrones/patrones")
        .then(function(response) {
          thisIns.patrones_choices = response.data.items;
        })
        .catch(function(error) {
          thisIns.$msjError(error);    
        });   

    },
    deleteTooltip(event) {
      if (this.tipObj) {
          //delete the tooltip if it exists in the DOM
          document.body.removeChild(this.tipObj);
          this.tipObj = null;
      }
    },
    moveTooltip(event) {
      if (this.tipObj && event) {
          //position it
          this.tipObj.style.top = event.ya.clientY + window.scrollY + this.offset.y + "px";
          this.tipObj.style.left = event.ya.clientX + window.scrollX + this.offset.x + "px";
      }
    },
    injectTooltip(event, data) {
      if (!this.tipObj && event) {
          //create the tooltip object
          this.tipObj = document.createElement("div");
          this.tipObj.style.width = '100px';
          this.tipObj.style.height = '40px';
          this.tipObj.style.background = "white";
          this.tipObj.style.borderRadius = "5px";
          this.tipObj.style.padding = "10px";
          this.tipObj.style.fontFamily = "Arial,Helvetica";
          this.tipObj.style.textAlign = "center";
          this.tipObj.style.zIndex = "99999";
          this.tipObj.innerHTML = data;
          
          //position it
          this.tipObj.style.position = "fixed";
          this.tipObj.style.top = event.ya.clientY + window.scrollY + this.offset.y + "px";
          this.tipObj.style.left = event.ya.clientX + window.scrollX + this.offset.x + "px";

          //add it to the body
          document.body.appendChild(this.tipObj);
        }
    }



  },
  created() {
      this.$refrescaTabla();
      
  },
  mounted() {
    this.isMounted = true;
    this.initMap();
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
</style>
