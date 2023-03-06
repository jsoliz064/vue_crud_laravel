<template>
  <div class="card m-4">
    <!-- modal -->
    <modal-component ref="modalComponent">
      <create-component
        ref="createComponent"
        @guardar="setValues"
        @cancelar="closeModal"
      ></create-component>
    </modal-component>

    <div class="card-title">
      <h2 class="d-flex justify-content-center align-items-center">
        <b>Productos</b>
      </h2>
      <div class="d-flex justify-content-end align-items-end px-4">
        <button
          type="button"
          class="btn btn-primary"
          @click="
            modificar = false;
            openModal();
          "
        >
          Registrar Producto
        </button>
      </div>
    </div>

    <div class="card-body">
      <div class="table-responsive">
        <v-client-table :columns="columns" :data="productos">
          <a class="fa fa-edit" >editar</a>
        </v-client-table>

        <!-- <div class="table-responsive">
          <table class="table table-striped" id="productos">
            <thead>
              <tr>
                <th scope="col">ID</th>
                <th scope="col">Nombre</th>
                <th scope="col">Precio</th>
                <th scope="col">Costo</th>
                <th scope="col" width="15%">Acciones</th>
              </tr>
            </thead>

            <tbody>
              <tr v-for="prod in productos" :key="prod.id">
                <td>{{ prod.id }}</td>
                <td>{{ prod.nombre }}</td>
                <td>{{ prod.precio }}</td>
                <td>{{ prod.costo }}</td>
                <td>
                  <button
                    @click="
                      modificar = true;
                      openModal(prod);
                    "
                    class="btn btn-warning"
                  >
                    Editar
                  </button>
                  <button
                    @click="eliminar(prod.id)"
                    class="btn btn-danger btn-sm"
                  >
                    Eliminar
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div> -->
      </div>
    </div>
  </div>
</template>

<script>
import ModalComponent from "../ModalComponent.vue";
import CreateComponent from "./create.vue";

export default {
  name: "index",
  components: {
    ModalComponent,
    CreateComponent,
  },
  data() {
    return {
      columns: ["nombre", "precio", "costo", "acciones"],
      producto: {
        nombre: "",
        precio: 1,
        costo: 1,
      },
      id: 0,
      modificar: true,
      productos: [],
    };
  },
  methods: {
    async listar() {
      const res = await axios.get("/api/productos");
      this.productos = res.data;
    },
    async eliminar(id) {
      const res = await axios.delete("/api/productos/" + id);
      this.listar();
    },

    openModal(data = {}) {
      let titulo;
      if (this.modificar) {
        this.id = data.id;
        titulo = "Editar Producto";
        this.producto.nombre = data.nombre;
        this.producto.precio = data.precio;
        this.producto.costo = data.costo;
      } else {
        this.id = 0;
        titulo = "Registrar Producto";
        this.producto.nombre = "";
        this.producto.precio = 1;
        this.producto.costo = 1;
      }
      this.$refs.createComponent.load(this.producto);
      this.$refs.modalComponent.openModal(titulo);
    },

    closeModal() {
      this.$refs.modalComponent.closeModal();
    },

    async setValues(obj) {
      if (this.modificar) {
        const res = await axios.put("/api/productos/" + this.id, obj);
      } else {
        const res = await axios.post("/api/productos/", obj);
      }
      this.closeModal();
      this.listar();
    },
  },
  computed: {
    // Agregar la columna de acciones
    actionsColumn() {
      return {
        label: "Acciones",
        field: "acciones",
        sortable: false,
        formatter: (value, row) => {
          return `
            <button @click="
                    modificar = true;
                    openModal(${row});
                  ">Editar</button>
            <button @click="eliminar(${row.id})">Eliminar</button>
          `;
        },
      };
    },
    // Combinar las columnas definidas con la columna de acciones
    tableColumns() {
      return this.columns.concat(this.actionsColumn);
    },
  },
  created() {
    this.listar();
  },
};
</script>

<style>
.mostrar {
  display: list-item;
  opacity: 1;
  background: rgba(39, 39, 39, 0.705);
}
</style>