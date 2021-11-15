<template>
  <main class="main">
    <!-- Breadcrumb -->

    <div class="container-fluid">
      <!-- Ejemplo de tabla Listado -->
      <div class="card">
        <div class="card-header">
          <div class="row justify-content-between rounded">
            <h3>Usuarios</h3>
            <button
              type="button"
              @click="abrirModal('usuario', 'registrar')"
              class="btn btn-secondary btn-sm"
            >
              <i class="icon-plus"></i>&nbsp;Nuevo
            </button>
          </div>
        </div>
        <div class="card-body">
          <!-- <div class="form-group row">
            <div class="col-md-6">
              <div class="input-group">
                <select class="form-control col-md-3" v-model="criterio">
                  <option value="usuario">Usuario</option>
                  <option value="email">Email</option>
                </select>
                <input
                  type="text"
                  v-model="buscar"
                  @keyup.enter="listarUsuario(1, buscar, criterio)"
                  class="form-control"
                  placeholder="Texto a buscar"
                />
                <button
                  type="submit"
                  @click="listarUsuario(1, buscar, criterio)"
                  class="btn btn-primary"
                >
                  <i class="fa fa-search"></i> Buscar
                </button>
              </div>
            </div>
          </div> -->
          <table class="table table-bordered table-striped table-sm">
            <thead>
              <tr>
                <th>Nombre</th>
                <th>Email</th>
                <th>Estado</th>
                <th>Opciones</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="usuario in arrayUsuario" :key="usuario.id">
                <td v-text="usuario.usuario"></td>
                <td v-text="usuario.email"></td>

                <td>
                  <div v-if="usuario.condicion">
                    <span class="badge badge-success">Activo</span>
                  </div>
                  <div v-else>
                    <span class="badge badge-danger">Inactivo</span>
                  </div>
                </td>

                <td>
                  <button
                    type="button"
                    @click="abrirModal('usuario', 'actualizar', usuario)"
                    class="btn btn-dark btn-sm"
                  >
                    <i class="icon-pencil"></i>
                  </button>
                  &nbsp;
                  <template v-if="usuario.condicion">
                    <button
                      type="button"
                      class="btn btn-dark btn-sm"
                      @click="desactivarUsuario(usuario.id)"
                    >
                      <i class="icon-trash"></i>
                    </button>
                  </template>
                  <template v-else>
                    <button
                      type="button"
                      class="btn btn-dark btn-sm"
                      @click="activarUsuario(usuario.id)"
                    >
                      <i class="icon-check"></i>
                    </button>
                  </template>
                </td>
              </tr>
            </tbody>
          </table>
          <nav>
            <ul class="pagination">
              <li class="page-item" v-if="pagination.current_page > 1">
                <a
                  class="page-link"
                  href="#"
                  @click.prevent="
                    cambiarPagina(pagination.current_page - 1, buscar, criterio)
                  "
                  >Ant</a
                >
              </li>
              <li
                class="page-item"
                v-for="page in pagesNumber"
                :key="page"
                :class="[page == isActived ? 'active' : '']"
              >
                <a
                  class="page-link"
                  href="#"
                  @click.prevent="cambiarPagina(page, buscar, criterio)"
                  v-text="page"
                ></a>
              </li>
              <li
                class="page-item"
                v-if="pagination.current_page < pagination.last_page"
              >
                <a
                  class="page-link"
                  href="#"
                  @click.prevent="
                    cambiarPagina(pagination.current_page + 1, buscar, criterio)
                  "
                  >Sig</a
                >
              </li>
            </ul>
          </nav>
        </div>
      </div>
      <!-- Fin ejemplo de tabla Listado -->
    </div>
    <!--Inicio del modal agregar/actualizar-->
    <div
      class="modal fade"
      tabindex="-1"
      :class="{ mostrar: modal }"
      role="dialog"
      aria-labelledby="myModalLabel"
      style="display: none"
      aria-hidden="true"
    >
      <div class="modal-dialog  modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title" v-text="tituloModal"></h4>
            <button
              type="button"
              class="close"
              @click="cerrarModal()"
              aria-label="Close"
            >
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">
            <form
              id="modal-form"
              action=""
              method="post"
              enctype="multipart/form-data"
              class="form-horizontal"
            >
              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="text-input"
                  >Usuario (*)</label
                >
                <div class="col-md-9">
                  <input
                    type="text"
                    v-model="usuario"
                    class="form-control"
                    placeholder="Nombre del usuario"
                  />
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="email-input"
                  >Contraseña (*)</label
                >
                <div class="col-md-9">
                  <input
                    type="password"
                    v-model="password"
                    class="form-control"
                    placeholder="Password de acceso"
                    maxlength="60"
                  />
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="email-input"
                  >Email (*)</label
                >
                <div class="col-md-9">
                  <input
                    type="email"
                    v-model="email"
                    class="form-control"
                    placeholder="Email"
                    maxlength="50"
                  />
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="text-input"
                  >Empleado (*)</label
                >
                <div class="col-md-9">
                  <select class="form-control" v-model="empleado_id">
                    <option value="0" disabled>Seleccione el Empleado</option>
                    <option
                      v-for="empleado in arrayEmpleado"
                      :key="empleado.id"
                      :value="empleado.id"
                      v-text="empleado.apellido + ' ' + empleado.nombre"
                    ></option>
                  </select>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="text-input"
                  >Rol (*)</label
                >
                <div class="col-md-9">
                  <select class="form-control" v-model="rol_id">
                    <option value="0" disabled>Seleccione el Rol</option>
                    <option
                      v-for="rol in arrayRol"
                      :key="rol.id"
                      :value="rol.id"
                      v-text="rol.nombre"
                    ></option>
                  </select>
                </div>
              </div>

              <div v-show="errorUsuario" class="form-group row div-error">
                <div class="text-center text-error">
                  <div
                    v-for="error in errorMostrarMsjUsuario"
                    :key="error"
                    v-text="error"
                  ></div>
                </div>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button
              type="button"
              class="btn btn-secondary"
              @click="cerrarModal()"
            >
              Cerrar
            </button>
            <button
              type="button"
              v-if="tipoAccion == 1"
              class="btn btn-primary"
              @click="registrarUsuario()"
            >
              Guardar
            </button>
            <button
              type="button"
              v-if="tipoAccion == 2"
              class="btn btn-primary"
              @click="actualizarUsuario()"
            >
              Actualizar
            </button>
          </div>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
    <!--Fin del modal-->
  </main>
