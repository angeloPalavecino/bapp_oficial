/*=========================================================================================
  File Name: sidebarItems.js
  Description: Sidebar Items list. Add / Remove menu items from here.
  Strucutre:
          url     => router path
          name    => name to display in sidebar
          slug    => router path name
          icon    => Feather Icon component/icon name
          tag     => text to display on badge
          tagColor  => class to apply on badge element
          i18n    => Internationalization
          submenu   => submenu of current item (current item will become dropdown )
                NOTE: Submenu don't have any icon(you can add icon if u want to display)
          isDisabled  => disable sidebar item/group
  ----------------------------------------------------------------------------------------
  Item Name: Vuesax Admin - VueJS Dashboard Admin Template
  Author: Pixinvent
  Author URL: http://www.themeforest.net/user/pixinvent
==========================================================================================*/


export default [
  {
    header: "Administracion",
    i18n: "Others",
  },
  {
    url: "/pages/administracion/users/users",
    name: "Usuarios",
    slug: "users",
    icon: "UsersIcon",
    i18n: "UsersIcon",
    permiso: "users.index"

  },
  {
    url: "/pages/administracion/roles/roles",
    name: "Roles",
    slug: "roles",
    icon: "UserCheckIcon",
    i18n: "UserCheckIcon",
    permiso: "roles.index"
  
  },
  {
    url: null,
    name: "Config. Planificacion",
    icon: "SettingsIcon",
    i18n: "SettingsIcon",
    submenu: [
      {
        url: "/pages/administracion/excepciones/excepciones",
        name: "Excepciones",
        slug: "excepciones",
        icon: "AlertCircleIcon",
        i18n: "AlertCircleIcon",
        permiso: "excepciones.index"
      },
      {
        url: "/pages/administracion/observaciones/observaciones",
        name: "Observaciones",
        slug: "observaciones",
        icon: "ListIcon",
        i18n: "ListIcon",
        permiso: "observaciones.index"
      },
      {
        url: "/pages/administracion/obsinternas/obsinternas",
        name: "Obs. Internas",
        slug: "obsinternas",
        icon: "ListIcon",
        i18n: "ListIcon",
        permiso: "obsinternas.index"
      },
      {
        url: "/pages/administracion/fuerazona/fuerazona",
        name: "Fuera de zona",
        slug: "fuerazona",
        icon: "MapIcon",
        i18n: "MapIcon",
        permiso: "fuerazona.index"
      },
    ]
  },
  {
    url: null,
    name: "Config. Baptner", 
    icon: "TruckIcon",
    i18n: "TruckIcon",
    submenu: [
      {
        url: "/pages/administracion/moviles/asociados",
        name: "Asociados",
        slug: "moviles",
        icon: "TruckIcon",
        i18n: "TruckIcon",
        permiso: "moviles.index"
    
      },
      {
        url: "/pages/administracion/moviles/moviles",
        name: "Moviles",
        slug: "moviles",
        icon: "TruckIcon",
        i18n: "TruckIcon",
        permiso: "moviles.index"
    
      },
      {
        url: "/pages/administracion/moviles/conductores",
        name: "Conductores",
        slug: "conductores",
        icon: "TruckIcon",
        i18n: "TruckIcon",
        permiso: "moviles.index"
    
      },
    ]
  },
  {
    url: "/pages/administracion/empresas/empresas",
    name: "Config. Cuenta",
    slug: "empresas",
    icon: "GlobeIcon",
    i18n: "GlobeIcon",
    permiso: "empresas.index"
  },
  {
    url: null,
    name: "Tarifario", 
    icon: "DollarSignIcon",
    i18n: "DollarSignIcon",
    submenu: [
      {
        url: "/pages/administracion/tarifas/pasajeros/pasajeros",
        name: "Tarifa Pasajero",
        slug: "tarifapasajero",
        icon: "DollarSignIcon",
        i18n: "DollarSignIcon",
        permiso: "servpasajeros.index"
      },
      {
        url: "/pages/administracion/tarifas/planas/planas",
        name: "Tarifa Plana",
        slug: "tarifaplana",
        icon: "DollarSignIcon",
        i18n: "DollarSignIcon",
        permiso: "servplanas.index"
      },
      {
        url: "/pages/administracion/tarifas/kms/kms",
        name: "Tarifa Kms",
        slug: "tarifakms",
        icon: "DollarSignIcon",
        i18n: "DollarSignIcon",
        permiso: "servkms.index"
      },
    ]
  },
  {
    header: "Planificacion",
    i18n: "Others",
  },
  {
    url: null,
    name: "Planificacion", 
    icon: "ClipboardIcon",
    i18n: "ClipboardIcon",
    submenu: [
      {
        url: "/pages/planificacion/patrones/patrones",
        name: "Patrones",
        slug: "patrones",
        icon: "MapSignIcon",
        i18n: "MapSignIcon",
        permiso: "patrones.index"
      },
      {
        url: "/pages/planificacion/patrones/grupos",
        name: "Grupo de Patrones",
        slug: "grupopatrones",
        icon: "LayersSignIcon",
        i18n: "LayersSignIcon",
        permiso: "grupopatrones.index"
      },
      
    ]
  },

  
  
]
