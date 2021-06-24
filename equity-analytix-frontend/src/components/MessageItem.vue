<template>
  <div
    class="message--wrap"
    :class="{
      'bg-black': status === 'none' || status === 'switch',
      'bg-grey':
        (status === 'none' || status === 'switch') &&
        !flashingData.isColorReset &&
        isFlashingAllowed,
      'bg-green':
        (status === 'bull' ||
          status === 'arrowPos' ||
          status === 'sightPos' ||
          status === 'eyePos' ||
          status === 'xfilesPos') &&
        !flashingData.isColorReset &&
        isFlashingAllowed,
      'bg-red':
        (status === 'bear' ||
          status === 'arrowNeg' ||
          status === 'sightNeg' ||
          status === 'eyeNeg' ||
          status === 'xfilesNeg') &&
        !flashingData.isColorReset &&
        isFlashingAllowed,
      'bg-yellow': status === 'alert' && !flashingData.isColorReset && isFlashingAllowed,
      'flashing-in-progress':
        isFlashingAllowed,
    }"
  >
    <div class="message-block-left">
      <div v-if="!isIconsHidden" class="item-status">
        <img :src="status ? imageOptions[status] : imageOptions.none" alt="Status icon" />
      </div>

      <div class="message-time-block">
        <div v-if="isSearchResultsInFeed" class="date-posted">{{ date }}</div>
        <span class="time-posted">{{ time }}</span>
        <div class="time-active" v-if="!isAdmin">{{ this.validateElapsedTime(elapsedTime) }}</div>
      </div>
    </div>
    <div class="message-block-center" :class="text === 'ALERT' ? 'alert-message-container' : ''">
      <div class="item-text">
        <div class="inner">
          <span
            class="message"
            :class="text === 'ALERT' ? 'alert-message-text' : ''"
            v-html="this.parseText(text)"
            v-linkified:options="{
              validate: {
                url: this.validateUrl,
              },
            }"
          />
        </div>
      </div>
    </div>
    <div class="message-block-right">
      <div
        class="item-delete"
        v-if="!isAdmin && !isSearchResultsInFeed"
        @click="$emit('clickToDelete', id)"
        :class="{
          'black-color':
            status === 'none' ||
            status === 'switch' ||
            (flashingData.isColorReset && isFlashingAllowed),
          'green-color':
            (status === 'bull' ||
              status === 'arrowPos' ||
              status === 'sightPos' ||
              status === 'eyePos' ||
              status === 'xfilesPos') &&
            !flashingData.isColorReset &&
            isFlashingAllowed,
          'red-color':
            (status === 'bear' ||
              status === 'arrowNeg' ||
              status === 'sightNeg' ||
              status === 'eyeNeg' ||
              status === 'xfilesNeg') &&
            !flashingData.isColorReset &&
            isFlashingAllowed,
          'yellow-color': status === 'alert' && !flashingData.isColorReset && isFlashingAllowed,
        }"
      ></div>
    </div>
  </div>
</template>

<script>
import { urlBaseToPicture } from '../../appConfig'
import moment from 'moment'

