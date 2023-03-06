<template>
  <div>
    <canvas ref="canvas"></canvas>
    <button @click="recognizeText">Extract Text</button>
    <div v-if="text">
      <h3>Extracted Text:</h3>
      <p>{{ text }}</p>
    </div>
  </div>
</template>

<script>
import Tesseract from "tesseract.js";

export default {
  name: "ImageOCR",
  data() {
    return {
      text: "",
    };
  },
  methods: {
    recognizeText() {
      const canvas = this.$refs.canvas;
      this.applyFilterNegativo();
      this.applyFilterGray();
      this.applyFilterContraste();

      const dataURL = canvas.toDataURL("image/png");
      Tesseract.recognize(dataURL).then((result) => {
        this.text = result.data.text;
        console.log(result.data.text);
      });
    },
    applyFilterContraste() {
      const canvas = this.$refs.canvas;
      const ctx = canvas.getContext("2d");
      const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
      const data = imageData.data;
      const umbral = 120;
      const nuevoUmbral = 255;
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
    applyFilterNitidez() {
      const canvas = this.$refs.canvas;
      const ctx = canvas.getContext("2d");
      const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
      const data = imageData.data;
      // Aplicar el filtro de nitidez
      const matrix = [
        [0, -1, 0],
        [-1, 5, -1],
        [0, -1, 0],
      ];
      const factor = 0.8;
      const bias = 0;
      for (let i = 0; i < data.length; i += 4) {
        const r = data[i];
        const g = data[i + 1];
        const b = data[i + 2];
        const a = data[i + 3];
        const x = (i / 4) % canvas.width;
        const y = Math.floor(i / 4 / canvas.width);

        let newR = 0;
        let newG = 0;
        let newB = 0;

        for (let j = -1; j <= 1; j++) {
          for (let k = -1; k <= 1; k++) {
            const neighborX = x + k;
            const neighborY = y + j;
            if (
              neighborX >= 0 &&
              neighborY >= 0 &&
              neighborX < canvas.width &&
              neighborY < canvas.height
            ) {
              const neighborIndex = (neighborY * canvas.width + neighborX) * 4;
              newR += data[neighborIndex] * matrix[j + 1][k + 1];
              newG += data[neighborIndex + 1] * matrix[j + 1][k + 1];
              newB += data[neighborIndex + 2] * matrix[j + 1][k + 1];
            }
          }
        }

        data[i] = newR * factor + bias;
        data[i + 1] = newG * factor + bias;
        data[i + 2] = newB * factor + bias;
      }
      ctx.putImageData(imageData, 0, 0);
    },
  },
  mounted() {
    const canvas = this.$refs.canvas;
    const ctx = canvas.getContext("2d");

    const img = new Image();
    img.src = "/img/carnet.jpg";
    // img.crossOrigin = "Anonymous"; // si la imagen está alojada en otro dominio
    img.onload = () => {
      canvas.width = img.width;
      canvas.height = img.height;
      ctx.drawImage(img, 0, 0);
    };
  },
};
</script>
