<template>
  <div class="settings-container">
    <AlertSettings />
  </div>
</template>

<script>
// import AlertSettings from '@/components/Settings/AlertSettings'
import AlertSettings from '/components/Settings/AlertSettings'
export default {
  name: 'SettingsLayout',
  components: { AlertSettings },
}
</script>

<style lang="scss" scoped>
.settings-container {
  position: absolute;
  top: 50px;
  height: 100%;
  width: 100%;
  padding: 20px;
  background-color: var(--chat-primary);
  user-select: none;
  z-index: 25;
  overflow: auto;
}
</style>
