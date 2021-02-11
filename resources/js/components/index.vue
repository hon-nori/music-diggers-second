<template>
    <v-app>
        <!-- ヘッダー -->
        <header>
            <v-app-bar
                app
                color="orange"
            >
                <v-app-bar-nav-icon @click="drawer = true"></v-app-bar-nav-icon>
                <v-toolbar-title>Music Diggers</v-toolbar-title>
                <v-tabs>
          <v-tab
            v-for="(headerItem, index) in headerItems"
            :key="index"
          >
            {{ headerItem.name }}
          </v-tab>
        </v-tabs>
            </v-app-bar>
            <!-- position:fixed menu-->
            <v-navigation-drawer v-model="drawer" fixed temporary>
                 <!-- navもdenseもレイアウトをイイ感じにマージンしてる-->
                <v-list nav dense>
                    <v-list-item-group>
                        <v-list-item v-for="(headerItem, index) in headerItems" :key="index">
                            <v-list-item-title>{{ headerItem.name }}</v-list-item-title>
                        </v-list-item>
                    </v-list-item-group>
                </v-list>
            </v-navigation-drawer>
        </header>
        <!-- //ヘッダー -->
    </v-app>
</template>

<script>
import constants from '../constants'

export default {
  data () {
    return {
      drawer: false,
      headerItems:constants.headerItems
    }
  }
}
</script>

<style lang="scss" scoped>
/* タイトルがはみ出ないように調整 */ 
.v-toolbar__title {
  overflow: visible !important;
  margin-right: 50px !important;
}

/* ハンバーガーメニューがPCで非表示となるように調整
TODO: mixinが読めないので後から
.v-app-bar__nav-icon {
  @include display_pc {
    display: none !important;
  }
} */ 
</style>