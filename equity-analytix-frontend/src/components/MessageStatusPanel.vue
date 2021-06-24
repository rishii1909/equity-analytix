<template>
  <div class="eq-chat--status-bar">
    <form class="status-form">
      <div class="center-container">
        <div class="center-top-container">
          <label class="bar-item bull">
            <input type="radio" value="bull" v-model="pickedStatus" @click="clickToStatusMessage" />
            <img :src="`${publicPath}images/pos-v3.svg`" alt="Bull" />
          </label>
          <label class="bar-item green-arrow-up">
            <input
              type="radio"
              value="arrowPos"
              v-model="pickedStatus"
              @click="clickToStatusMessage"
            />
            <img :src="`${publicPath}images/green-arrow-up.svg`" alt="Green Arrow Up" />
          </label>
          <label class="bar-item green-target">
            <input
              type="radio"
              value="sightPos"
              v-model="pickedStatus"
              @click="clickToStatusMessage"
            />
            <img :src="`${publicPath}images/green-target.svg`" alt="Green Target" />
          </label>
          <label class="bar-item green-circle">
            <input
              type="radio"
              value="eyePos"
              v-model="pickedStatus"
              @click="clickToStatusMessage"
            />
            <img :src="`${publicPath}images/green-circle.svg`" alt="Green Circle" />
          </label>
          <label class="bar-item green-alien">
            <input
              type="radio"
              value="xfilesPos"
              v-model="pickedStatus"
              @click="clickToStatusMessage"
            />
            <img :src="`${publicPath}images/green-alien-v3.svg`" alt="Green Alien" />
          </label>
        </div>
        <div class="center-bottom-container">
          <label class="bar-item bear">
            <input type="radio" value="bear" v-model="pickedStatus" @click="clickToStatusMessage" />
            <img :src="`${publicPath}images/neg-v3.svg`" alt="Bear" />
          </label>

          <label class="bar-item red-arrow-down">
            <input
              type="radio"
              value="arrowNeg"
              v-model="pickedStatus"
              @click="clickToStatusMessage"
            />
            <img :src="`${publicPath}images/red-arrow-down.svg`" alt="Red Arrow Down" />
          </label>

          <label class="bar-item red-target">
            <input
              type="radio"
              value="sightNeg"
              v-model="pickedStatus"
              @click="clickToStatusMessage"
            />
            <img :src="`${publicPath}images/red-target.svg`" alt="Red Target" />
          </label>
          <label class="bar-item red-circle">
            <input
              type="radio"
              value="eyeNeg"
              v-model="pickedStatus"
              @click="clickToStatusMessage"
            />
            <img :src="`${publicPath}images/red-circle.svg`" alt="Red Circle" />
          </label>
          <label class="bar-item red-alien">
            <input
              type="radio"
              value="xfilesNeg"
              v-model="pickedStatus"
              @click="clickToStatusMessage"
            />
            <img :src="`${publicPath}images/red-alien-v3.svg`" alt="Red Alien" />
          </label>
        </div>
      </div>

      <div class="right-container">
        <div class="inner-right-container">
          <label class="bar-item change-position">
            <input
              type="radio"
              value="switch"
              v-model="pickedStatus"
              @click="clickToStatusMessage"
            />
            <img :src="`${publicPath}images/change-position.svg`" alt="Switch" />
          </label>
          <label class="bar-item bear">
            <input
              type="radio"
              value="alert"
              v-model="pickedStatus"
              @click="clickToStatusMessage"
            />
            <img :src="`${publicPath}images/alert.svg`" alt="Alert" />
          </label>
        </div>
      </div>
    </form>
  </div>
</template>

<script>
import { urlBaseToPicture } from '../../appConfig'

export default {
  name: 'MessageStatusPanel',
  props: {
    messageCount: {
      type: Number,
      default: 0,
      required: false,
    },
  },
  data: () => ({
    publicPath: urlBaseToPicture,
    pickedStatus: '',
  }),
  watch: {
    pickedStatus: function () {
      this.$emit('input', this.pickedStatus)
    },
    messageCount: function () {
      this.pickedStatus = 'none'
    },
  },
  methods: {
    clickToStatusMessage(event) {
      this.$emit('clickToStatus', this.pickedStatus)
      if (event.shiftKey) {
        this.pickedStatus = 'none'
      }
    },
  },
}
</script>

<style lang="scss" scoped>
.eq-chat--inner .eq-chat--status-bar {
  padding: 0 0 12px 12px;
  background: var(--chat-message-list);
}

.eq-chat--inner .eq-chat--status-bar .bar-item {
  height: 34px;
  width: 34px;
  cursor: pointer;
  margin-right: 4px;
}

.eq-chat--inner .eq-chat--status-bar .bar-item:hover {
  height: 34px;
  width: 34px;
  box-shadow: #fff200 0px 0px 1px, #fff200 0px 0px 0px 1px;
}

.status-form {
  display: flex;
  height: 100%;

  label {
    margin: 0;
  }
}

.left-container {
  display: flex;
  flex-direction: column;
  justify-content: center;
  margin-right: 20px;
}

.center-container {
  height: 100%;
  display: flex;
  flex-direction: column;
  justify-content: center;
}

.right-container {
  display: flex;
  flex-direction: column;
  justify-content: center;
  margin-left: 20px;
}

.center-top-container {
  margin-bottom: 4px;
}

.inner-right-container {
  display: flex;
}

[type='radio'] {
  position: absolute;
  opacity: 0;
  width: 0;
  height: 0;
}

[type='radio'] + img {
  cursor: pointer;
  max-height: 33px;
}

[type='radio']:checked + img {
  border: 1px solid yellow;
}
[type='radio'] {
  position: absolute;
  opacity: 0;
  width: 0;
  height: 0;
}

[type='radio'] + img {
  cursor: pointer;
}
</style>
