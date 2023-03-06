<template>
  <div class="camera-ocr">
    <div class="camera-container">
      <video ref="video" autoplay></video>
      <canvas ref="canvas" style="display: none"></canvas>
    </div>
    <div class="text-container">
      <div v-if="isLoading">
        <p>Cargando...</p>
      </div>
      <div v-if="text">
        <h3>Texto extraído:</h3>
        <p>{{ text }}</p>
        <button @click="reset">Tomar otra foto</button>
      </div>
      <div v-if="screenshotURL">
        <h3>Captura:</h3>
        <img :src="screenshotURL" />
      </div>
    </div>
    <button class="take-photo-btn" @click="takePhoto" :disabled="isLoading">
      Tomar foto
    </button>
  </div>
</template>

<script>
import Tesseract from "tesseract.js";

export default {
  name: "CameraOCR",
  data() {
    return {
      isLoading: false,
      text: "",
      screenshotURL: "",
    };
  },
  mounted() {
    navigator.mediaDevices.getUserMedia({ video: true }).then((stream) => {
      this.$refs.video.srcObject = stream;
      this.$refs.video.play();
    });
  },
  methods: {
    takePhoto() {
      this.isLoading = true;
      this.$refs.video.pause();
      const canvas = this.$refs.canvas;
      const context = canvas.getContext("2d");
      canvas.width = this.$refs.video.videoWidth;
      canvas.height = this.$refs.video.videoHeight;
      context.drawImage(this.$refs.video, 0, 0, canvas.width, canvas.height);
      // this.applyFilterGray();
      // this.applyFilterNegativo();
      this.applyFilterContraste();

      const dataURL = canvas.toDataURL("image/png");
      this.screenshotURL = dataURL;
      Tesseract.recognize(dataURL).then((result) => {
        this.text = result.data.text;
        console.log("prediccion", result.data.text);
        this.isLoading = false;
      });
    },
    applyFilter() {
      const img = this.$refs.canvas;
      img.style.filter =
        "blur(0px) brightness(111%) contrast(200%) grayscale(100%) hue-rotate(0deg) invert(100%) opacity(100%) saturate(1) sepia(0%)";
    },
    applyFilterGray() {
      const canvas = this.$refs.canvas;
      const ctx = canvas.getContext("2d");
      const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
      const data = imageData.data;
      for (let i = 0; i < data.length; i += 4) {
        const r = data[i];
        const g = data[i + 1];
        const b = data[i + 2];
        const grayScale = 0.2989 * r + 0.587 * g + 0.114 * b;

        // Si el color es oscuro
        data[i] = grayScale; // rojo
        data[i + 1] = grayScale; // verde
        data[i + 2] = grayScale; // azul
      }
      ctx.putImageData(imageData, 0, 0);
    },
    applyFilterNegativo() {
      const canvas = this.$refs.canvas;
      const ctx = canvas.getContext("2d");
      const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
      const data = imageData.data;
      for (let i = 0; i < data.length; i += 4) {
        data[i] = 255 - data[i]; // Rojo
        data[i + 1] = 255 - data[i + 1]; // Verde
        data[i + 2] = 255 - data[i + 2]; // Azul
      }
      ctx.putImageData(imageData, 0, 0);
    },
    applyFilterContraste() {
      const canvas = this.$refs.canvas;
      const ctx = canvas.getContext("2d");
      const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
      const data = imageData.data;
      const umbral = 125;
      const nuevoUmbral=255;
      for (let i = 0; i < data.length; i += 4) {
        const r = data[i];
        const g = data[i + 1];
        const b = data[i + 2];
        const grayScale = (r + g + b) / 3;
        data[i] = grayScale > umbral ? nuevoUmbral : 0; // rojo
        data[i + 1] = grayScale > umbral ? nuevoUmbral : 0; // verde
        data[i + 2] = grayScale > umbral ? nuevoUmbral : 0; // azul
      }
      ctx.putImageData(imageData, 0, 0);
    },
    reset() {
      this.isLoading = false;
      this.text = "";
      this.screenshotURL = "";
      this.$refs.video.play();
    },
  },
};
</script>

<style>
.camera-ocr {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  height: 100vh;
}

.camera-container {
  position: relative;
  width: 60%;
  height: 60%;
  margin-bottom: 20px;
}

.camera-container video {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  width: 50%;
  height: 50%;
}

.take-photo-btn {
  padding: 10px 20px;
  font-size: 1.5rem;
}

.text-container {
  text-align: center;
  overflow-y: scroll;
}
</style>
