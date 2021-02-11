<template>
    <div>
        <v-app id="comming">
            <v-main>
                <HeaderItem/>
                <div>comming soon.</div>
            </v-main>
        </v-app>
    </div>
</template>

<script>
import HeaderItem from '../components/Header'

  export default {
      name: 'comming',
      components : {
      HeaderItem
    }
  }
</script>