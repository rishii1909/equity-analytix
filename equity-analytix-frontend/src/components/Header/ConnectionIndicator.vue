<template>
  <div class="container">
    <svg
      v-if="isOnline && isWebSocketRunning"
      id="connection-indicator"
      width="10"
      height="10"
      viewBox="0 0 149 149"
      fill="none"
      xmlns="http://www.w3.org/2000/svg"
    >
      <g filter="url(#filter0_d)">
        <circle cx="74.5" cy="73.5" r="72.5" fill="#33AD31" />
        <circle cx="74.5" cy="73.5" r="72.5" fill="url(#paint0_radial)" />
        <circle cx="74.5" cy="73.5" r="72" stroke="#303032" />
      </g>
      <defs>
        <filter
          id="filter0_d"
          x="0"
          y="0"
          width="149"
          height="149"
          filterUnits="userSpaceOnUse"
          color-interpolation-filters="sRGB"
        >
          <feFlood flood-opacity="0" result="BackgroundImageFix" />
          <feColorMatrix
            in="SourceAlpha"
            type="matrix"
            values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0"
          />
          <feOffset dy="1" />
          <feGaussianBlur stdDeviation="1" />
          <feColorMatrix type="matrix" values="0 0 0 0 1 0 0 0 0 1 0 0 0 0 1 0 0 0 0.12 0" />
          <feBlend mode="normal" in2="BackgroundImageFix" result="effect1_dropShadow" />
          <feBlend mode="normal" in="SourceGraphic" in2="effect1_dropShadow" result="shape" />
        </filter>
        <radialGradient
          id="paint0_radial"
          cx="0"
          cy="0"
          r="1"
          gradientUnits="userSpaceOnUse"
          gradientTransform="translate(74.5 1) rotate(90) scale(89)"
        >
          <stop stop-color="white" />
          <stop offset="1" stop-color="white" stop-opacity="0" />
        </radialGradient>
      </defs>
    </svg>

    <svg
      v-else
      id="connection-indicator"
      width="10"
      height="10"
      viewBox="0 0 149 149"
      fill="none"
      xmlns="http://www.w3.org/2000/svg"
    >
      <g filter="url(#filter0_d)">
        <circle cx="74.5" cy="73.5" r="72.5" fill="#333332" />
        <circle cx="74.5" cy="73.5" r="72.5" fill="url(#paint0_radial)" />
      </g>
      <defs>
        <filter
          id="filter0_d"
          x="0"
          y="0"
          width="149"
          height="149"
          filterUnits="userSpaceOnUse"
          color-interpolation-filters="sRGB"
        >
          <feFlood flood-opacity="0" result="BackgroundImageFix" />
          <feColorMatrix
            in="SourceAlpha"
            type="matrix"
            values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0"
          />
          <feOffset dy="1" />
          <feGaussianBlur stdDeviation="1" />
          <feColorMatrix type="matrix" values="0 0 0 0 1 0 0 0 0 1 0 0 0 0 1 0 0 0 0.12 0" />
          <feBlend mode="normal" in2="BackgroundImageFix" result="effect1_dropShadow" />
          <feBlend mode="normal" in="SourceGraphic" in2="effect1_dropShadow" result="shape" />
        </filter>
        <radialGradient
          id="paint0_radial"
          cx="0"
          cy="0"
          r="1"
          gradientUnits="userSpaceOnUse"
          gradientTransform="translate(74.5 1) rotate(90) scale(86.5)"
        >
          <stop stop-color="white" />
          <stop offset="1" stop-color="white" stop-opacity="0" />
        </radialGradient>
      </defs>
    </svg>
  </div>
</template>

<script>
export default {
  name: "ConnectionIndicator",
  computed: {
    isOnline: function () {
      return this.$store.getters["chat/isOnline"];
    },
    isWebSocketRunning: function () {
      return this.$store.getters["chat/isWebSocketRunning"];
    },
  },
};
</script>

<style scoped>
#connection-indicator {
  width: 10px;
}
.container {
  padding: 0;
}
</style>
