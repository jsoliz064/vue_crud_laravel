<template>
  <div>
    <video ref="video" width="320" height="240"></video>
    <button @click="takePhoto">Tomar foto</button>
    <canvas ref="canvas" style="display: none"></canvas>
    <img ref="imagen" :src="photo" width="320" height="240" />
  </div>
</template>

<script>
import html2canvas from "html2canvas";

export default {
  data() {
    return {
      photo: null,
      streamVideo: null,
    };
  },
  methods: {
    takePhoto() {
      const video = this.$refs.video;
      const canvas = this.$refs.canvas;
      const ctx = canvas.getContext("2d");

      ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
      const dataURL = canvas.toDataURL("image/png");
      this.photo = dataURL;
      this.applyFilterNitido();
      //this.savePhoto(dataURL);
      //   this.captureVideo();
    },

    // Tomar una captura de pantalla del video y guardarla en un elemento de imagen
    captureVideo() {
      const video = this.$refs.video;
      html2canvas(video).then((canvas) => {
        const imgData = canvas.toDataURL("image/png");
        this.savePhoto(imgData);
      });
    },

    savePhoto(dataURL) {
      axios
        .post("/api/save-photo", { photo: dataURL })
        .then((response) => {
          console.log(response.data.message);
        })
        .catch((error) => {
          console.log(error);
        });
    },
    applyFilter(ctx) {
      const canvas = this.$refs.canvas;
      const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
      const data = imageData.data;

      for (let i = 0; i < data.length; i += 4) {
        const r = data[i];
        const g = data[i + 1];
        const b = data[i + 2];
        const grayScale = 0.2989 * r + 0.587 * g + 0.114 * b;

        if (grayScale < 200) {
          // Si el color es oscuro
          data[i] = grayScale; // rojo
          data[i + 1] = grayScale; // verde
          data[i + 2] = grayScale; // azul
        } else {
          // Si el color es claro
          data[i + 3] = 0; // Establecer la transparencia a 0
        }
      }

      ctx.putImageData(imageData, 0, 0);
    },
    applyFilterNitido() {
      const img = this.$refs.imagen;
      if (img) {
        img.addEventListener("load", () => {
          img.style.filter =
            "blur(0px) brightness(111%) contrast(200%) grayscale(100%) hue-rotate(0deg) invert(100%) opacity(100%) saturate(1) sepia(0%)";
        });
      }
    },
  },
  mounted() {
    navigator.mediaDevices.getUserMedia({ video: true }).then((stream) => {
      this.streamVideo = stream;
      const video = this.$refs.video;
      video.srcObject = stream;
      video.play();
    });
  },
  //   pausar captura de video al cambiar de componente
  beforeRouteLeave(to, from, next) {
    if (this.streamVideo) {
      this.streamVideo.getTracks().forEach((track) => track.stop());
    }
    next();
  },
};
</script>
