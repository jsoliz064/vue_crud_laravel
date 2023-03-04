<template>
  <div class="camera-ocr">
    <div class="camera-container">
      <video ref="video" autoplay></video>
      <canvas ref="canvas" style="display: none;"></canvas>
    </div>
    <div class="text-container">
      <div v-if="isLoading">
        <p>Cargando...</p>
      </div>
      <div v-if="text">
        <h3>Extracted Text:</h3>
        <p>{{ text }}</p>
        <button @click="reset">Intentar de nuevo</button>
      </div>
      <div v-if="screenshotURL">
        <h3>Screenshot:</h3>
        <img :src="screenshotURL" />
      </div>
    </div>
    <button class="take-photo-btn" @click="takePhoto" :disabled="isLoading">Take Photo</button>
  </div>
</template>

<script>
import Tesseract from 'tesseract.js';

export default {
  name: 'CameraOCR',
  data() {
    return {
      isLoading: false,
      text: '',
      screenshotURL: ''
    }
  },
  mounted() {
    navigator.mediaDevices.getUserMedia({ video: true })
      .then(stream => {
        this.$refs.video.srcObject = stream;
        this.$refs.video.play();
      })
  },
  methods: {
    takePhoto() {
      this.isLoading = true;
      this.$refs.video.pause();
      const canvas = this.$refs.canvas;
      const context = canvas.getContext('2d');
      canvas.width = this.$refs.video.videoWidth;
      canvas.height = this.$refs.video.videoHeight;
      context.drawImage(this.$refs.video, 0, 0, canvas.width, canvas.height);
      const dataURL = canvas.toDataURL('image/png');
      this.screenshotURL = dataURL;
      Tesseract.recognize(dataURL)
        .then(result => {
          this.text = result.data.text;
          console.log("prediccion:",result.data.text);
          this.isLoading = false;
        })
    },
    reset() {
      this.isLoading = false;
      this.text = '';
      this.screenshotURL = '';
      this.$refs.video.play();
    }
  }
}
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
  width: 100%;
  height: 100%;
}

.take-photo-btn {
  padding: 10px 20px;
  font-size: 1.5rem;
}

.text-container {
  text-align: center;
}
</style>