</template>

<script>
import toastr from "toastr";
export default {
  data() {
    return {
      usuario_id: 0,
      usuario: "",
      empleado_id: 0,
      rol_id: 0,
      email: "",
      password: "",
      arrayUsuario: [],
      arrayEmpleado: [],
      arrayRol: [],
      modal: 0,
      tituloModal: "",
      tipoAccion: 0,
      errorUsuario: 0,
      errorMostrarMsjUsuario: [],
      pagination: {
        total: 0,
        current_page: 0,
        per_page: 0,
        last_page: 0,
        from: 0,
        to: 0,
      },
      offset: 3,
      criterio: "usuario",
      buscar: "",
    };
  },
  computed: {
    isActived: function () {
      return this.pagination.current_page;
    },
    //Calcula los elementos de la paginación
    pagesNumber: function () {
      if (!this.pagination.to) {
        return [];
      }

      var from = this.pagination.current_page - this.offset;
      if (from < 1) {
        from = 1;
      }

      var to = from + this.offset * 2;
      if (to >= this.pagination.last_page) {
        to = this.pagination.last_page;
      }

      var pagesArray = [];
      while (from <= to) {
        pagesArray.push(from);
        from++;
      }
      return pagesArray;
    },
  },
  methods: {
    listarUsuario(page, buscar, criterio) {
      let me = this;
      var url =
        "/usuario?page=" + page + "&buscar=" + buscar + "&criterio=" + criterio;
      axios
        .get(url)
        .then(function (response) {
          var respuesta = response.data;
          me.arrayUsuario = respuesta.users.data;
          me.pagination = respuesta.pagination;
        })
        .catch(function (error) {
          console.log(error);
        });
    },
    listarEmpleado(page, buscar, criterio) {
      let me = this;
      var url =
        "/empleado?page=" +
        page +
        "&buscar=" +
        buscar +
        "&criterio=" +
        criterio;
      axios
        .get(url)
        .then(function (response) {
          var respuesta = response.data;
          me.arrayEmpleado = respuesta.empleados.data;
          me.pagination = respuesta.pagination;
        })
        .catch(function (error) {
          console.log(error);
        });
    },
    listarRol(page, buscar, criterio) {
      let me = this;
      var url =
        "/rol?page=" + page + "&buscar=" + buscar + "&criterio=" + criterio;
      axios
        .get(url)
        .then(function (response) {
          var respuesta = response.data;
          me.arrayRol = respuesta.roles.data;
          me.pagination = respuesta.pagination;
        })
        .catch(function (error) {
          console.log(error);
        });
    },
    cambiarPagina(page, buscar, criterio) {
      let me = this;
      //Actualiza la página actual
      me.pagination.current_page = page;
      //Envia la petición para visualizar la data de esa página
      me.listarUsuario(page, buscar, criterio);
    },
    registrarUsuario() {
      let me = this;
      if (this.validarUsuario()) {
        return;
      }
      if (this.validarEmail()) {
      } else {
        me.errorMostrarMsjUsuario.push("El email ingresado no es valido");
        me.errorUsuario = 1;
        return;
      }
      axios
        .post("/usuario/registrar", {
          usuario: this.usuario,
          email: this.email,
          empleado_id: this.empleado_id,
          rol_id: this.rol_id,
          password: this.password,
        })
        .then(function (response) {
          me.cerrarModal();
          me.listarUsuario(1, "", "usuario");
          toastr.success("Se ha registrado el usuario", "Registrado", {
            timeOut: 5000,
          });
        })
        .catch(function (error) {
          console.log(error);
          toastr.error("Error", { timeOut: 5000 });
        });
    },
    actualizarUsuario() {
      console.log("prueba");
      if (this.validarUsuario()) {
        return;
      }
      if (this.validarEmail()) {
      } else {
        me.errorMostrarMsjUsuario.push("El email ingresado no es valido");
        me.errorUsuario = 1;
        return;
      }
      let me = this;
      axios
        .put("/usuario/actualizar", {
          usuario: this.usuario,
          email: this.email,
          password: this.password,
          empleado_id: this.empleado_id,
          rol_id: this.rol_id,
          id: this.usuario_id,
        })
        .then(function (response) {
          me.cerrarModal();
          me.listarUsuario(1, "", "usuario");
          toastr.success("Se ha actualizado con exito", "Actualizado", {
            timeOut: 5000,
          });
        })
        .catch(function (error) {
          console.log(error);
          toastr.error("Ha ocurrido un error", "Error", { timeOut: 5000 });
        });
    },
    desactivarUsuario(id) {
      swal({
        title: "Esta seguro de desactivar este usuario?",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Aceptar!",
        cancelButtonText: "Cancelar",
        confirmButtonClass: "btn btn-success",
        cancelButtonClass: "btn btn-danger",
        buttonsStyling: false,
        reverseButtons: true,
      }).then((result) => {
        if (result.value) {
          let me = this;

          axios
            .put("/usuario/desactivar", {
              id: id,
            })
            .then(function (response) {
              me.listarUsuario(1, "", "usuario");
              swal(
                "Desactivado!",
                "La usuario se ha desactivado con éxito.",
                "success"
              );
            })
            .catch(function (error) {
              console.log(error);
            });
        } else if (
          // Read more about handling dismissals
          result.dismiss === swal.DismissReason.cancel
        ) {
        }
      });
    },
    activarUsuario(id) {
      swal({
        title: "Esta seguro de activar este usuario?",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Aceptar!",
        cancelButtonText: "Cancelar",
        confirmButtonClass: "btn btn-success",
        cancelButtonClass: "btn btn-danger",
        buttonsStyling: false,
        reverseButtons: true,
      }).then((result) => {
        if (result.value) {
          let me = this;

          axios
            .put("/usuario/activar", {
              id: id,
            })
            .then(function (response) {
              me.listarUsuario(1, "", "usuario");
              swal(
                "Activado!",
                "El usuario se ha activado con éxito.",
                "success"
              );
            })
            .catch(function (error) {
              console.log(error);
            });
        } else if (
          // Read more about handling dismissals
          result.dismiss === swal.DismissReason.cancel
        ) {
        }
      });
    },

    validarUsuario() {
      this.errorUsuario = 0;
      this.errorMostrarMsjUsuario = [];

      if (!this.usuario)
        this.errorMostrarMsjUsuario.push(
          "Debe ingresar el usuario de la usuario"
        );
      if (!this.email)
        this.errorMostrarMsjUsuario.push(
          "Debe ingresar la cantidad de días mínimos"
        );
      if (!this.password)
        this.errorMostrarMsjUsuario.push(
          "Debe ingresar la cantidad de días máximos"
        );
      if (!this.empleado_id)
        this.errorMostrarMsjUsuario.push("Debe ingresar el empleado");
      if (!this.rol_id)
        this.errorMostrarMsjUsuario.push("Debe ingresar el rol");
      if (this.errorMostrarMsjUsuario.length) this.errorUsuario = 1;
      return this.errorUsuario;
    },
    cerrarModal() {
      this.modal = 0;
      this.tituloModal = "";
      this.usuario = "";
      this.email = "";
      this.empleado_id = 0;
      this.rol_id = 0;
      this.password = "";
    },
    validarEmail() {
      var regex =
        /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
      return regex.test(this.email) ? true : false;
    },
    abrirModal(modelo, accion, data = []) {
      switch (modelo) {
        case "usuario": {
          switch (accion) {
            case "registrar": {
              this.modal = 1;
              this.tituloModal = "Registrar usuario";
              this.email = data["email"];
              this.usuario = data["usuario"];
              this.empleado_id = data["empleado_id"];
              this.rol_id = data["rol_id"];
              this.password = data["password"];
              this.tipoAccion = 1;
              break;
            }
            case "actualizar": {
              this.modal = 1;
              this.usuario_id = data["id"];
              this.tituloModal = "Actualizar usuario";
              this.tipoAccion = 2;
              this.email = data["email"];
              this.usuario = data["usuario"];
              this.empleado_id = data["empleado_id"];
              this.rol_id = data["rol_id"];
              this.password = data["password"];
              break;
            }
          }
        }
      }
    },
  },
  mounted() {
    this.listarUsuario(1, this.buscar, this.criterio);
    this.listarEmpleado(1, this.buscar, this.criterio);
    this.listarRol(1, this.buscar, this.criterio);
  },
};
</script>
<style>
.modal-content {
  width: 100% !important;
  position: absolute !important;
}
.mostrar {
  display: list-item !important;
  opacity: 1 !important;
  position: absolute !important;
  background-color: #3c29297a !important;
}
.div-error {
  display: flex;
  justify-content: center;
}
.text-error {
  color: red !important;
  font-weight: bold;
}
</style>