export default {
  name: 'MessageItem',
  props: {
    feed: {
      type: Array,
      required: true,
    },
    index: {
      type: Number,
      required: true,
    },
    id: {
      type: String,
      default: '',
      required: true,
    },
    created: {
      type: String,
      default: '',
      required: true,
    },
    time: {
      type: String,
      default: '',
      required: true,
    },
    date: {
      type: String,
      default: '',
      required: false,
    },
    imageUrl: {
      type: String,
      default: '',
      required: false,
    },
    text: {
      type: String,
      default: '',
      required: true,
    },
    status: {
      type: String,
      default: '',
      required: true,
    },
    flashingData: {
      type: Object,
      default: () => ({
        isColorReset: false,
      }),
      required: false,
    },
    currentTime: {
      default: '',
      required: false,
    },
  },
  data: () => ({
    isAdmin: false,
    imageOptions: {
      none: `${urlBaseToPicture}images/burger-menu.svg`,
      bear: `${urlBaseToPicture}images/neg-v3.svg`,
      bull: `${urlBaseToPicture}images/pos-v3.svg`,
      arrowNeg: `${urlBaseToPicture}images/red-arrow-down.svg`,
      arrowPos: `${urlBaseToPicture}images/green-arrow-up.svg`,
      sightNeg: `${urlBaseToPicture}images/red-target.svg`,
      sightPos: `${urlBaseToPicture}images/green-target.svg`,
      eyeNeg: `${urlBaseToPicture}images/red-circle.svg`,
      eyePos: `${urlBaseToPicture}images/green-circle.svg`,
      xfilesNeg: `${urlBaseToPicture}images/red-alien-v3.svg`,
      xfilesPos: `${urlBaseToPicture}images/green-alien-v3.svg`,
      alert: `${urlBaseToPicture}images/alert.svg`,
      switch: `${urlBaseToPicture}images/change-position.svg`,
    },
  }),
  computed: {
    isSearchResultsInFeed: function () {
      return this.$store.getters['chat/keyword']
    },
    elapsedTime: function () {
      const postedTime = moment(this.created)
      return this.currentTime && postedTime ? this.currentTime.diff(postedTime) : ''
    },
    isFlashingAllowed: function () {
      const messagesAllowedToFlashing = this.$store.getters['chat/flashingMessages']
      const allowedFlashingActivationTime = 30000

      return (
        messagesAllowedToFlashing.find((allowedId) => allowedId === this.id) &&
        this.elapsedTime < allowedFlashingActivationTime
      )
    },
    isIconsHidden: function () {
      return this.$store.getters['settings/hideIcons'] === 'true'
    },
  },
  created() {
    this.isAdmin = this.$store.getters['chat/isAdmin']
  },
  mounted() {
    const currentMessageIndex = this.index
    const lastMessageIndex = this.feed.length - 1
    if (currentMessageIndex === lastMessageIndex) {
      this.$emit('lastMessageIsMounted')
    }
  },
  methods: {
    // adds styles for links and stock symbols, highlights matches when searching by keyword
    parseText(text) {
      const keyword = this.$store.getters['chat/keyword']
      const isKeywordCombination = /\b.+\s.+\b/.test(keyword)
      const keywordCombination = isKeywordCombination ? keyword.split(' ') : null
      const words = text.split(' ')
      let convertedText = []

      if (!isKeywordCombination) {
        const keywordMatcher = keyword
          ? new RegExp(this.escapeSpecialCharacters(keyword.trim().toLowerCase()))
          : null

        for (let word of words) {
          const isStockSymbol = /\$.+/.test(word)
          const isKeywordMatch = keyword ? keywordMatcher.test(word.toLowerCase()) : false
          let punctuation = ''
          if (isStockSymbol || isKeywordMatch) {
            punctuation = word.match(/\W$/) ? word.match(/\W$/) : ''
            if (punctuation) {
              word = word.slice(0, -1)
            }
          }
          const additionalClasses = this.getAdditionalClasses(isKeywordMatch, isStockSymbol)
          const convertedWord =
            isStockSymbol || isKeywordMatch
              ? `<span class='${additionalClasses}'>${word}</span>${punctuation}`
              : word
          convertedText.push(convertedWord)
        }

        return convertedText.join(' ')
      } else {
        let isFirstIteration = true
        for (const keywordPart of keywordCombination) {
          const keywordMatcher = keywordPart
            ? new RegExp(this.escapeSpecialCharacters(keywordPart.trim().toLowerCase()))
            : null

          for (let [index, word] of words.entries()) {
            const isStockSymbol = /\$.+/.test(word)
            const isKeywordMatch = keywordPart ? keywordMatcher.test(word.toLowerCase()) : false
            let punctuation = ''
            if (isStockSymbol || isKeywordMatch) {
              punctuation = word.match(/\W$/) ? word.match(/\W$/) : ''
              if (punctuation) {
                word = word.slice(0, -1)
              }
            }
            const additionalClasses = this.getAdditionalClasses(isKeywordMatch, isStockSymbol)
            const convertedWord =
              isStockSymbol || isKeywordMatch
                ? `<span class='${additionalClasses}'>${word}</span>${punctuation}`
                : word
            if (isFirstIteration) {
              convertedText.push(convertedWord)
            } else {
              if (word.toLowerCase() === keywordPart.toLowerCase()) {
                convertedText[index] = convertedWord
              }
            }
          }
          isFirstIteration = false
        }

        return convertedText.join(' ')
      }
    },
    getAdditionalClasses(isKeywordMatch, isStockSymbol) {
      let additionalClasses = []
      if (isKeywordMatch) additionalClasses.push('highlighted-text')
      if (isStockSymbol) additionalClasses.push('stock-symbol')

      return additionalClasses.join(' ')
    },
    escapeSpecialCharacters(string) {
      return string.replace(/[.*+?^${}()|[\]\\]/g, '\\$&')
    },
    validateUrl(value) {
      const regexp = /^(?:http(s)?:\/\/)?[\w.-]+(?:\.[\w\.-]+)+[\w\-\._~:/?#[\]@!\$&'\(\)\*\+,;=.]+$/
      return regexp.test(value)
    },
    validateElapsedTime(timestamp) {
      const seconds = moment.duration(timestamp).seconds()
      const convertedSeconds = seconds > 9 ? `:${seconds}` : `:0${seconds}`
      const minutes = moment.duration(timestamp).minutes()
        ? moment.duration(timestamp).minutes()
        : ''
      const hours = moment.duration(timestamp).hours() ? moment.duration(timestamp).hours() : ''

      let result = hours + minutes + convertedSeconds
      if (minutes >= 10) result = `${minutes}m`
      if (hours >= 1) result = `${hours}h`

      return result
    },
  },
}
</script>

<style lang="scss">
.message--wrap {
  display: flex;
  width: 100%;
  border-radius: 4px;
  transition: background 0.05s linear;

  &:hover {
    background-color: var(--chat-message-hover);
    transition: background 0.05s linear;
  }

  &:hover .item-delete {
    background-color: var(--chat-item-delete);
    transition: stroke 0.3s linear;
  }
}

.message--wrap.flashing-in-progress {
  transition: background 0.3s linear;
}


.message-block-left {
  display: flex;
  flex-direction: row;
  padding: 3px 3px;
}

.message-time-block {
  display: flex;
  flex-direction: column;
  justify-content: flex-start;
  margin: 5px 0 5px 0;
}

.message-block-center {
  margin-right: auto;
  padding: 8px 0;
}

.message-block-right {
  min-width: 10px;
  height: 100%;
}

.item-delete {
  height: 100%;
  width: 100%;
  transition: stroke 0.3s linear;
}

.linkified,
.linkified:hover {
  color: var(--link-color) !important;
  text-decoration: underline;
  font-weight: normal;
}

.stock-symbol {
  color: var(--stock-symbol-color);
  text-transform: uppercase;
  font-weight: bold;
}

.highlighted-text {
  background-color: #fff200;
  color: #000;
  padding: 1px;
}

.alert-message-container {
  display: flex;
  justify-content: center;
  align-items: center;
  width: 100%;
  text-align: center;
}

.alert-message-text {
  font-weight: 700;
}
</style>
