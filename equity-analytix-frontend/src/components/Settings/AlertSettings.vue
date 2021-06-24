<template>
  <div class="alert-settings-container">
    <div class="settings-flex-container">
      <div class="settings-column-headers">
        <h2>Alert type</h2>
        <h2>Choose presets</h2>
      </div>
      <div v-if="!isAdmin" class="settings-item number-of-flashes-container">
        <label>Number of Flashes </label>
        <input
          type="number"
          id="number-of-flashes"
          min="0"
          max="20"
          v-model.number="numberOfFlashes"
          @change="changeSetting"
        />
      </div>
      <div v-if="!isAdmin" class="settings-item flashing-speed-container">
        <div class="label"><label>Flashing Speed</label></div>
        <VueSlider
          v-model="flashingSpeed"
          :width="'200px'"
          :min="1"
          :max="5"
          :interval="1"
          :duration="0.25"
        />
      </div>
      <div v-if="!isAdmin" class="settings-item incoming-messages-container">
        <label>Incoming Messages</label>
        <select id="incoming-messages" v-model="incomingMessages" @change="changeSetting">
          <option value="up">Top</option>
          <option value="bottom">Bottom</option>
        </select>
      </div>
      <div class="settings-item web-link-color-container">
        <label>Web Link Color</label>
        <ejs-colorpicker
          :showButtons="false"
          v-model="webLinkColor"
          @change="changeWebLinkColor"
        ></ejs-colorpicker>
      </div>
      <div class="settings-item stock-symbol-color-container">
        <label>Stock Symbol Color</label>
        <ejs-colorpicker
          :showButtons="false"
          v-model="stockSymbolColor"
          @change="changeStockSymbolColor"
        ></ejs-colorpicker>
      </div>
      <div class="settings-item delivered-time-color-container">
        <label>Delivered Time Color</label>
        <ejs-colorpicker
          :showButtons="false"
          v-model="deliveredTimeColor"
          @change="changeDeliveredTimeColor"
        ></ejs-colorpicker>
      </div>
      <div class="settings-item text-container">
        <label>Text</label>
        <div class="settings-items-group">
          <input
            type="number"
            id="text-font-size"
            min="12"
            max="24"
            v-model.number="textSize"
            @change="changeSetting"
          />
          <input
            type="button"
            value="B"
            id="text-bold"
            placeholder="B"
            @click="changeTextBold"
            :class="{ active: textBold }"
          />
        </div>
      </div>
      <div class="settings-item">
        <label>{{ hideIconsLabel }}</label>
        <ToggleButton
          :color="{ checked: 'rgb(127,127,127)', unchecked: '#fff' }"
          switch-color="#202020"
          name="hide-icons"
          :sync="true"
          v-model="hideIcons"
          @change="changeSetting"
        />
      </div>
      <div class="settings-item dark-theme-container">
        <label>{{ themeLabel }}</label>
        <ToggleButton
          :color="{ checked: 'rgb(127,127,127)', unchecked: '#fff' }"
          switch-color="#202020"
          name="dark-theme"
          :sync="true"
          v-model="darkTheme"
          @change="changeSetting"
        />
      </div>
    </div>
  </div>
</template>

<script>
import { ToggleButton } from 'vue-js-toggle-button'
import { ColorPickerPlugin } from '@syncfusion/ej2-vue-inputs'
import VueSlider from 'vue-slider-component'
import 'vue-slider-component/theme/antd.css'

