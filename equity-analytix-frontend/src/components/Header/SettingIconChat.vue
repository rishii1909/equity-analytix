<template>
  <div class="tool">
    <ConnectionIndicator />
    <span
      class="tool-item tool--hide-input"
      v-show="!isAdmin && isInputHidden"
      @click="$emit('toggleInput')"
    >
      <svg
        width="26"
        height="16"
        viewBox="0 0 26 16"
        fill="none"
        xmlns="http://www.w3.org/2000/svg"
      >
        <path
          d="M22.3056 8.53672L13.0659 0.288172L9.07825 4.155L22.3056 15.8763L22.3056 8.53672Z"
          fill="#9BA5AB"
        />
        <path
          d="M3.64055 8.53676L13.0659 0.233215L16.7471 4.41171L3.64052 15.8764L3.64055 8.53676Z"
          fill="#9BA5AB"
        />
      </svg>
    </span>
    <span
      class="tool-item tool--hide-input"
      v-show="!isAdmin && !isInputHidden"
      @click="$emit('toggleInput')"
    >
      <svg
        width="26"
        height="16"
        transform="rotate(180)"
        viewBox="0 0 26 16"
        fill="none"
        xmlns="http://www.w3.org/2000/svg"
      >
        <path
          d="M22.3056 8.53672L13.0659 0.288172L9.07825 4.155L22.3056 15.8763L22.3056 8.53672Z"
          fill="#9BA5AB"
        />
        <path
          d="M3.64055 8.53676L13.0659 0.233215L16.7471 4.41171L3.64052 15.8764L3.64055 8.53676Z"
          fill="#9BA5AB"
        />
      </svg>
    </span>
    <span class="tool-item tool--settings" @click="$emit('toggleSettings')">
      <svg
        xmlns="http://www.w3.org/2000/svg"
        width="17"
        height="17"
        viewBox="0 0 24 24"
        fill="#98a6ad"
      >
        <path
          :class="{
            'setting-active-stroke setting-active-fill': isSettingsOpen,
          }"
          d="M22.2,14.4L21,13.7c-1.3-0.8-1.3-2.7,0-3.5l1.2-0.7c1-0.6,1.3-1.8,0.7-2.7l-1-1.7c-0.6-1-1.8-1.3-2.7-0.7   L18,5.1c-1.3,0.8-3-0.2-3-1.7V2c0-1.1-0.9-2-2-2h-2C9.9,0,9,0.9,9,2v1.3c0,1.5-1.7,2.5-3,1.7L4.8,4.4c-1-0.6-2.2-0.2-2.7,0.7   l-1,1.7C0.6,7.8,0.9,9,1.8,9.6L3,10.3C4.3,11,4.3,13,3,13.7l-1.2,0.7c-1,0.6-1.3,1.8-0.7,2.7l1,1.7c0.6,1,1.8,1.3,2.7,0.7L6,18.9   c1.3-0.8,3,0.2,3,1.7V22c0,1.1,0.9,2,2,2h2c1.1,0,2-0.9,2-2v-1.3c0-1.5,1.7-2.5,3-1.7l1.2,0.7c1,0.6,2.2,0.2,2.7-0.7l1-1.7   C23.4,16.2,23.1,15,22.2,14.4z M12,16c-2.2,0-4-1.8-4-4c0-2.2,1.8-4,4-4s4,1.8,4,4C16,14.2,14.2,16,12,16z"
        />
      </svg>
    </span>
    <span class="tool-item tool--mute-notification" @click="$emit('toggleNotifications')">
      <svg
        width="18"
        height="18"
        viewBox="0 0 18 18"
        fill="none"
        xmlns="http://www.w3.org/2000/svg"
      >
        <g
          clip-path="url(#clip0)"
          :class="{
            'setting-active-stroke': !isNotificationsOn,
          }"
        >
          <path
            :class="{
              'setting-active-stroke': !isNotificationsOn,
            }"
            d="M9.97716 15.26C9.8494 15.4802 9.66603 15.6631 9.4454 15.7901C9.22477 15.9172 8.97463 15.9841 8.72002 15.9841C8.46541 15.9841 8.21527 15.9172 7.99465 15.7901C7.77402 15.6631 7.59065 15.4802 7.46289 15.26"
            stroke="#98A6AD"
            stroke-width="2"
            stroke-linecap="round"
            stroke-linejoin="round"
          />
          <path
            :class="{
              'setting-active-stroke': !isNotificationsOn,
            }"
            d="M13.5378 9.44669C13.2146 8.26324 13.0604 7.04 13.08 5.81335"
            stroke="#98A6AD"
            stroke-width="2"
            stroke-linecap="round"
            stroke-linejoin="round"
          />
          <path
            :class="{
              'setting-active-stroke': !isNotificationsOn,
            }"
            d="M4.54899 4.54895C4.42274 4.95851 4.35904 5.38478 4.36005 5.81335C4.36005 10.9 2.18005 12.3533 2.18005 12.3533H12.3534"
            stroke="#98A6AD"
            stroke-width="2"
            stroke-linecap="round"
            stroke-linejoin="round"
          />
          <path
            :class="{
              'setting-active-stroke': !isNotificationsOn,
            }"
            d="M13.08 5.81337C13.0811 5.02353 12.8677 4.2482 12.4625 3.57021C12.0574 2.89222 11.4756 2.33704 10.7794 1.96395C10.0833 1.59087 9.29881 1.41391 8.50989 1.45196C7.72097 1.49002 6.9572 1.74167 6.30017 2.18004"
            stroke="#98A6AD"
            stroke-width="2"
            stroke-linecap="round"
            stroke-linejoin="round"
          />
          <path
            :class="{
              'setting-active-stroke': !isNotificationsOn,
            }"
            d="M0.726685 0.726685L16.7133 16.7133"
            stroke="#98A6AD"
            stroke-width="2"
            stroke-linecap="round"
            stroke-linejoin="round"
          />
        </g>
        <defs>
          <clipPath id="clip0">
            <rect width="17.44" height="17.44" fill="white" />
          </clipPath>
        </defs>
      </svg>
    </span>
    <span class="tool-item tool--screen-off" v-if="!isAdmin" @click="$emit('deleteAllMessages')">
      <svg
        width="18"
        height="18"
        viewBox="0 0 18 18"
        fill="none"
        xmlns="http://www.w3.org/2000/svg"
      >
        <g clip-path="url(#clip0)">
          <path
            d="M6.33337 15.26H12.1467"
            stroke="#98A6AD"
            stroke-width="2"
            stroke-linecap="round"
            stroke-linejoin="round"
          />
          <path
            d="M9.23999 12.3533V15.2599"
            stroke="#98A6AD"
            stroke-width="2"
            stroke-linecap="round"
            stroke-linejoin="round"
          />
          <path
            fill-rule="evenodd"
            clip-rule="evenodd"
            d="M2.97339 3.63339C2.97339 3.38302 3.17635 3.18005 3.42672 3.18005H15.0534C15.3038 3.18005 15.5067 3.38302 15.5067 3.63339V8.05624C16.5305 8.68704 17.2738 9.72924 17.5062 10.9524C17.5065 10.935 17.5067 10.9175 17.5067 10.9001V3.63339C17.5067 2.27845 16.4083 1.18005 15.0534 1.18005H3.42672C2.07178 1.18005 0.973389 2.27845 0.973389 3.63339V10.9001C0.973389 12.255 2.07178 13.3534 3.42672 13.3534H9.15893C8.96778 12.8628 8.86289 12.3291 8.86289 11.771C8.86289 11.6301 8.86956 11.4908 8.88262 11.3534H3.42672C3.17635 11.3534 2.97339 11.1504 2.97339 10.9001V3.63339Z"
            fill="#98A6AD"
          />
          <path
            fill-rule="evenodd"
            clip-rule="evenodd"
            d="M15.5938 8.94331C15.8421 9.19162 15.8421 9.59421 15.5938 9.84251L11.7788 13.6575C11.5305 13.9058 11.1279 13.9058 10.8796 13.6575C10.6313 13.4092 10.6313 13.0066 10.8796 12.7583L14.6946 8.94331C14.9429 8.695 15.3455 8.695 15.5938 8.94331Z"
            fill="#FF0101"
          />
          <path
            fill-rule="evenodd"
            clip-rule="evenodd"
            d="M10.8796 8.94331C11.1279 8.695 11.5305 8.695 11.7788 8.94331L15.5938 12.7583C15.8421 13.0066 15.8421 13.4092 15.5938 13.6575C15.3455 13.9058 14.9429 13.9058 14.6946 13.6575L10.8796 9.84251C10.6313 9.59421 10.6313 9.19162 10.8796 8.94331Z"
            fill="#FF0101"
          />
        </g>
        <defs>
          <clipPath id="clip0">
            <rect width="17.44" height="17.44" fill="white" transform="translate(0.52002)" />
          </clipPath>
        </defs>
      </svg>
    </span>
    <span class="tool-item tool--refresh" v-if="!isAdmin" @click="$emit('refreshAllMessages')">
      <svg
        width="18"
        height="18"
        viewBox="0 0 18 18"
        fill="none"
        xmlns="http://www.w3.org/2000/svg"
      >
        <g clip-path="url(#clip0)">
          <path
            d="M0.766602 2.90662V7.26661H5.1266"
            stroke="#98A6AD"
            stroke-width="2"
            stroke-linecap="round"
            stroke-linejoin="round"
          />
          <path
            d="M16.7532 14.5333V10.1733H12.3932"
            stroke="#98A6AD"
            stroke-width="2"
            stroke-linecap="round"
            stroke-linejoin="round"
          />
          <path
            d="M14.9293 6.54004C14.5608 5.49857 13.9344 4.56744 13.1087 3.83351C12.283 3.09959 11.2848 2.58681 10.2072 2.343C9.12973 2.0992 8.00801 2.13233 6.94676 2.4393C5.88551 2.74627 4.91932 3.31707 4.13833 4.09844L0.766602 7.26671M16.7533 10.1734L13.3815 13.3416C12.6005 14.123 11.6344 14.6938 10.5731 15.0008C9.51185 15.3078 8.39014 15.3409 7.31262 15.0971C6.2351 14.8533 5.23691 14.3405 4.41117 13.6066C3.58544 12.8726 2.95908 11.9415 2.59053 10.9"
            stroke="#98A6AD"
            stroke-width="2"
            stroke-linecap="round"
            stroke-linejoin="round"
          />
        </g>
        <defs>
          <clipPath id="clip0">
            <rect width="17.44" height="17.44" fill="white" transform="translate(0.039917)" />
          </clipPath>
        </defs>
      </svg>
    </span>
    <span class="tool-item tool--search" v-if="!isAdmin" @click="$emit('toggleKeySearch')">
      <svg
        width="18"
        height="18"
        viewBox="0 0 18 18"
        fill="none"
        xmlns="http://www.w3.org/2000/svg"
      >
        <path
          :class="{
            'setting-active-stroke': isKeySearchOpen,
          }"
          d="M8.55344 13.8067C11.7641 13.8067 14.3668 11.204 14.3668 7.99339C14.3668 4.78277 11.7641 2.18005 8.55344 2.18005C5.34283 2.18005 2.74011 4.78277 2.74011 7.99339C2.74011 11.204 5.34283 13.8067 8.55344 13.8067Z"
          stroke="#98A6AD"
          stroke-width="2"
          stroke-linecap="round"
          stroke-linejoin="round"
        />
        <path
          :class="{
            'setting-active-stroke setting-active-fill': isKeySearchOpen,
          }"
          d="M15.8201 15.26L12.6591 12.099"
          stroke="#98A6AD"
          stroke-width="2"
          stroke-linecap="round"
          stroke-linejoin="round"
        />
      </svg>
    </span>
  </div>
</template>
<script>
// import ConnectionIndicator from "@/components/Header/ConnectionIndicator";
import ConnectionIndicator from "/components/Header/ConnectionIndicator";

export default {
  name: "SettingIconChat",
  components: { ConnectionIndicator },
  data: () => ({
    isAdmin: false,
  }),
  computed: {
    isSettingsOpen: function () {
      return this.$store.getters["settingHeader/isSettingsOpen"];
    },
    isKeySearchOpen: function () {
      return this.$store.getters["settingHeader/isKeySearchOpen"];
    },
    isInputHidden: function () {
      return this.$store.getters["settingHeader/isInputHidden"];
    },
    isNotificationsOn: function () {
      return this.$store.getters["settingHeader/isNotificationsOn"];
    },
  },
  created() {
    this.isAdmin = this.$store.getters["chat/isAdmin"];
  },
};
</script>

<style lang="scss">
ul > li {
  list-style: none;
}

.tool--hide-input {
  margin-right: 5px;
}

.setting-active-stroke {
  stroke: #fff200;
}
.setting-active-fill {
  fill: #fff200;
}
</style>
