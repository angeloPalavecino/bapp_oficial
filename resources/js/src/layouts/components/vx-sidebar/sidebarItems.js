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

  },
  {
    url: "/pages/administracion/roles/roles",
    name: "Roles",
    slug: "roles",
    icon: "UserCheckIcon",
    i18n: "UserCheckIcon",
  
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
      },
      {
        url: "/pages/administracion/observaciones/observaciones",
        name: "Observaciones",
        slug: "observaciones",
        icon: "ListIcon",
        i18n: "ListIcon",
      },
      {
        url: "/pages/administracion/obsinternas/obsinternas",
        name: "Obs. Internas",
        slug: "obsinternas",
        icon: "ListIcon",
        i18n: "ListIcon",
      },
      {
        url: "/pages/administracion/fuerazona/fuerazona",
        name: "Fuera de zona",
        slug: "fuerazona",
        icon: "MapIcon",
        i18n: "MapIcon",
      },
    ]
  },
  {
    url: "/pages/administracion/moviles/moviles",
    name: "Config. Baptner",
    slug: "moviles",
    icon: "TruckIcon",
    i18n: "TruckIcon",

  },

  {
    url: "/pages/administracion/empresas/empresas",
    name: "Config. Cuenta",
    slug: "empresas",
    icon: "GlobeIcon",
    i18n: "GlobeIcon",
  
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
      },
      {
        url: "/pages/administracion/tarifas/planas/planas",
        name: "Tarifa Plana",
        slug: "tarifaplana",
        icon: "DollarSignIcon",
        i18n: "DollarSignIcon",
      },
      {
        url: "/pages/administracion/tarifas/kms/kms",
        name: "Tarifa Kms",
        slug: "tarifakms",
        icon: "DollarSignIcon",
        i18n: "DollarSignIcon",
      },
    ]
  },

  
  
]