export default {
  name: 'AlertSettings',
  components: { ToggleButton, ColorPickerPlugin, VueSlider },
  data: () => ({
    isAdmin: false,
    numberOfFlashes: '',
    flashingSpeed: 3,
    webLinkColor: '',
    deliveredTimeColor: '',
    stockSymbolColor: '',
    textSize: '',
    textBold: '',
    incomingMessages: '',
    hideIcons: '',
    darkTheme: '',
    isLightThemeSelectedFirstTime: '',
    deliveredTimeColorDefaultDark: '',
    deliveredTimeColorDefaultLight: '',
    stockSymbolColorDefaultLight: '',
    webLinkColorDefaultLight: '',
  }),
  computed: {
    themeLabel: function () {
      return this.darkTheme ? 'Dark Theme' : 'Light Theme'
    },
    hideIconsLabel: function () {
      return this.hideIcons ? 'Hide Icons' : 'Display Icons'
    },
  },
  watch: {
    flashingSpeed: function () {
      this.changeFlashingSpeed()
    },
  },
  mounted() {
    this.isAdmin = this.$store.getters['chat/isAdmin']
    this.numberOfFlashes = this.$store.getters['settings/numberOfFlashes']
    this.flashingSpeed = this.$store.getters['settings/flashingSpeed']
    this.webLinkColor = this.$store.getters['settings/webLinkColor']
    this.stockSymbolColor = this.$store.getters['settings/stockSymbolColor']
    this.deliveredTimeColor = this.$store.getters['settings/deliveredTimeColor']
    this.textSize = this.$store.getters['settings/textSize']
    this.textBold = this.$store.getters['settings/textBold'] === 'true'
    this.incomingMessages = this.$store.getters['settings/incomingMessages']
    this.hideIcons = this.$store.getters['settings/hideIcons'] === 'true'
    this.darkTheme = this.$store.getters['settings/theme'] === 'dark'
    this.isLightThemeSelectedFirstTime =
      this.$store.getters['settings/isLightThemeSelectedFirstTime'] === 'true'

    this.deliveredTimeColorDefaultDark = '#fff'
    this.deliveredTimeColorDefaultLight = '#000'

    this.stockSymbolColorDefaultLight = '#3A68E8'
    this.webLinkColorDefaultLight = '#63A14A'
  },
  methods: {
    changeSetting(ev) {
      const targetName = ev.target ? ev.target.id : ev.srcEvent.target.name
      switch (targetName) {
        case 'number-of-flashes':
          this.$store.commit(`settings/setNumberOfFlashes`, String(this.numberOfFlashes))
          break
        case 'web-link-color':
          this.$store.commit(`settings/setWebLinkColor`, String(this.webLinkColor))
          break
        case 'text-font-size':
          this.$store.commit(`settings/setTextSize`, String(this.textSize))
          break
        case 'text-bold':
          this.$store.commit(`settings/setTextBold`, String(this.textBold))
          break
        case 'incoming-messages':
          this.$store.commit(`settings/setIncomingMessages`, String(this.incomingMessages))
          break
        case 'hide-icons':
          this.$store.commit(`settings/setHideIcons`, String(this.hideIcons))
          break
        case 'dark-theme':
          this.$store.commit(`settings/setTheme`, String(this.darkTheme ? 'dark' : 'light'))

          if (this.darkTheme) {
            this.$store.commit(
              `settings/setDeliveredTimeColor`,
              String(this.deliveredTimeColorDefaultDark)
            )
          } else {
            // default settings to enable light theme first
            if (!this.isLightThemeSelectedFirstTime) {
              this.$store.commit(`settings/setIsLightThemeSelectedFirstTime`, 'true')
              this.$store.commit(`settings/setWebLinkColor`, String(this.webLinkColorDefaultLight))
              this.$store.commit(
                `settings/setStockSymbolColor`,
                String(this.stockSymbolColorDefaultLight)
              )
            }
            this.$store.commit(
              `settings/setDeliveredTimeColor`,
              String(this.deliveredTimeColorDefaultLight)
            )
          }
          break
      }
    },
    changeFlashingSpeed() {
      this.$store.commit(`settings/setFlashingSpeed`, String(this.flashingSpeed))
    },
    changeWebLinkColor() {
      this.$store.commit(`settings/setWebLinkColor`, String(this.webLinkColor))
    },
    changeStockSymbolColor() {
      this.$store.commit(`settings/setStockSymbolColor`, String(this.stockSymbolColor))
    },
    changeDeliveredTimeColor() {
      this.$store.commit(`settings/setDeliveredTimeColor`, String(this.deliveredTimeColor))
    },
    changeTextBold() {
      this.textBold = !this.textBold
      this.$store.commit(`settings/setTextBold`, String(this.textBold))
    },
  },
}
</script>

<style lang="scss" scoped>
@import '../../../node_modules/@syncfusion/ej2-base/styles/material.css';
@import '../../../node_modules/@syncfusion/ej2-buttons/styles/material.css';
@import '../../../node_modules/@syncfusion/ej2-popups/styles/material.css';
@import '../../../node_modules/@syncfusion/ej2-splitbuttons/styles/material.css';
@import '../../../node_modules/@syncfusion/ej2-inputs/styles/material.css';

.alert-settings-container h1 {
  margin-bottom: 15px;
  color: #98a6ad;
  font-size: 30px;
  text-align: center;
}

.settings-flex-container {
  display: flex;
  flex-direction: column;
  justify-content: flex-start;

  label {
    margin: 0;
    line-height: 30px;
  }
}

.settings-column-headers {
  display: flex;
  justify-content: space-between;
  padding: 0 40px;
  margin-bottom: 10px;
  border-bottom: 1px solid #98a6ad;
}

.settings-column-headers h2 {
  margin-bottom: 15px;
  color: #98a6ad;
  text-align: center;
  font-size: 20px;
}

.settings-item {
  display: flex;
  justify-content: space-between;
  padding: 15px 30px 15px 25px;
  font-size: 16px;
  border-bottom: 1px solid #414040;

  label {
    color: #fff;
  }
}

.label {
  display: flex;
  flex-direction: column;
  justify-content: center;
}

.settings-item.web-link-color-container,
.settings-item.stock-symbol-color-container {
  padding-right: 30px;
}

.settings-item.text-container {
  padding-right: 30px;
}

.settings-item.incoming-messages-container {
  padding-right: 30px;
}

#number-of-flashes,
#text-font-size {
  color: #ffffff;
  width: 60px;
  background-color: #202020;
  padding-left: 15px;
  border: 1px solid #98a6ad;
  border-radius: 5px;
}

#incoming-messages {
  color: #ffffff;
  background-color: #202020;
  border: 1px solid #98a6ad;
  border-radius: 5px;
  padding: 2px;
}

#text-bold {
  padding: 3px 10px;
  margin-left: 10px;
  color: #ffffff;
  background-color: #202020;
  border: 1px solid #98a6ad;
  border-radius: 5px;
  outline: none;
}

#text-bold.active {
  background-color: #fff200;
  color: #202020;
  font-weight: bolder;
}
</style>
