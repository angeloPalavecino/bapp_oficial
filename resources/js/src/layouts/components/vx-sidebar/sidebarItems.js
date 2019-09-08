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
    url: "/",
    name: "Administracion",
    slug: "administracion",
    icon: "SettingsIcon",
    submenu: [{
      url: "/pages/administracion/users/users",
      name: "Usuarios",
      slug: "users",
      icon: "",
    },{
      url: "/pages/administracion/roles/roles",
      name: "Roles",
      slug: "roles",
      icon: "",
      submenu: ""
    },
    {
      url: "/pages/administracion/empresas/empresas",
      name: "Empresas",
      slug: "empresas",
      icon: "",
      submenu: ""
    },{
      url: "/pages/administracion/observaciones/observaciones",
      name: "Observaciones",
      slug: "observaciones",
      icon: "",
      submenu: ""
    },{
      url: "/pages/administracion/obsinternas/obsinternas",
      name: "Obs. internas",
      slug: "obsinternas",
      icon: "",
      submenu: ""
    },{
      url: "/pages/administracion/excepciones/excepciones",
      name: "Excepciones",
      slug: "excepciones",
      icon: "",
      submenu: ""
    },{
      url: "/pages/administracion/fuerazona/fuerazona",
      name: "Fuera de zona",
      slug: "fuerazona",
      icon: "",
      submenu: ""
    },]
  },
  
  
]
