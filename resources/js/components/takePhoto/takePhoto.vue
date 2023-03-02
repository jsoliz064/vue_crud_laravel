<template>
  <div>
    <video ref="video" width="320" height="240"></video>
    <button @click="takePhoto">Tomar foto</button>
    <canvas ref="canvas" style="display: none"></canvas>
    <img v-if="photo" :src="photo" width="320" height="240" />
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
      this.savePhoto(dataURL);
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
