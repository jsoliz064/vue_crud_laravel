<template>
  <div class="modal fade" :class="{ mostrar: modal }">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">
            {{ tituloModal }}
          </h5>
          <button
            type="button"
            class="close"
            data-dismiss="modal"
            @click="closeModal()"
          >
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <slot></slot>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      modal: 0,
      tituloModal: "",
    };
  },
  methods: {
    
    openModal(tituloModal) {
      this.modal = 1;
      this.tituloModal = tituloModal;
    },

    closeModal() {
      this.modal = 0;
    },
  },
};
</script>

<style>
.mostrar {
  display: list-item;
  opacity: 1;
  background: rgba(39, 39, 39, 0.705);
  padding: 15%;
}
</style>