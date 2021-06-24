<template>
  <form id="key-search-form-container" @submit.prevent="$emit('keywordSubmitted', keyword)">
    <input
      id="key-search-input"
      ref="keySearchInput"
      type="search"
      placeholder="Search"
      v-model="keyword"
    />
    <div id="nothing-found-message" v-if="isSearchResultsEmpty">Nothing Found</div>
  </form>
</template>

<script>
export default {
  name: "KeySearchInput",
  props: {
    isSearchResultsEmpty: {
      type: Boolean,
      default: false,
      required: true,
    },
  },
  data: () => ({
    keyword: "",
  }),
  mounted() {
    this.$refs.keySearchInput.focus();
  },
};
</script>

<style scoped lang="scss">
#key-search-form-container {
  display: flex;
  justify-content: flex-end;
  margin-right: 7px;
}

#key-search-input {
  position: absolute;
  width: 170px;
  top: 80px;
  padding: 3px 10px;
  color: #fff;
  background-color: #000000;
  border: 1px solid #98a6ad;
  border-radius: 5px;
  z-index: 10;
  &::placeholder {
    text-align: left;
    font-size: 14px;
  }
}

#nothing-found-message {
  position: absolute;
  left: calc(50% - 45px);
  top: 50%;
}
</style>
