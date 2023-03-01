<template>
  <div>
    <div class="my-4">
      <label for="nombre">Nombre</label>
      <input
        v-model="producto.nombre"
        type="text"
        class="form-control"
        id="nombre"
        placeholder="Nombre del producto"
      />
    </div>
    <div class="my-4">
      <label for="precio">Precio</label>
      <input
        v-model="producto.precio"
        type="number"
        class="form-control"
        id="precio"
        placeholder="Precio del producto"
      />
    </div>
    <div class="my-4">
      <label for="costo">Costo</label>
      <input
        v-model="producto.costo"
        type="number"
        class="form-control"
        id="costo"
        placeholder="costo del producto"
      />
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-secondary" @click="cancelar()">
        Cancelar
      </button>
      <button type="button" class="btn btn-primary" @click="guardar()">
        Guardar
      </button>
    </div>
  </div>
</template>
<script>
export default {
  name: "Create",
  data() {
    return {
      producto: {
        nombre: "",
        precio: 1,
        costo: 1,
      },
    };
  },
  methods: {

    guardar() {
      this.$emit("guardar", this.producto);
    },
    cancelar() {
      this.$emit("cancelar");
    },

    load(data) {
      this.producto.nombre = data.nombre;
      this.producto.precio = data.precio;
      this.producto.costo = data.costo;
    },
  },
};
</script>