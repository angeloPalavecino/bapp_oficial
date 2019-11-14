<script>
  export default {
    methods: {
      //Carga Tabla 
      $refrescaTabla($url = null) {
        const thisIns = this;
        $url = $url == null ? thisIns.ruta : $url;

        const url = $url; //thisIns.ruta        
        this.$http.get(url)
          .then(function (response) {
              thisIns.items = response.data.items 
              thisIns.itemsOriginal = response.data.items 
          })
          
          .catch(function (error) {
            thisIns.$msjError(error);    
          });
          

      },
      //Actualiza Registros
    $submitActualizar($name = null) {
      
      $name = $name == null ? true : $name;
      const thisIns = this;
      const url = thisIns.ruta + this.item.id;

      thisIns.$validator.validateAll($name).then(result =>{
        if (result) {
      
          this.$http.put(url, this.item) 
            .then((res) =>{             
              
              this.$vs.loading({ type: 'radius' , scale: 0.6 });
              this.$refrescaTabla();
              this.initValues();   
              this.modoEditar  = false;            
              this.popupActive = false;
              setTimeout(() => {
                  this.$vs.loading.close()
              }, 500);
        
              thisIns.$vs.notify({
                title:'Exito',
                text: 'El registro fue actualizado con exito!.',
                color:'success',
                iconPack: 'feather',
                icon:'icon-alert-circle'}) 
                   
            })
            .catch(function (error) {
               thisIns.$msjError(error);            

            });
        } else {
        }
      })
    },
    //Agrega Registros
    $submitAgregar($name = null) {
     
      $name = $name == null ? true : $name;
      const thisIns = this;
      const url = thisIns.ruta + 'store';
      
      thisIns.$validator.validateAll($name).then(result =>{
        if (result) {
         this.$http.post(url, this.item) //this.item
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
              thisIns.$msjError(error);    

            });
        } else {
        }
      })
    },
    //Elimina registo
    $submitEliminar(x, index) {
        this.ite = x;
        this.ind = index;
        this.selected = [];
        this.$vs.dialog({
                type: 'confirm',
                color: 'danger',
                title: `Eliminar registro`,
                text: `Esta seguro que desea eliminar el registro seleccionado?`,
                accept: this.$aceptaBorrado,
                cancel: this.$cancelarBorrado,
              
            })
      },
    //Acepta Borrado
    $aceptaBorrado() {
       const thisIns = this;
       const url = thisIns.ruta + this.ite.id;

        this.$http.delete(url)
          .then(()=>{
            this.$vs.loading({ type: 'radius' , scale: 0.6 });
              this.$refrescaTabla();
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

              thisIns.$msjError(error);    

              delete thisIns.ite;
              delete thisIns.ind;
          });
     },
      //Cancela Borrado
    $cancelarBorrado() {
          delete this.ite;
          delete this.ind;
          this.selected = [];
    },
    //Acepta el borrado masivo
    $aceptaBorradoMasivo() {
        const thisIns = this;
        const registrosSeleccionados = this.selected;
        const url = thisIns.ruta + 'borrar';

        this.$http.post(url, registrosSeleccionados)
          .then(()=>{
            thisIns.$vs.loading({ type: 'radius' , scale: 0.6 });
              this.$refrescaTabla();
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
              thisIns.$msjError(error);        
     
          });

   
     },
    //Cancela el borrado masivo
     $cancelarBorradoMasivo() {
          this.selected = [];
    },
    //Exporta CSV
    $csvExport(arrData) {
      let csvContent = "data:text/csv;charset=utf-8,";
      csvContent += [
        Object.keys(arrData[0]).join(";"),
        ...arrData.map(item => Object.values(item).join(";"))
      ]
        .join("\n")
        .replace(/(^\[)|(\]$)/gm, "");

      const data = encodeURI(csvContent);
      const link = document.createElement("a");
      link.setAttribute("href", data);
      link.setAttribute("download", "export.csv");
      link.click();
    },
    //Cierra PopUP
    $cancelarPopUp(){
      
      this.initValues();
      this.selected = [];
      this.popupActive=false;
      
    },
    //Abre PopUP
    $agregarPopUp(){
  
      this.initValues();
      this.modoEditar = false; 
      this.selected = [];
      this.popupActive=true; 
      
    },
    //Acciones masivas
    $accion(seleccion) {
      switch (seleccion) {
        case 1:
            this.aux = this.selected.length;
            if(this.aux > 0){


                this.$vs.dialog({
                  
                  type: 'confirm',
                  color: 'danger',
                  title: `Borrado masivo`,
                  text: `Esta seguro que desea eliminar todos lo registros seleccionados?`,
                  accept: this.$aceptaBorradoMasivo,
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
            const datos = this.items;
            this.$csvExport(datos);
            break;          
        default:
           return;
    }
    },
    //Cierra Pop-Up Evento
    $close(event,con){
      this.selected = [];
    },
    //Filtra Tabla
    $filtrar(campo,valor){
      
      var dataBase = this.itemsOriginal;
      var filterx = null;
      
      if(valor === null) {
        
        filterx = dataBase;

      }else {

        filterx = dataBase.filter(function (tr) {
        if(tr[campo] === valor){
          return tr;
        }
      });
      }
      
      this.items = filterx;
      
    },

     //Carga datos perfil
    $cargarDatosPerfil(){
      
      const thisIns = this;
      const url = thisIns.ruta + 'perfil';
      this.$http.get(url)
        .then(function (response) {
          thisIns.item = response.data.item; 
          
         if(response.data.item.imagen === null){
          thisIns.item.imagen = "avatar.png";
           }else{
          thisIns.item.imagen = response.data.item.imagen;
        }     

         })
        .catch(function (error) {
          thisIns.$msjError(error);          

        });
    },

    //Agrega Registros
    $upload(formData) {
      
      const thisIns = this;
      const url = thisIns.ruta + 'upload';
      
      if (this.itemDoc.file.size > 0) {
        this.$http.post(url, formData, {headers: {
          "Content-Type": "multipart/form-data",
          "Content-Type": "application/json"
        }})
        .then((res) =>{
          this.$vs.loading({ type: 'radius' , scale: 0.6 });
          //this.$refrescaTabla();
          this.initValues();
          this.modoEditar = false; 
          this.popupDocumento = false;
          setTimeout(() => { this.$vs.loading.close() }, 500);

          thisIns.$vs.notify({
            title:'Exito',
            text: 'El registro se ha creado con exito!.',
            color:'success',
            iconPack: 'feather',
            icon:'icon-alert-circle'
          })                                      
        })
        .catch(function (error) {
           thisIns.$msjError(error);           
        });
      } else {
      }
    },
    //Envia mensaje error
    $msjError(error){
        const thisIns = this;
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
    }

    
      
    },
  };
</script>