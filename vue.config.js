module.exports = {
    transpileDependencies: [
      'vuetify'
    ],
    css: {
      loaderOptions: {
        scss: {
          prependData: '@import "resources/sass/_mixin.scss";'
        }
      }
    }
  }