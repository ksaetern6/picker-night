<script setup lang="ts">
import { onMounted, ref } from 'vue';

const segments = ref(['Apple', 'Banana', 'Cherry', 'Date', 'Elderberry', 'Fig', 'Grape', 'Honeydew, Apple', 'Banana', 'Cherry', 'Date', 'Elderberry', 'Fig', 'Grape', 'Honeydew', 'Apple', 'Banana', 'Cherry', 'Date', 'Elderberry', 'Fig', 'Grape', 'Honeydew' ,'Apple', 'Banana', 'Cherry', 'Date', 'Elderberry', 'Fig', 'Grape', 'Honeydew']);
const selectedSegment = ref<string | null>(null);
const wheelCanvas = ref<HTMLCanvasElement | null>(null);

const drawWheel = () => {
  if (!wheelCanvas.value) return;
  
  const ctx = wheelCanvas.value.getContext('2d');
  const { width, height } = wheelCanvas.value;
  const centerX = width / 2;
  const centerY = height / 2;
  const radius = Math.min(width, height) / 2 - 10; // Adjust for padding

  const angleStep = (2 * Math.PI) / segments.value.length;

  // Clear the canvas
  ctx.clearRect(0, 0, width, height);
//   ctx.imageSmoothingEnabled = true;
  
  // Draw segments
  segments.value.forEach((segment, index) => {
    const startAngle = angleStep * index;
    const endAngle = startAngle + angleStep;

    ctx.beginPath();
    ctx.moveTo(centerX, centerY);
    ctx.arc(centerX, centerY, radius, startAngle, endAngle);
    ctx.fillStyle = index % 2 === 0 ? '#f0f0f0' : '#cccccc'; // Alternating colors
    ctx.fill();
    ctx.stroke();

    // Draw text
    ctx.save();
    ctx.translate(centerX, centerY);
    ctx.rotate(startAngle + angleStep / 2);
    ctx.fillStyle = 'black';
    ctx.fillText(segment, radius / 2, 10);
    ctx.restore();
  });
};

// Spin the wheel
const spinWheel = () => {
  const spins = Math.floor(Math.random() * 10 + 10); // Random spins
  const totalDegrees = spins * 360 + Math.random() * 360; // Add a random degree
  const duration = 3000; // Spin duration
  let start: number | null = null;

  const animate = (timestamp: number) => {
    if (!start) start = timestamp;
    const elapsed = timestamp - start;

    const progress = Math.min(elapsed / duration, 1);
    const easing = 1 - Math.pow(1 - progress, 3); // Ease out cubic

    const currentAngle = totalDegrees * easing;

    // Rotate canvas
    if (wheelCanvas.value) {
      const ctx = wheelCanvas.value.getContext('2d');
      ctx.clearRect(0, 0, wheelCanvas.value.width, wheelCanvas.value.height);
      ctx.save();
      ctx.translate(wheelCanvas.value.width / 2, wheelCanvas.value.height / 2);
      ctx.rotate((currentAngle * Math.PI) / 180);
      ctx.translate(-wheelCanvas.value.width / 2, -wheelCanvas.value.height / 2);

      // Redraw the wheel
      drawWheel();

      ctx.restore();
    }

    if (progress < 1) {
      requestAnimationFrame(animate);
    } else {
      // Determine the selected segment after spinning
      const angle = (totalDegrees % 360) / (360 / segments.value.length);
      selectedSegment.value = segments.value[Math.floor(segments.value.length - angle) % segments.value.length];
    }
  };

  requestAnimationFrame(animate);
};

// Watch for changes in segments
// watch(segments, drawWheel);

onMounted(() => {
    drawWheel()
})
</script>

<template>
    <canvas ref="wheelCanvas" width="400" height="400"></canvas>
    <button @click="spinWheel">Spin the Wheel!</button>
    <p v-if="selectedSegment">You landed on: {{ selectedSegment }}</p>
</template>

<style scoped>
canvas {
  border: 1px solid #000;
}
</style>